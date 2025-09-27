@extends('layouts.app')

@section('content')
<div class="container">
    <div class="verification-notice">
        <div class="verification-card">
            <h1 class="verification-title">メール認証が必要です</h1>
            <div class="verification-content">
                <p class="verification-message">
                    ご登録いただいたメールアドレスに認証メールを送信しました。<br>
                    メール内のリンクをクリックして、メールアドレスを認証してください。
                </p>
                <p class="verification-sub-message">
                    メールが届かない場合は、迷惑メールフォルダをご確認ください。
                </p>
            </div>

            <div class="verification-actions">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="resend-btn">
                        認証メールを再送信する
                    </button>
                </form>

                <div class="verify-info">
                    <p>メール内の「メールアドレスを認証」ボタンをクリックして認証を完了してください。</p>
                </div>
            </div>

            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        ログアウト
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.verification-notice {
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

.verification-content {
    margin-bottom: 40px;
}

.verification-message {
    font-size: 18px;
    color: #333333;
    line-height: 1.6;
    margin-bottom: 15px;
}

.verification-sub-message {
    font-size: 14px;
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

.verify-info {
    text-align: center;
    padding: 15px;
    background-color: #FFF8F8;
    border: 1px solid #FFE0E0;
    border-radius: 4px;
}

.verify-info p {
    color: #666666;
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
}

.logout-section {
    border-top: 1px solid #E0E0E0;
    padding-top: 20px;
}

.logout-btn {
    background: none;
    border: none;
    color: #666666;
    font-size: 14px;
    cursor: pointer;
    text-decoration: underline;
    transition: color 0.3s ease;
}

.logout-btn:hover {
    color: #333333;
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
        font-size: 13px;
    }

    .resend-btn, .verify-link {
        font-size: 15px;
        padding: 10px 20px;
    }
}
</style>
@endsection
