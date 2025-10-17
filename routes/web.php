<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;

// カスタム認証ルート
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Fortify認証ルート（自動で登録される）
// 既存の手動認証ルートはコメントアウト
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 商品ルート（要件に合わせてパスを修正）
Route::get('/', [ProductController::class, 'index'])->middleware(['verified', 'check.first.login'])->name('products.index');
Route::get('/item/{product}', [ProductController::class, 'show'])->middleware('verified')->name('products.show');
Route::get('/sell', [ProductController::class, 'create'])->middleware(['auth', 'verified', 'check.first.login'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->middleware(['auth', 'verified', 'check.first.login'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware(['auth', 'verified', 'check.first.login'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->middleware(['auth', 'verified', 'check.first.login'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware(['auth', 'verified', 'check.first.login'])->name('products.destroy');

// プロフィールルート（要件に合わせてパスを修正）
Route::get('/mypage', [ProfileController::class, 'show'])->middleware(['auth', 'verified', 'check.first.login'])->name('profile.show');
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->middleware(['auth', 'verified'])->name('profile.update');

// コメントルート
Route::post('/comments', [CommentController::class, 'store'])->middleware(['auth', 'verified', 'check.first.login'])->name('comments.store');

// 購入ルート（要件に合わせてパスを修正）
Route::get('/purchase/{product}', [PurchaseController::class, 'create'])->middleware(['auth', 'verified', 'check.first.login'])->name('purchases.create');
Route::post('/purchase/{product}', [PurchaseController::class, 'store'])->middleware(['auth', 'verified', 'check.first.login'])->name('purchases.store');
Route::get('/purchase/address/{product}', [PurchaseController::class, 'editAddress'])->middleware(['auth', 'verified', 'check.first.login'])->name('purchases.edit_address');
Route::put('/purchase/address/{product}', [PurchaseController::class, 'updateAddress'])->middleware(['auth', 'verified', 'check.first.login'])->name('purchases.update_address');
Route::get('/purchase/{product}/payment/{purchase}', [PurchaseController::class, 'payment'])->middleware(['auth', 'verified', 'check.first.login'])->name('purchases.payment');
Route::get('/purchase/{product}/success/{purchase}', [PurchaseController::class, 'success'])->middleware(['auth', 'verified', 'check.first.login'])->name('purchases.success');
Route::get('/purchase/{product}/cancel/{purchase}', [PurchaseController::class, 'cancel'])->middleware(['auth', 'verified', 'check.first.login'])->name('purchases.cancel');

// いいねルート
Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->middleware(['auth', 'verified', 'check.first.login'])->name('favorites.store');
Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->middleware(['auth', 'verified', 'check.first.login'])->name('favorites.destroy');

// メール認証関連ルート
Route::get('/email/verify', function () {
    return view('auth.email-verification-notice');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // 初回ログインかどうかをチェック
    $user = $request->user();
    if ($user->is_first_login) {
        return redirect()->route('profile.setup');
    } else {
        return redirect()->route('products.index');
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ダッシュボードルート
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// プロフィール設定ルート
Route::get('/profile/setup', [ProfileSetupController::class, 'show'])->middleware(['auth', 'verified'])->name('profile.setup');
Route::post('/profile/setup', [ProfileSetupController::class, 'store'])->middleware(['auth', 'verified'])->name('profile.setup.store');
