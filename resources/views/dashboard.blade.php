@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
<div style="position: relative; width: 100%; min-height: calc(100vh - 80px); background-color: #F0EFF2; margin: -24px; padding: 0;">
    <!-- ステータスバッジ（右上） -->
    <div style="position: absolute; top: 289px; right: calc(50% - 700px); width: 100px; height: 40px; background-color: #C8C8C8; border-radius: 50px; display: flex; align-items: center; justify-content: center;">
        <span style="color: #696969; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 18px; letter-spacing: 0.15em;">勤務外</span>
    </div>

    <!-- コンテンツエリア -->
    <div style="position: relative; width: 1512px; max-width: 100%; margin: 0 auto; min-height: calc(100vh - 80px); padding-top: 80px;">
        <!-- 日付 -->
        <div style="position: absolute; left: 590px; top: 361px; width: 332px; height: 48px;">
            <div style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 400; font-size: 40px; line-height: 1.21;">
                {{ now()->format('Y年m月d日') }}({{ ['日', '月', '火', '水', '木', '金', '土'][now()->format('w')] }})
            </div>
        </div>

        <!-- 時刻表示 -->
        <div style="position: absolute; left: 635px; top: 441px; width: 242px; height: 97px;">
            <div id="current-time" style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 80px; line-height: 1.21;">
                {{ now()->format('H:i') }}
            </div>
        </div>

        <!-- 出勤ボタン -->
        @if(!$todayRecord || !$todayRecord->clock_in)
            <form method="POST" action="{{ route('attendance.clock-in') }}" style="position: absolute; left: 646px; top: 625px; width: 221px; height: 77px;">
                @csrf
                <button type="submit" style="width: 100%; height: 100%; background-color: #000000; border: none; border-radius: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #FFFFFF; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 32px; letter-spacing: 0.15em;">出勤</span>
                </button>
            </form>
        @elseif($todayRecord->clock_in && !$todayRecord->clock_out)
            <form method="POST" action="{{ route('attendance.clock-out') }}" style="position: absolute; left: 646px; top: 625px; width: 221px; height: 77px;">
                @csrf
                <button type="submit" style="width: 100%; height: 100%; background-color: #000000; border: none; border-radius: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #FFFFFF; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 32px; letter-spacing: 0.15em;">退勤</span>
                </button>
            </form>
        @endif

        <!-- 出勤時刻表示 -->
        @if($todayRecord && $todayRecord->clock_in)
            <div style="position: absolute; left: 590px; top: 750px; padding: 16px; background-color: rgba(0, 0, 0, 0.05); border-radius: 12px;">
                <div style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 400; font-size: 24px; margin-bottom: 8px;">出勤時刻</div>
                <div style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 32px;">{{ $todayRecord->clock_in }}</div>
            </div>
        @endif

        <!-- 退勤時刻表示 -->
        @if($todayRecord && $todayRecord->clock_out)
            <div style="position: absolute; left: 590px; top: 850px; padding: 16px; background-color: rgba(0, 0, 0, 0.05); border-radius: 12px;">
                <div style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 400; font-size: 24px; margin-bottom: 8px;">退勤時刻</div>
                <div style="color: #000000; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 32px;">{{ $todayRecord->clock_out }}</div>
            </div>
        @endif
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
