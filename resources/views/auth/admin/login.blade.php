@extends('layouts.app')

@section('title', '管理者ログイン')

@section('styles')
<style>
    body {
        background-color: #FFFFFF;
    }

    /* コンテナのエラーメッセージを非表示（各フィールドの下に表示するため） */
    .container .alert {
        display: none;
    }

    .auth-page {
        min-height: 100vh;
        background-color: #FFFFFF;
        padding-top: 80px;
        padding-bottom: 40px;
    }

    .auth-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 80px;
        background-color: #000000;
        z-index: 1000;
    }

    .auth-header-content {
        max-width: 1512px;
        width: 100%;
        margin: 0 auto;
        height: 100%;
        display: flex;
        align-items: center;
        padding: 0 25px;
        position: relative;
    }

    .auth-header-logo {
        display: flex;
        align-items: center;
    }

    .auth-header-logo img {
        width: 370px;
        height: 36px;
        max-width: 100%;
    }

    .auth-container {
        max-width: 1512px;
        width: 100%;
        margin: 0 auto;
        padding: 81px 25px 40px;
        display: flex;
        justify-content: center;
    }

    .auth-form-wrapper {
        width: 100%;
        max-width: 680px;
        margin: 0 auto;
    }

    .auth-title {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 36px;
        line-height: 1.21;
        color: #000000;
        margin-bottom: 45px;
        text-align: center;
    }

    .auth-form-group {
        margin-bottom: 28px;
    }

    .auth-label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 24px;
        line-height: 1.21;
        color: #000000;
        margin-bottom: 10px;
    }

    .auth-input {
        width: 100%;
        height: 45px;
        padding: 0 16px;
        border: 1px solid #000000;
        border-radius: 4px;
        font-family: 'Inter', sans-serif;
        font-size: 16px;
        color: #000000;
        background-color: #FFFFFF;
        box-sizing: border-box;
    }

    .auth-input:focus {
        outline: none;
        border-color: #000000;
    }

    .auth-button {
        width: 100%;
        height: 60px;
        background-color: #000000;
        color: #FFFFFF;
        border: none;
        border-radius: 5px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 26px;
        line-height: 1.21;
        cursor: pointer;
        margin-top: 70px;
        margin-bottom: 17px;
    }

    .auth-button:hover {
        opacity: 0.9;
    }

    .auth-error {
        color: #DC3545;
        font-size: 14px;
        margin-top: 4px;
    }

    @media (max-width: 1540px) {
        .auth-header-logo img {
            width: min(370px, 25vw);
        }
    }

    @media (max-width: 768px) {
        .auth-title {
            font-size: 28px;
        }

        .auth-label {
            font-size: 20px;
        }

        .auth-input {
            font-size: 14px;
        }

        .auth-button {
            font-size: 22px;
            height: 50px;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-page">
    <!-- ヘッダー -->
    <div class="auth-header">
        <div class="auth-header-content">
            <div class="auth-header-logo">
                <img src="{{ asset('images/coachtech-logo.svg') }}" alt="CoachTech">
            </div>
        </div>
    </div>

    <!-- 管理者ログインフォーム -->
    <div class="auth-container">
        <div class="auth-form-wrapper">
            <h1 class="auth-title">管理者ログイン</h1>

            <form method="POST" action="{{ route('admin.login') }}" novalidate>
                @csrf

                <!-- メールアドレス -->
                <div class="auth-form-group">
                    <label for="email" class="auth-label">メールアドレス</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="auth-input">
                    @error('email')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- パスワード -->
                <div class="auth-form-group">
                    <label for="password" class="auth-label">パスワード</label>
                    <input type="password" id="password" name="password" class="auth-input">
                    @error('password')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ログインボタン -->
                <button type="submit" class="auth-button">管理者ログインする</button>
            </form>
        </div>
    </div>
</div>
@endsection

