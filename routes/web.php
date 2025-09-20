<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;

// Fortify認証ルート（自動で登録される）
// 既存の手動認証ルートはコメントアウト
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 商品ルート（要件に合わせてパスを修正）
Route::get('/', [ProductController::class, 'index'])->middleware('check.first.login')->name('products.index');
Route::get('/item/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/sell', [ProductController::class, 'create'])->middleware(['auth', 'check.first.login'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->middleware(['auth', 'check.first.login']);
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware(['auth', 'check.first.login'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->middleware(['auth', 'check.first.login']);
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware(['auth', 'check.first.login']);

// プロフィールルート（要件に合わせてパスを修正）
Route::get('/mypage', [ProfileController::class, 'show'])->middleware(['auth', 'check.first.login'])->name('profile.show');
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

// コメントルート
Route::post('/comments', [CommentController::class, 'store'])->middleware(['auth', 'check.first.login'])->name('comments.store');

// 購入ルート（要件に合わせてパスを修正）
Route::get('/purchase/{product}', [PurchaseController::class, 'create'])->middleware(['auth', 'check.first.login'])->name('purchases.create');
Route::post('/purchase/{product}', [PurchaseController::class, 'store'])->middleware(['auth', 'check.first.login'])->name('purchases.store');
Route::get('/purchase/address/{product}', [PurchaseController::class, 'editAddress'])->middleware(['auth', 'check.first.login'])->name('purchases.edit_address');
Route::put('/purchase/address/{product}', [PurchaseController::class, 'updateAddress'])->middleware(['auth', 'check.first.login'])->name('purchases.update_address');

// いいねルート
Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->middleware(['auth', 'check.first.login'])->name('favorites.store');
Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->middleware(['auth', 'check.first.login'])->name('favorites.destroy');
