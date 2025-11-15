<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\Admin\AdminLoginController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StampCorrectionRequestController;

// トップページ（ログイン済みの場合は勤怠登録画面へリダイレクト）
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->is_admin) {
            return redirect('/admin/attendance/list');
        }
        return redirect('/attendance');
    }
    return redirect('/login');
});

// 一般ユーザー向け認証
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// 管理者向け認証
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// 認証が必要なルート（一般ユーザー）
Route::middleware(['auth', 'verified'])->group(function () {
    // 勤怠登録画面（PG03）
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

    // 打刻機能
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.break-start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.break-end');

    // 勤怠一覧画面（PG04）
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');

    // 勤怠詳細画面（PG05）
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'detail'])->name('attendance.detail');
    Route::post('/attendance/detail/{id}/request-correction', [AttendanceController::class, 'requestCorrection'])->name('attendance.request-correction');

    // 申請一覧画面（PG06: 一般ユーザー、PG12: 管理者）- 同じパスを使用
    Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'list'])->name('stamp_correction_request.list');
});

// 認証が必要なルート（管理者）
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
        // 勤怠一覧画面（PG08）
        Route::get('/attendance/list', [AdminController::class, 'attendanceList'])->name('attendance.list');

        // 勤怠詳細画面（PG09）
        Route::get('/attendance/{id}', [AdminController::class, 'attendanceDetail'])->name('attendance.detail');
        Route::post('/attendance/{id}/update', [AdminController::class, 'updateAttendance'])->name('attendance.update');

        // スタッフ一覧画面（PG10）
        Route::get('/staff/list', [AdminController::class, 'staffList'])->name('staff.list');

        // スタッフ別勤怠一覧画面（PG11）
        Route::get('/attendance/staff/{id}', [AdminController::class, 'staffAttendanceList'])->name('attendance.staff');
        Route::get('/attendance/staff/{id}/csv', [AdminController::class, 'staffAttendanceCsv'])->name('attendance.staff.csv');
});

// 認証が必要なルート（管理者 - PG13は一般ユーザーと同じパスを使用）
// コントローラー内で管理者権限をチェック
Route::middleware(['auth', 'verified'])->group(function () {
        // 修正申請承認画面（PG13）
        Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [StampCorrectionRequestController::class, 'approveDetail'])->name('admin.stamp_correction_request.approve.detail');
        Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [StampCorrectionRequestController::class, 'approve'])->name('admin.stamp_correction_request.approve');
        Route::post('/stamp_correction_request/reject/{attendance_correct_request_id}', [StampCorrectionRequestController::class, 'reject'])->name('admin.stamp_correction_request.reject');
});

// メール認証ルート
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/attendance');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->intended('/attendance');
    }

    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
