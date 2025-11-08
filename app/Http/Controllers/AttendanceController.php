<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\BreakRecord;
use App\Models\StampCorrectionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * 勤怠登録画面（PG03）を表示
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $todayRecord = AttendanceRecord::with('breaks')
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $status = $todayRecord ? $todayRecord->getStatus() : '勤務外';

        return view('attendance.index', compact('todayRecord', 'status'));
    }

    /**
     * 出勤打刻
     */
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 今日の記録が既に存在するか確認
        $existingRecord = AttendanceRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existingRecord && $existingRecord->clock_in) {
            return redirect()->back()->with('error', '既に出勤打刻が記録されています。');
        }

        $clockInTime = Carbon::now();

        if ($existingRecord) {
            $existingRecord->update([
                'clock_in' => $clockInTime->format('H:i:s'),
            ]);
        } else {
            AttendanceRecord::create([
                'user_id' => $user->id,
                'date' => $today,
                'clock_in' => $clockInTime->format('H:i:s'),
            ]);
        }

        return redirect()->back()->with('success', '出勤打刻が完了しました。');
    }

    /**
     * 退勤打刻
     */
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $record = AttendanceRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->with('breaks')
            ->first();

        if (!$record || !$record->clock_in) {
            return redirect()->back()->with('error', 'まず出勤打刻を行ってください。');
        }

        if ($record->clock_out) {
            return redirect()->back()->with('error', '既に退勤打刻が記録されています。');
        }

        // 休憩中でないことを確認
        $lastBreak = $record->breaks()->orderBy('created_at', 'desc')->first();
        if ($lastBreak && $lastBreak->break_start && !$lastBreak->break_end) {
            return redirect()->back()->with('error', '休憩中です。先に休憩戻を行ってください。');
        }

        $clockOutTime = Carbon::now();

        // 休憩時間の合計を計算
        $totalBreakSeconds = $record->breaks()
            ->whereNotNull('break_end')
            ->sum('break_duration');

        $breakHours = floor($totalBreakSeconds / 3600);
        $breakMinutes = floor(($totalBreakSeconds % 3600) / 60);
        $breakSeconds = $totalBreakSeconds % 60;
        $breakTime = sprintf('%02d:%02d:%02d', $breakHours, $breakMinutes, $breakSeconds);

        $workTime = $this->calculateWorkTime($record->clock_in, $clockOutTime->format('H:i:s'), $breakTime);

        $record->update([
            'clock_out' => $clockOutTime->format('H:i:s'),
            'break_time' => $breakTime,
            'work_time' => $workTime,
        ]);

        return redirect()->back()->with('success', 'お疲れ様でした。退勤打刻が完了しました。');
    }

    /**
     * 休憩開始
     */
    public function breakStart(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $record = AttendanceRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->with('breaks')
            ->first();

        if (!$record || !$record->clock_in) {
            return redirect()->back()->with('error', 'まず出勤打刻を行ってください。');
        }

        if ($record->clock_out) {
            return redirect()->back()->with('error', '既に退勤しています。');
        }

        // 最後の休憩が終了しているか確認
        $lastBreak = $record->breaks()->orderBy('created_at', 'desc')->first();
        if ($lastBreak && $lastBreak->break_start && !$lastBreak->break_end) {
            return redirect()->back()->with('error', '既に休憩中です。');
        }

        BreakRecord::create([
            'attendance_record_id' => $record->id,
            'break_start' => Carbon::now()->format('H:i:s'),
        ]);

        return redirect()->back()->with('success', '休憩開始しました。');
    }

    /**
     * 休憩終了
     */
    public function breakEnd(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $record = AttendanceRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->with('breaks')
            ->first();

        if (!$record || !$record->clock_in) {
            return redirect()->back()->with('error', 'まず出勤打刻を行ってください。');
        }

        // 最後の休憩を取得
        $lastBreak = $record->breaks()->orderBy('created_at', 'desc')->first();

        if (!$lastBreak || !$lastBreak->break_start || $lastBreak->break_end) {
            return redirect()->back()->with('error', '休憩中ではありません。');
        }

        $breakEndTime = Carbon::now();
        $breakStart = Carbon::createFromTimeString($lastBreak->break_start);
        $breakDuration = $breakStart->diffInSeconds($breakEndTime);

        $lastBreak->update([
            'break_end' => $breakEndTime->format('H:i:s'),
            'break_duration' => $breakDuration,
        ]);

        return redirect()->back()->with('success', '休憩終了しました。');
    }

    /**
     * 勤怠一覧画面（PG04）を表示
     */
    public function list(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $records = AttendanceRecord::where('user_id', $user->id)
            ->whereYear('date', Carbon::parse($month)->year)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->orderBy('date', 'desc')
            ->get();

        $prevMonth = Carbon::parse($month)->subMonth()->format('Y-m');
        $nextMonth = Carbon::parse($month)->addMonth()->format('Y-m');

        return view('attendance.list', compact('records', 'month', 'prevMonth', 'nextMonth'));
    }

    /**
     * 勤怠詳細画面（PG05）を表示
     */
    public function detail($id)
    {
        $user = Auth::user();

        $record = AttendanceRecord::with(['breaks', 'stampCorrectionRequests'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $hasPendingRequest = $record->hasPendingCorrectionRequest();

        return view('attendance.detail', compact('record', 'hasPendingRequest'));
    }

    /**
     * 修正申請を送信
     */
    public function requestCorrection(Request $request, $id)
    {
        $user = Auth::user();

        $record = AttendanceRecord::with('stampCorrectionRequests')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // 承認待ちの申請がある場合はエラー
        if ($record->hasPendingCorrectionRequest()) {
            return redirect()->back()->with('error', '承認待ちのため修正はできません。');
        }

        // バリデーション
        $validated = $request->validate([
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'notes' => 'required|string',
            'break_starts' => 'nullable|array',
            'break_starts.*' => 'nullable|date_format:H:i',
            'break_ends' => 'nullable|array',
            'break_ends.*' => 'nullable|date_format:H:i',
        ]);

        // 時刻の妥当性チェック
        if ($validated['clock_out']) {
            $clockIn = Carbon::createFromTimeString($validated['clock_in']);
            $clockOut = Carbon::createFromTimeString($validated['clock_out']);

            if ($clockOut <= $clockIn) {
                return redirect()->back()->withErrors(['clock_out' => '出勤時間もしくは退勤時間が不適切な値です']);
            }
        }

        // 修正申請を作成
        StampCorrectionRequest::create([
            'attendance_record_id' => $record->id,
            'user_id' => $user->id,
            'requested_clock_in' => $validated['clock_in'],
            'requested_clock_out' => $validated['clock_out'] ?? null,
            'requested_notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return redirect()->route('attendance.list')->with('success', '修正申請を送信しました。');
    }

    /**
     * 勤務時間を計算
     */
    private function calculateWorkTime($clockIn, $clockOut, $breakTime = '00:00:00')
    {
        // 時刻のみの文字列（HH:MM:SS形式）を解析
        $clockInParts = explode(':', $clockIn);
        $clockOutParts = explode(':', $clockOut);
        $breakTimeParts = explode(':', $breakTime);

        $clockInSeconds = ($clockInParts[0] * 3600) + ($clockInParts[1] * 60) + ($clockInParts[2] ?? 0);
        $clockOutSeconds = ($clockOutParts[0] * 3600) + ($clockOutParts[1] * 60) + ($clockOutParts[2] ?? 0);
        $breakSeconds = ($breakTimeParts[0] * 3600) + ($breakTimeParts[1] * 60) + ($breakTimeParts[2] ?? 0);

        // 退勤時刻が出勤時刻より前の場合（日をまたいだ場合）は24時間を加算
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
