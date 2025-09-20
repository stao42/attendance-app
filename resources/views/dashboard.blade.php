<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード - CoachTech</title>
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
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .logo {
            height: 36px;
            width: auto;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-name {
            color: #FFFFFF;
            font-size: 18px;
        }

        .logout-button {
            background-color: rgba(240, 240, 240, 0.6);
            border: 1px solid #D9C6B5;
            border-radius: 4px;
            color: #D9C6B5;
            padding: 8px 16px;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .logout-button:hover {
            background-color: rgba(240, 240, 240, 0.8);
            color: #FFFFFF;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-container {
            text-align: center;
        }

        .welcome-title {
            font-size: 48px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 20px;
        }

        .welcome-message {
            font-size: 24px;
            color: #666666;
            margin-bottom: 40px;
        }

        .dashboard-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .action-button {
            background-color: #FF5555;
            border: none;
            border-radius: 5px;
            color: #FFFFFF;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 700;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .action-button:hover {
            background-color: #E04444;
        }
    </style>
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/coachtech-logo.svg') }}" alt="CoachTech" class="logo">
        <div class="user-info">
            <span class="user-name">{{ Auth::user()->name }}さん</span>
            <a href="{{ route('logout') }}" class="logout-button">logout</a>
        </div>
    </header>

    <main class="main-content">
        <div class="dashboard-container">
            <h1 class="welcome-title">ダッシュボード</h1>
            <p class="welcome-message">CoachTechへようこそ！</p>
            <div class="dashboard-actions">
                <a href="#" class="action-button">プロフィール編集</a>
                <a href="#" class="action-button">設定</a>
            </div>
        </div>
    </main>
</body>
</html>
