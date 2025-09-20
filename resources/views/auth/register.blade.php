<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録 - CoachTech</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #FFFFFF;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: #000000;
            height: 80px;
            display: flex;
            align-items: center;
            padding: 0 40px;
        }

        .logo {
            height: 36px;
            width: auto;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: #F5F5F5;
        }

        .auth-container {
            width: 680px;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 60px 40px;
        }

        .auth-title {
            font-size: 36px;
            font-weight: 700;
            color: #000000;
            text-align: center;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        .form-label {
            font-size: 20px;
            font-weight: 600;
            color: #333333;
            display: block;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            height: 50px;
            border: 2px solid #E0E0E0;
            border-radius: 6px;
            padding: 0 16px;
            font-size: 16px;
            color: #333333;
            background-color: #FFFFFF;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #FF5555;
        }

        .form-input.error {
            border-color: #FF5555;
        }

        .auth-button {
            width: 100%;
            height: 56px;
            background-color: #FF5555;
            border: none;
            border-radius: 6px;
            color: #FFFFFF;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 24px;
            transition: background-color 0.3s ease;
        }

        .auth-button:hover {
            background-color: #E04444;
        }

        .auth-button:disabled {
            background-color: #CCCCCC;
            cursor: not-allowed;
        }

        .auth-link {
            text-align: center;
        }

        .auth-link a {
            color: #FF5555;
            font-size: 16px;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #FF5555;
            font-size: 14px;
            margin-bottom: 16px;
            text-align: center;
            background-color: #FFF5F5;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #FFE5E5;
        }

        .field-error {
            color: #FF5555;
            font-size: 14px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/coachtech-logo.svg') }}" alt="CoachTech" class="logo">
    </header>

    <main class="main-content">
        <div class="auth-container">
            <h1 class="auth-title">会員登録</h1>
            
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') error @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" required>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">パスワード確認</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input @error('password_confirmation') error @enderror" required>
                    @error('password_confirmation')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="auth-button">会員登録する</button>
            </form>

            <div class="auth-link">
                <a href="{{ route('login') }}">ログインはこちら</a>
            </div>
        </div>
    </main>
</body>
</html>
