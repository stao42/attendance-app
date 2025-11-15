<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\BreakRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * 管理者権限をチェック
     */
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'このページにアクセスする権限がありません。');
        }
    }

    /**
     * 管理画面トップページ
     */
    public function index()
    {
        $this->checkAdmin();
        $today = Carbon::today();

        $stats = [
            'total_users' => User::count(),
            'today_attendances' => AttendanceRecord::where('date', $today)
                ->whereNotNull('clock_in')
                ->count(),
            'today_completed' => AttendanceRecord::where('date', $today)
                ->whereNotNull('clock_in')
                ->whereNotNull('clock_out')
                ->count(),
        ];

        $recentRecords = AttendanceRecord::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.index', compact('stats', 'recentRecords'));
    }

    /**
     * 勤怠一覧画面（PG08）を表示
     */
    public function attendanceList(Request $request)
    {
        $this->checkAdmin();
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $records = AttendanceRecord::with('user')
            ->where('date', $date)
            ->orderBy('clock_in', 'asc')
            ->get();

        $prevDate = Carbon::parse($date)->subDay()->format('Y-m-d');
        $nextDate = Carbon::parse($date)->addDay()->format('Y-m-d');

        return view('admin.attendance_list', compact('records', 'date', 'prevDate', 'nextDate'));
    }

    /**
     * 勤怠詳細画面（PG09）を表示
     */
    public function attendanceDetail($id)
    {
        $this->checkAdmin();
        $record = AttendanceRecord::with(['user', 'breaks', 'stampCorrectionRequests'])
            ->findOrFail($id);

        $hasPendingRequest = $record->hasPendingCorrectionRequest();

        return view('admin.attendance_detail', compact('record', 'hasPendingRequest'));
    }

    /**
     * 勤怠情報を更新
     */
    public function updateAttendance(Request $request, $id)
    {
        $this->checkAdmin();
        $record = AttendanceRecord::with('stampCorrectionRequests')
            ->findOrFail($id);

        // 承認待ちの申請がある場合はエラー
        if ($record->hasPendingCorrectionRequest()) {
            return redirect()->back()->with('error', '承認待ちのため修正はできません。');
        }

        // バリデーション
        $validated = $request->validate([
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'notes' => ['required', 'string', function ($attribute, $value, $fail) {
                if (trim($value) === '') {
                    $fail('備考を記入してください');
                }
            }],
            'break_starts' => 'nullable|array',
            'break_starts.*' => 'nullable|date_format:H:i',
            'break_ends' => 'nullable|array',
            'break_ends.*' => 'nullable|date_format:H:i',
        ], [
            'notes.required' => '備考を記入してください',
        ]);

        // 時刻の妥当性チェック
        if ($validated['clock_out']) {
            $clockIn = Carbon::createFromTimeString($validated['clock_in']);
            $clockOut = Carbon::createFromTimeString($validated['clock_out']);

            if ($clockOut <= $clockIn) {
                return redirect()->back()->withErrors(['clock_out' => '出勤時間もしくは退勤時間が不適切な値です']);
            }

            // 休憩時間の妥当性チェック
            if (isset($validated['break_starts']) && isset($validated['break_ends'])) {
                foreach ($validated['break_starts'] as $index => $breakStart) {
                    if ($breakStart && isset($validated['break_ends'][$index]) && $validated['break_ends'][$index]) {
                        $breakStartTime = Carbon::createFromTimeString($breakStart);
                        $breakEndTime = Carbon::createFromTimeString($validated['break_ends'][$index]);

                        // 休憩開始時間が退勤時間より後の場合
                        if ($breakStartTime >= $clockOut) {
                            return redirect()->back()->withErrors([
                                "break_starts.{$index}" => '休憩時間が不適切な値です'
                            ]);
                        }

                        // 休憩終了時間が退勤時間より後の場合
                        if ($breakEndTime > $clockOut) {
                            return redirect()->back()->withErrors([
                                "break_ends.{$index}" => '休憩時間もしくは退勤時間が不適切な値です'
                            ]);
                        }

                        // 休憩開始時間が休憩終了時間より後の場合
                        if ($breakEndTime <= $breakStartTime) {
                            return redirect()->back()->withErrors([
                                "break_ends.{$index}" => '休憩時間が不適切な値です'
                            ]);
                        }
                    }
                }
            }
        }

        // 勤怠記録を更新
        $record->update([
            'clock_in' => $validated['clock_in'],
            'clock_out' => $validated['clock_out'] ?? null,
            'notes' => $validated['notes'],
        ]);

        // 休憩記録を更新
        $record->breaks()->delete();
        if (isset($validated['break_starts']) && isset($validated['break_ends'])) {
            foreach ($validated['break_starts'] as $index => $breakStart) {
                if ($breakStart && isset($validated['break_ends'][$index]) && $validated['break_ends'][$index]) {
                    $breakEnd = $validated['break_ends'][$index];
                    $breakStartTime = Carbon::createFromTimeString($breakStart);
                    $breakEndTime = Carbon::createFromTimeString($breakEnd);
                    $breakDuration = $breakStartTime->diffInSeconds($breakEndTime);

                    BreakRecord::create([
                        'attendance_record_id' => $record->id,
                        'break_start' => $breakStart,
                        'break_end' => $breakEnd,
                        'break_duration' => $breakDuration,
                    ]);
                }
            }
        }

        // 勤務時間を再計算
        if ($record->clock_out) {
            $totalBreakSeconds = $record->breaks()->sum('break_duration');
            $breakHours = floor($totalBreakSeconds / 3600);
            $breakMinutes = floor(($totalBreakSeconds % 3600) / 60);
            $breakSeconds = $totalBreakSeconds % 60;
            $breakTime = sprintf('%02d:%02d:%02d', $breakHours, $breakMinutes, $breakSeconds);

            $workTime = $this->calculateWorkTime($record->clock_in, $record->clock_out, $breakTime);
            $record->update(['work_time' => $workTime, 'break_time' => $breakTime]);
        }

        return redirect()->route('admin.attendance.list')->with('success', '勤怠情報を更新しました。');
    }

    /**
     * スタッフ一覧画面（PG10）を表示
     */
    public function staffList()
    {
        $this->checkAdmin();
        $users = User::where('is_admin', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.staff_list', compact('users'));
    }

    /**
     * スタッフ別勤怠一覧画面（PG11）を表示
     */
    public function staffAttendanceList($id, Request $request)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $records = AttendanceRecord::where('user_id', $id)
            ->whereYear('date', Carbon::parse($month)->year)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->orderBy('date', 'desc')
            ->get();

        $prevMonth = Carbon::parse($month)->subMonth()->format('Y-m');
        $nextMonth = Carbon::parse($month)->addMonth()->format('Y-m');

        return view('admin.staff_attendance_list', compact('user', 'records', 'month', 'prevMonth', 'nextMonth'));
    }

    /**
     * CSV出力機能
     */
    public function staffAttendanceCsv($id, Request $request)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $records = AttendanceRecord::with('breaks')
            ->where('user_id', $id)
            ->whereYear('date', Carbon::parse($month)->year)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->orderBy('date', 'asc')
            ->get();

        $fileName = sprintf('勤怠一覧_%s_%s.csv', $user->name, Carbon::parse($month)->format('Y年m月'));

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');

            // BOM付きUTF-8でCSVを出力
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // ヘッダー行
            fputcsv($file, ['日付', '出勤時刻', '退勤時刻', '休憩時間', '勤務時間', '備考']);

            // データ行
            foreach ($records as $record) {
                $totalBreakSeconds = $record->breaks()->sum('break_duration');
                $breakHours = floor($totalBreakSeconds / 3600);
                $breakMinutes = floor(($totalBreakSeconds % 3600) / 60);
                $breakSeconds = $totalBreakSeconds % 60;
                $breakTime = sprintf('%02d:%02d:%02d', $breakHours, $breakMinutes, $breakSeconds);

                fputcsv($file, [
                    $record->date->format('Y/m/d'),
                    $record->clock_in ?? '',
                    $record->clock_out ?? '',
                    $breakTime,
                    $record->work_time ?? '',
                    $record->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * ユーザー一覧（旧実装との互換性のため）
     */
    public function users()
    {
        $this->checkAdmin();
        $users = User::withCount('attendanceRecords')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * ユーザーの勤怠履歴（旧実装との互換性のため）
     */
    public function userAttendance($userId, Request $request)
    {
        $this->checkAdmin();
        $user = User::findOrFail($userId);
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $records = AttendanceRecord::where('user_id', $userId)
            ->whereYear('date', Carbon::parse($month)->year)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('admin.user_attendance', compact('user', 'records', 'month'));
    }

    /**
     * 全ユーザーの勤怠状況（旧実装との互換性のため）
     */
    public function attendanceStatus(Request $request)
    {
        $this->checkAdmin();
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $records = AttendanceRecord::with('user')
            ->where('date', $date)
            ->orderBy('clock_in', 'asc')
            ->get();

        $usersWithoutRecord = User::whereDoesntHave('attendanceRecords', function ($query) use ($date) {
            $query->where('date', $date);
        })->get();

        return view('admin.attendance_status', compact('records', 'usersWithoutRecord', 'date'));
    }

    /**
     * 勤務時間を計算
     */
    private function calculateWorkTime($clockIn, $clockOut, $breakTime = '00:00:00')
    {
        $clockInParts = explode(':', $clockIn);
        $clockOutParts = explode(':', $clockOut);
        $breakTimeParts = explode(':', $breakTime);

        $clockInSeconds = ($clockInParts[0] * 3600) + ($clockInParts[1] * 60) + ($clockInParts[2] ?? 0);
        $clockOutSeconds = ($clockOutParts[0] * 3600) + ($clockOutParts[1] * 60) + ($clockOutParts[2] ?? 0);
        $breakSeconds = ($breakTimeParts[0] * 3600) + ($breakTimeParts[1] * 60) + ($breakTimeParts[2] ?? 0);

        if ($clockOutSeconds < $clockInSeconds) {
            $clockOutSeconds += 24 * 3600;
        }

        $workSeconds = $clockOutSeconds - $clockInSeconds - $breakSeconds;

        if ($workSeconds < 0) {
            $workSeconds = 0;
        }

        $hours = floor($workSeconds / 3600);
        $minutes = floor(($workSeconds % 3600) / 60);
        $seconds = $workSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
