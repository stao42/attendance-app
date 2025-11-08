@extends('layouts.app')

@section('title', '勤怠登録')

@section('styles')
<style>
    .attendance-page {
        min-height: calc(100vh - 80px);
        background-color: #F0EFF2;
        padding: 40px 25px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .attendance-container {
        max-width: 1512px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .status-badge {
        width: 100px;
        height: 40px;
        background-color: #C8C8C8;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
    }

    .status-badge span {
        color: #696969;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 18px;
        line-height: 1.2102272245619032em;
        letter-spacing: 0.15em;
        text-align: center;
    }

    .date-display {
        margin-bottom: 24px;
        text-align: center;
    }

    .date-text {
        color: #000000;
        font-family: 'Inter', sans-serif;
        font-weight: 400;
        font-size: 40px;
        line-height: 1.2102272033691406em;
        text-align: center;
    }

    .time-display {
        margin-bottom: 40px;
        text-align: center;
    }

    .time-text {
        color: #000000;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 80px;
        line-height: 1.2102272033691406em;
        text-align: center;
    }

    .button-group {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 82px;
        margin-bottom: 40px;
        width: 100%;
    }

    .attendance-button {
        width: 221px;
        height: 77px;
        background-color: #000000;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.2s;
    }

    .attendance-button.break-button {
        background-color: #FFFFFF;
        border: none;
    }

    .attendance-button:hover {
        opacity: 0.8;
    }

    .attendance-button span {
        color: #FFFFFF;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 32px;
        line-height: 1.2102272510528564em;
        letter-spacing: 0.15em;
        text-align: center;
    }

    .attendance-button.break-button span {
        color: #000000;
    }

    .thank-you-message {
        color: #000000;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 26px;
        line-height: 1.2102272327129657em;
        letter-spacing: 0.15em;
        text-align: center;
    }

    .time-info {
        width: 100%;
        max-width: 400px;
        padding: 16px 24px;
        background-color: #C8C8C8;
        border-radius: 12px;
        text-align: center;
        margin-bottom: 16px;
    }

    .time-info-label {
        color: #000000;
        font-family: 'Inter', sans-serif;
        font-weight: 400;
        font-size: 24px;
        margin-bottom: 8px;
    }

    .time-info-value {
        color: #000000;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 32px;
    }


    @media (max-width: 768px) {
        .attendance-page {
            padding: 80px 16px 40px;
        }

        .date-text {
            font-size: 28px;
        }

        .time-text {
            font-size: 56px;
        }

        .attendance-button {
            width: 100%;
            max-width: 221px;
            height: 60px;
        }

        .attendance-button span {
            font-size: 24px;
        }

        .status-badge {
            margin: 0 auto 20px;
        }
    }

    @media (min-width: 1400px) and (max-width: 1540px) {
        .attendance-container {
            max-width: 100%;
            padding: 0 25px;
        }
    }
</style>
@endsection

@section('content')
<div class="attendance-page">
    <div class="attendance-container">
        <!-- ステータスバッジ -->
        <div class="status-badge">
            <span>{{ $status }}</span>
        </div>

        <!-- 日付表示 -->
        <div class="date-display">
            <div class="date-text">
                {{ now()->format('Y年m月d日') }}({{ ['日', '月', '火', '水', '木', '金', '土'][now()->format('w')] }})
            </div>
        </div>

        <!-- 時刻表示 -->
        <div class="time-display">
            <div id="current-time" class="time-text">
                {{ now()->format('H:i') }}
            </div>
        </div>

        <!-- 打刻ボタン -->
        <div class="button-group">
            @if($status === '勤務外')
                <!-- 出勤ボタン -->
                <form method="POST" action="{{ route('attendance.clock-in') }}">
                    @csrf
                    <button type="submit" class="attendance-button">
                        <span>出勤</span>
                    </button>
                </form>
            @elseif($status === '出勤中')
                <!-- 退勤ボタンと休憩入ボタン（横並び） -->
                <form method="POST" action="{{ route('attendance.clock-out') }}">
                    @csrf
                    <button type="submit" class="attendance-button">
                        <span>退勤</span>
                    </button>
                </form>
                <form method="POST" action="{{ route('attendance.break-start') }}">
                    @csrf
                    <button type="submit" class="attendance-button break-button">
                        <span>休憩入</span>
                    </button>
                </form>
            @elseif($status === '休憩中')
                <!-- 休憩戻ボタン -->
                <form method="POST" action="{{ route('attendance.break-end') }}">
                    @csrf
                    <button type="submit" class="attendance-button break-button">
                        <span>休憩戻</span>
                    </button>
                </form>
            @elseif($status === '退勤済')
                <!-- 退勤済みの場合はメッセージを表示 -->
                <div class="thank-you-message">
                    お疲れ様でした。
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    // 現在時刻をリアルタイムで更新
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = `${hours}:${minutes}`;
        }
    }

    setInterval(updateTime, 1000);
</script>
@endsection
