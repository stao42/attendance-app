@extends('layouts.app')

@section('content')
<div class="container">
    <div class="email-verification">
        <div class="verification-card">
            <h1 class="verification-title">メール認証</h1>
            <div class="verification-content">
                <div class="verification-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 7L10 13L20 3" stroke="#FF5555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="verification-message">
                    {{ auth()->user()->email }} に認証メールを送信しました。
                </p>
                <p class="verification-sub-message">
                    メール内の「メールアドレスを認証」ボタンをクリックして認証を完了してください。
                </p>
            </div>

            <div class="verification-actions">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="resend-btn">
                        認証メールを再送信
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        ログアウト
                    </button>
                </form>
            </div>

            <div class="help-section">
                <p class="help-text">
                    メールが届かない場合は、以下の点をご確認ください：
                </p>
                <ul class="help-list">
                    <li>迷惑メールフォルダをご確認ください</li>
                    <li>メールアドレスが正しいかご確認ください</li>
                    <li>数分お待ちいただいた後、再度お試しください</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.email-verification {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.verification-card {
    background: #FFFFFF;
    border-radius: 8px;
    padding: 40px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
    text-align: center;
}

.verification-title {
    font-size: 32px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 30px;
}

.verification-icon {
    margin-bottom: 20px;
}

.verification-content {
    margin-bottom: 40px;
}

.verification-message {
    font-size: 18px;
    color: #333333;
    line-height: 1.6;
    margin-bottom: 15px;
    font-weight: 600;
}

.verification-sub-message {
    font-size: 16px;
    color: #666666;
    line-height: 1.4;
}

.verification-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 30px;
}

.resend-btn {
    background-color: #FF5555;
    color: #FFFFFF;
    border: none;
    border-radius: 4px;
    padding: 12px 24px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.resend-btn:hover {
    background-color: #E04444;
}

.logout-btn {
    background: none;
    border: 1px solid #CCCCCC;
    color: #666666;
    font-size: 14px;
    cursor: pointer;
    padding: 10px 20px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    border-color: #999999;
    color: #333333;
}

.help-section {
    border-top: 1px solid #E0E0E0;
    padding-top: 20px;
    text-align: left;
}

.help-text {
    font-size: 14px;
    color: #666666;
    margin-bottom: 10px;
    font-weight: 600;
}

.help-list {
    font-size: 13px;
    color: #666666;
    line-height: 1.5;
    margin: 0;
    padding-left: 20px;
}

.help-list li {
    margin-bottom: 5px;
}

/* レスポンシブデザイン */
@media (max-width: 768px) {
    .verification-card {
        padding: 30px 20px;
    }

    .verification-title {
        font-size: 28px;
        margin-bottom: 25px;
    }

    .verification-message {
        font-size: 16px;
    }

    .verification-sub-message {
        font-size: 14px;
    }

    .resend-btn {
        font-size: 15px;
        padding: 10px 20px;
    }

    .help-text {
        font-size: 13px;
    }

    .help-list {
        font-size: 12px;
    }
}
</style>
@endsection
