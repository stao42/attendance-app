<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\BreakRecord;
use App\Models\StampCorrectionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StampCorrectionRequestController extends Controller
{
    /**
     * 申請一覧画面（PG06: 一般ユーザー、PG12: 管理者）
     * 同じURLを使用し、コントローラー側で管理者かどうかを判断
     */
    public function list(Request $request)
    {
        $user = Auth::user();

        // 管理者の場合は管理者用のビューを表示
        if ($user->is_admin) {
            $pendingRequests = StampCorrectionRequest::with(['attendanceRecord', 'attendanceRecord.user', 'user'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            $approvedRequests = StampCorrectionRequest::with(['attendanceRecord', 'attendanceRecord.user', 'user', 'approver'])
                ->where('status', 'approved')
                ->orderBy('approved_at', 'desc')
                ->get();

            return view('stamp_correction_request.admin_list', compact('pendingRequests', 'approvedRequests'));
        }

        // 一般ユーザーの場合
        $pendingRequests = StampCorrectionRequest::with(['attendanceRecord', 'attendanceRecord.user'])
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedRequests = StampCorrectionRequest::with(['attendanceRecord', 'attendanceRecord.user', 'approver'])
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->get();

        return view('stamp_correction_request.list', compact('pendingRequests', 'approvedRequests'));
    }

    /**
     * 修正申請承認画面（PG13）を表示
     */
    public function approveDetail($attendance_correct_request_id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'このページにアクセスする権限がありません。');
        }

        $request = StampCorrectionRequest::with(['attendanceRecord', 'attendanceRecord.user', 'attendanceRecord.breaks', 'user'])
            ->findOrFail($attendance_correct_request_id);

        return view('stamp_correction_request.approve', compact('request'));
    }

    /**
     * 修正申請を承認
     */
    public function approve(Request $request, $attendance_correct_request_id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'このページにアクセスする権限がありません。');
        }

        $correctionRequest = StampCorrectionRequest::with('attendanceRecord')
            ->findOrFail($attendance_correct_request_id);

        if ($correctionRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'この申請は既に処理されています。');
        }

        $attendanceRecord = $correctionRequest->attendanceRecord;

        try {
            DB::transaction(function () use ($attendanceRecord, $correctionRequest) {
                // 勤怠記録を更新
                $attendanceRecord->update([
                    'clock_in' => $correctionRequest->requested_clock_in,
                    'clock_out' => $correctionRequest->requested_clock_out,
                    'notes' => $correctionRequest->requested_notes,
                ]);

                // 修正申請を承認済みに更新
                $correctionRequest->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => Carbon::now(),
                ]);
            });

            return redirect()->route('stamp_correction_request.list')->with('success', '修正申請を承認しました。');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '承認処理中にエラーが発生しました。');
        }
    }

    /**
     * 修正申請を却下
     */
    public function reject(Request $request, $attendance_correct_request_id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'このページにアクセスする権限がありません。');
        }

        $correctionRequest = StampCorrectionRequest::findOrFail($attendance_correct_request_id);

        if ($correctionRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'この申請は既に処理されています。');
        }

        try {
            DB::transaction(function () use ($correctionRequest) {
                // 修正申請を却下済みに更新
                $correctionRequest->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => Carbon::now(),
                ]);
            });

            return redirect()->route('stamp_correction_request.list')->with('success', '修正申請を却下しました。');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '却下処理中にエラーが発生しました。');
        }
    }
}
