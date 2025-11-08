<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'coachtech 勤怠管理アプリ')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #F0EFF2;
            color: #000000;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #000000;
            color: #FFFFFF;
            height: 80px;
            display: flex;
            align-items: center;
            padding: 0 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1000;
        }

        .header-content {
            max-width: 1512px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            position: relative;
            height: 100%;
        }

        .header-logo {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
        }

        .header-logo img {
            width: 370px;
            height: 36px;
            display: block;
            max-width: 100%;
        }

        @media (max-width: 1540px) {
            .header-logo img {
                width: min(370px, 25vw);
            }
        }

        .header-nav {
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 0;
        }

        .header-nav a, .header-nav form button {
            color: #FFFFFF;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 24px;
            line-height: 1.21;
            text-align: center;
            transition: opacity 0.2s;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            white-space: nowrap;
        }

        .header-nav a:nth-child(1) {
            width: 48px;
            margin-right: 41px;
        }

        .header-nav a:nth-child(2) {
            width: 96px;
            margin-right: 41px;
        }

        .header-nav a:nth-child(3) {
            width: 48px;
            margin-right: 53px;
        }

        .header-nav form button {
            width: 120px;
        }

        .header-nav a:hover, .header-nav form button:hover {
            opacity: 0.8;
        }

        .container {
            max-width: 1512px;
            width: 100%;
            margin: 0 auto;
            padding: 0;
            position: relative;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }

        .alert-success {
            background-color: #ECFDF5;
            color: #065F46;
            border: 1px solid #10B981;
        }

        .alert-error {
            background-color: #FEF2F2;
            color: #991B1B;
            border: 1px solid #EF4444;
        }

        .card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            background-color: #000000;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 16px;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .btn-secondary {
            background-color: #C8C8C8;
            color: #696969;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: #000000;
            font-size: 14px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            background: #FFFFFF;
            color: #000000;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #000000;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .table th {
            padding: 16px;
            text-align: left;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: #000000;
            border-bottom: 2px solid #E5E7EB;
        }

        .table td {
            padding: 16px;
            border-bottom: 1px solid #E5E7EB;
            font-family: 'Inter', sans-serif;
            color: #000000;
        }

        .table tbody tr:hover {
            background-color: #F9FAFB;
        }
    </style>
    @yield('styles')
</head>
<body>
    @auth
    <div class="header">
        <div class="header-content">
            <div class="header-logo">
                <img src="{{ asset('images/coachtech-logo.svg') }}" alt="CoachTech" style="width: 370px; height: 36px; display: block;">
            </div>
            <nav class="header-nav">
                @if(Auth::user()->is_admin)
                    <a href="{{ route('admin.attendance.list') }}">勤怠一覧</a>
                    <a href="{{ route('admin.staff.list') }}">スタッフ一覧</a>
                    <a href="{{ route('stamp_correction_request.list') }}">申請</a>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                @else
                    <a href="{{ route('attendance.index') }}">勤怠</a>
                    <a href="{{ route('attendance.list') }}">勤怠一覧</a>
                    <a href="{{ route('stamp_correction_request.list') }}">申請</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                @endif
            </nav>
        </div>
    </div>
    @endauth

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
