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
            margin-bottom: 0;
        }

        .header-content {
            width: 100%;
            max-width: 1512px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: relative;
            z-index: 100;
        }

        .header-left {
            flex: 0 0 auto;
        }

        .header-center {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .header-right {
            flex: 0 0 auto;
        }

        /* レスポンシブデザイン */
        @media (max-width: 768px) {
            .header-content {
                padding: 0 20px;
                flex-wrap: wrap;
            }
            
            .header-center {
                order: 3;
                width: 100%;
                margin-top: 10px;
            }
            
            .nav-section {
                gap: 20px;
            }
            
            .nav-link {
                font-size: 18px;
            }
            
            .sell-button {
                width: 80px;
                font-size: 18px;
                padding: 15px 0;
            }
            
            .search-input {
                font-size: 18px;
                height: 40px;
            }
        }

        @media (max-width: 480px) {
            .header {
                height: auto;
                padding: 10px 0;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .header-center {
                order: 0;
                width: 100%;
                margin-top: 0;
            }
            
            .nav-section {
                gap: 15px;
            }
            
            .nav-link {
                font-size: 16px;
            }
            
            .sell-button {
                width: 70px;
                font-size: 16px;
                padding: 12px 0;
            }
            
            .search-input {
                font-size: 16px;
                height: 35px;
            }
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
            background-color: #FFFFFF;
            color: #000000;
            border: none;
            border-radius: 4px;
            padding: 18px 0;
            width: 100px;
            font-size: 24px;
            font-weight: 400;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .sell-button:hover {
            background-color: #E0E0E0;
        }

        .search-section {
            flex: 1;
            max-width: 563px;
            display: flex;
            align-items: center;
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
            padding: 0;
        }

        .container {
            max-width: 1512px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 290px);
            gap: 20px;
            margin-top: 47px;
            justify-content: flex-start;
        }

        /* 商品グリッドのレスポンシブ対応 */
        @media (max-width: 1540px) {
            .container {
                max-width: 1400px;
                padding: 0 20px;
            }
        }

        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(2, 290px);
                justify-content: center;
            }
        }

        @media (max-width: 850px) {
            .container {
                padding: 0 20px;
            }
            
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                margin-top: 30px;
            }
            
            .product-card {
                width: 100%;
                min-height: 280px;
            }
            
            .product-image {
                height: 240px;
                font-size: 30px;
            }
            
            .product-name {
                font-size: 20px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                margin-top: 25px;
            }
            
            .product-card {
                width: 100%;
                min-height: 260px;
            }
            
            .product-image {
                height: 220px;
                font-size: 28px;
            }
            
            .product-name {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
                gap: 15px;
                margin-top: 20px;
            }
            
            .product-card {
                min-height: 250px;
            }
            
            .product-image {
                height: 210px;
                font-size: 24px;
            }
            
            .product-name {
                font-size: 18px;
            }
        }

        .product-card {
            background-color: #FFFFFF;
            border-radius: 4px;
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease;
            width: 290px;
            min-height: 320px;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        .product-image {
            width: 100%;
            height: 260px;
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
            padding: 10px 20px;
            min-height: 39px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
        }

        .product-name {
            font-size: 20px;
            font-weight: 400;
            color: #000000;
            margin: 0 0 5px 0;
            line-height: 1.2;
        }

        .product-price {
            font-size: 16px;
            font-weight: 400;
            color: #000000;
            margin: 0 0 3px 0;
            line-height: 1.2;
        }

        .category-tabs {
            display: flex;
            gap: 52px;
            margin: 47px 0 0 0;
            padding-bottom: 5px;
            justify-content: flex-start;
            position: relative;
        }

        .category-tabs::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100vw;
            height: 2px;
            background-color: #5F5F5F;
            z-index: 0;
            transform: translateX(-50%);
            margin-left: 50%;
        }


        .category-tab {
            font-size: 24px;
            font-weight: 700;
            color: #5F5F5F;
            text-decoration: none;
            padding: 0;
            border-bottom: none;
            transition: color 0.3s ease;
            width: 155px;
            text-align: left;
        }

        /* カテゴリータブのレスポンシブ対応 */
        @media (max-width: 768px) {
            .category-tabs {
                gap: 30px;
                margin: 30px 0 0 0;
                justify-content: flex-start;
            }
            
            .category-tab {
                font-size: 20px;
                width: 120px;
                text-align: left;
            }
        }

        @media (max-width: 480px) {
            .category-tabs {
                gap: 20px;
                margin: 20px 0 0 0;
                flex-direction: column;
                align-items: flex-start;
            }
            
            .category-tab {
                font-size: 18px;
                width: 100px;
                text-align: left;
            }
        }

        .category-tab.active {
            color: #FF0000;
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

        /* ボタンのレスポンシブ対応 */
        @media (max-width: 768px) {
            .btn {
                padding: 10px 20px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .btn {
                padding: 8px 16px;
                font-size: 14px;
                width: 100%;
            }
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

        /* フォームのレスポンシブ対応 */
        @media (max-width: 768px) {
            .form-label {
                font-size: 20px;
            }
            
            .form-input {
                height: 40px;
                font-size: 16px;
                padding: 0 12px;
            }
            
            .form-textarea {
                font-size: 16px;
                padding: 12px;
                min-height: 100px;
            }
            
            .form-select {
                height: 40px;
                font-size: 16px;
                padding: 0 12px;
            }
        }

        @media (max-width: 480px) {
            .form-label {
                font-size: 18px;
            }
            
            .form-input {
                height: 35px;
                font-size: 14px;
                padding: 0 10px;
            }
            
            .form-textarea {
                font-size: 14px;
                padding: 10px;
                min-height: 80px;
            }
            
            .form-select {
                height: 35px;
                font-size: 14px;
                padding: 0 10px;
            }
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
            <!-- 左側エリア（ロゴ） -->
            <div class="header-left">
                <div class="logo-section">
                    <img src="{{ asset('images/coachtech-logo.svg') }}" alt="CoachTech" class="logo">
                </div>
            </div>
            
            <!-- 中央エリア（検索） -->
            <div class="header-center">
                <div class="search-section">
                    <form action="{{ route('products.index') }}" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="なにをお探しですか？" class="search-input" value="{{ request('search') }}">
                        @if(request('tab'))
                            <input type="hidden" name="tab" value="{{ request('tab') }}">
                        @endif
                    </form>
                </div>
            </div>

            <!-- 右側エリア（ナビゲーション） -->
            <div class="header-right">
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
