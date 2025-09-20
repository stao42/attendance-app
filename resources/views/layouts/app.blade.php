<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CoachTech')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #FFFFFF;
            color: #000000;
        }

        .header {
            background-color: #000000;
            height: 80px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-content {
            width: 100%;
            max-width: 1512px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
        }

        .logo-section {
            flex: 0 0 auto;
        }

        .logo {
            height: 36px;
            width: auto;
        }

        .nav-section {
            display: flex;
            align-items: center;
            gap: 37px;
            flex: 0 0 auto;
        }

        .nav-link {
            color: #FFFFFF;
            text-decoration: none;
            font-size: 24px;
            font-weight: 400;
            transition: opacity 0.3s ease;
            background: none;
            border: none;
            cursor: pointer;
        }

        .nav-link:hover {
            opacity: 0.7;
        }

        .sell-button {
            background-color: #000000;
            color: #FFFFFF;
            border: none;
            border-radius: 4px;
            padding: 18px 0;
            width: 100px;
            font-size: 24px;
            font-weight: 400;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sell-button:hover {
            background-color: #333333;
        }

        .search-section {
            flex: 1;
            max-width: 563px;
            margin-left: 40px;
        }

        .search-form {
            width: 100%;
        }

        .search-input {
            width: 100%;
            height: 50px;
            background-color: #FFFFFF;
            border-radius: 5px;
            border: none;
            padding: 0 31px;
            font-size: 24px;
            color: #000000;
        }

        .search-input::placeholder {
            color: #000000;
        }

        .main-content {
            min-height: calc(100vh - 80px);
            padding: 20px 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 290px);
            gap: 20px;
            margin-top: 20px;
            justify-content: center;
        }

        .product-card {
            background-color: #FFFFFF;
            border-radius: 4px;
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease;
            width: 290px;
            height: 320px;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        .product-image {
            width: 100%;
            height: 281px;
            background-color: #D9D9D9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000000;
            font-size: 40px;
            font-weight: 400;
            border-radius: 4px 4px 0 0;
        }

        .product-info {
            padding: 20px;
            height: 39px;
            display: flex;
            align-items: center;
        }

        .product-name {
            font-size: 25px;
            font-weight: 400;
            color: #000000;
            margin: 0;
        }

        .product-price {
            font-size: 20px;
            font-weight: 400;
            color: #000000;
            margin: 0;
        }

        .category-tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #5F5F5F;
            padding-bottom: 10px;
        }

        .category-tab {
            font-size: 24px;
            font-weight: 700;
            color: #5F5F5F;
            text-decoration: none;
            padding: 10px 0;
            border-bottom: 2px solid transparent;
            transition: color 0.3s ease;
        }

        .category-tab.active {
            color: #FF0000;
            border-bottom-color: #FF0000;
        }

        .category-tab:hover {
            color: #FF0000;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #FF5555;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background-color: #E04444;
        }

        .btn-secondary {
            background-color: #0073CC;
            color: #FFFFFF;
        }

        .btn-secondary:hover {
            background-color: #005999;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 10px;
        }

        .form-input {
            width: 100%;
            height: 45px;
            border: 1px solid #5F5F5F;
            border-radius: 4px;
            padding: 0 16px;
            font-size: 18px;
            color: #000000;
            background-color: #FFFFFF;
        }

        .form-input:focus {
            outline: none;
            border-color: #FF5555;
        }

        .form-textarea {
            width: 100%;
            min-height: 125px;
            border: 1px solid #5F5F5F;
            border-radius: 4px;
            padding: 16px;
            font-size: 18px;
            color: #000000;
            background-color: #FFFFFF;
            resize: vertical;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #FF5555;
        }

        .form-select {
            width: 100%;
            height: 45px;
            border: 1px solid #5F5F5F;
            border-radius: 4px;
            padding: 0 16px;
            font-size: 18px;
            color: #000000;
            background-color: #FFFFFF;
        }

        .form-select:focus {
            outline: none;
            border-color: #FF5555;
        }

        .error-message {
            color: #FF5555;
            font-size: 16px;
            margin-top: 5px;
        }

        .success-message {
            color: #0073CC;
            font-size: 16px;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #E5F2FF;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <!-- ロゴエリア -->
            <div class="logo-section">
                <img src="{{ asset('images/coachtech-logo.svg') }}" alt="CoachTech" class="logo">
            </div>
            
            <!-- ナビゲーションエリア -->
            <div class="nav-section">
                @auth
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link">ログアウト</button>
                    </form>
                    <a href="{{ route('profile.show') }}" class="nav-link">マイページ</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                    <a href="{{ route('register') }}" class="nav-link">会員登録</a>
                @endauth
                
                <a href="{{ route('products.create') }}" class="sell-button">出品</a>
            </div>

            <!-- 検索エリア -->
            <div class="search-section">
                <form action="{{ route('products.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="なにをお探しですか？" class="search-input" value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
