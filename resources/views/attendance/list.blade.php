@extends('layouts.app')

@section('title', '勤怠一覧')

@section('styles')
<style>
    .attendance-list-container {
        width: 100%;
        min-height: calc(100vh - 80px);
        background: #F0EFF2;
        padding: 40px 24px;
    }

    .attendance-list-wrapper {
        max-width: 1512px;
        margin: 0 auto;
        width: 100%;
    }

    .attendance-list-header {
        display: flex;
        align-items: center;
        gap: 21px;
        margin-bottom: 60px;
        padding-left: 20px;
    }

    .attendance-list-vertical-line {
        width: 8px;
        height: 40px;
        background: #000000;
        flex-shrink: 0;
    }

    .attendance-list-title {
        flex-shrink: 0;
    }

    .attendance-list-title h2 {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 30px;
        line-height: 36px;
        color: #000000;
    }

    .attendance-list-month-section {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 60px;
        position: relative;
    }

    .attendance-list-month-selector {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        position: relative;
        background: #FFFFFF;
        border-radius: 10px;
        padding: 12px 24px;
        width: 100%;
        max-width: 900px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .attendance-list-month-prev,
    .attendance-list-month-next {
        padding: 0;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #737373;
        transition: opacity 0.2s;
        flex-shrink: 0;
    }

    .attendance-list-month-next {
        gap: 4px;
    }

    .attendance-list-month-prev:hover,
    .attendance-list-month-next:hover {
        opacity: 0.7;
    }

    .attendance-list-month-prev span:first-child,
    .attendance-list-month-next span:last-child {
        font-size: 20px;
        line-height: 1;
        flex-shrink: 0;
    }

    .attendance-list-month-text {
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 20px;
        line-height: 24px;
        color: #000000;
        min-width: 87px;
        text-align: center;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .attendance-list-month-text::before {
        content: '';
        display: inline-block;
        width: 20px;
        height: 20px;
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M15 3H5C3.89543 3 3 3.89543 3 5V15C3 16.1046 3.89543 17 5 17H15C16.1046 17 17 16.1046 17 15V5C17 3.89543 16.1046 3 15 3Z' stroke='%23737373' stroke-width='2'/%3E%3Cpath d='M3 8H17' stroke='%23737373' stroke-width='2'/%3E%3Cpath d='M7 3V8' stroke='%23737373' stroke-width='2'/%3E%3Cpath d='M13 3V8' stroke='%23737373' stroke-width='2'/%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        flex-shrink: 0;
    }

    .attendance-list-table-container {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background: #FFFFFF;
        border-radius: 10px;
        padding: 15px 0 15px 0;
        overflow-x: auto;
        border: 1px solid #E1E1E1;
    }

    .attendance-list-table {
        width: 100%;
        min-width: 900px;
    }

    .attendance-list-table-header {
        position: relative;
        width: 100%;
        height: 31px;
        margin-bottom: 0;
    }

    .attendance-list-table-header-line {
        position: absolute;
        width: 100%;
        height: 0px;
        left: 0;
        bottom: 0;
        border-top: 3px solid #E1E1E1;
    }

    .attendance-list-table-header-item {
        position: absolute;
        height: 19px;
        top: 6px;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #737373;
        white-space: nowrap;
    }

    .attendance-list-table-header-item.date-col {
        text-align: left;
    }

    .attendance-list-table-header-item.time-col {
        text-align: center;
    }

    .attendance-list-table-header-item.detail-col {
        text-align: right;
    }

    .attendance-list-table-row {
        position: relative;
        width: 100%;
        height: 33px;
        margin-top: 0;
    }

    .attendance-list-table-row:first-of-type {
        margin-top: 29px;
    }

    .attendance-list-table-cell {
        position: absolute;
        height: 19px;
        top: 7px;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #737373;
        white-space: nowrap;
    }

    .attendance-list-table-row:not(:last-child)::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 0px;
        left: 0;
        bottom: 0;
        border-top: 2px solid #E1E1E1;
    }

    .attendance-list-table-cell.date-col {
        text-align: left;
    }

    .attendance-list-table-cell.time-col {
        text-align: center;
    }

    .attendance-list-table-cell.detail-col {
        text-align: right;
    }


    .attendance-list-detail-link {
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #000000;
        text-decoration: none;
        display: inline-block;
        white-space: nowrap;
    }

    .attendance-list-detail-link:hover {
        text-decoration: underline;
    }

    .attendance-list-empty {
        padding: 100px 24px;
        text-align: center;
        color: #696969;
    }

    .attendance-list-empty p {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        margin: 0;
    }

    /* レスポンシブ対応 */
    @media (max-width: 1024px) {
        .attendance-list-container {
            padding: 24px 16px;
        }

        .attendance-list-header {
            padding-left: 0;
            margin-bottom: 40px;
        }

        .attendance-list-title h2 {
            font-size: 24px;
            line-height: 28px;
        }

        .attendance-list-vertical-line {
            height: 32px;
        }

        .attendance-list-month-section {
            margin-bottom: 40px;
        }

        .attendance-list-month-selector {
            padding: 10px 20px;
        }

        .attendance-list-month-text {
            font-size: 18px;
        }

        .attendance-list-month-prev,
        .attendance-list-month-next {
            font-size: 14px;
        }

        .attendance-list-table-container {
            border-radius: 8px;
        }
    }

    @media (max-width: 768px) {
        .attendance-list-container {
            padding: 20px 12px;
        }

        .attendance-list-header {
            margin-bottom: 32px;
        }

        .attendance-list-title h2 {
            font-size: 20px;
            line-height: 24px;
        }

        .attendance-list-vertical-line {
            width: 6px;
            height: 28px;
        }

        .attendance-list-month-section {
            margin-bottom: 32px;
        }

        .attendance-list-month-selector {
            padding: 8px 16px;
            gap: 12px;
        }

        .attendance-list-month-text {
            font-size: 16px;
            min-width: 70px;
        }

        .attendance-list-month-text::before {
            width: 16px;
            height: 16px;
        }

        .attendance-list-month-prev,
        .attendance-list-month-next {
            font-size: 12px;
            gap: 4px;
        }

        .attendance-list-month-prev span:first-child,
        .attendance-list-month-next span:last-child {
            font-size: 16px;
        }

        .attendance-list-table-header-item,
        .attendance-list-table-cell {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .attendance-list-header {
            gap: 12px;
        }

        .attendance-list-title h2 {
            font-size: 18px;
            line-height: 22px;
        }

        .attendance-list-vertical-line {
            width: 4px;
            height: 24px;
        }

        .attendance-list-month-selector {
            padding: 6px 12px;
            gap: 8px;
        }

        .attendance-list-month-text {
            font-size: 14px;
            min-width: 60px;
        }

        .attendance-list-month-text::before {
            width: 14px;
            height: 14px;
        }

        .attendance-list-month-prev span,
        .attendance-list-month-next span {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="attendance-list-container">
    <div class="attendance-list-wrapper">
        <!-- タイトルセクション -->
        <div class="attendance-list-header">
            <div class="attendance-list-vertical-line"></div>
            <div class="attendance-list-title">
                <h2>勤怠一覧</h2>
            </div>
        </div>

        <!-- 月選択セクション -->
        <div class="attendance-list-month-section">
            <form method="GET" action="{{ route('attendance.list') }}" class="attendance-list-month-selector">
                <!-- 前月ボタン（矢印+テキスト） -->
                <button type="submit" name="month" value="{{ $prevMonth }}" class="attendance-list-month-prev">
                    <span>←</span>
                    <span>前月</span>
                </button>

                <!-- 月表示 -->
                <span class="attendance-list-month-text">{{ Carbon\Carbon::parse($month)->format('Y/m') }}</span>

                <!-- 翌月ボタン（テキスト+矢印） -->
                <button type="submit" name="month" value="{{ $nextMonth }}" class="attendance-list-month-next">
                    <span>翌月</span>
                    <span>→</span>
                </button>
            </form>
        </div>

        <!-- テーブルコンテナ -->
        <div class="attendance-list-table-container">
            @if($records->count() > 0)
                <div class="attendance-list-table">
                    <!-- テーブルヘッダー -->
                    <div class="attendance-list-table-header">
                        <div class="attendance-list-table-header-line"></div>
                        <div class="attendance-list-table-header-item date-col" style="left: 57px; width: 35px;">日付</div>
                        <div class="attendance-list-table-header-item time-col" style="left: 246px; width: 35px;">出勤</div>
                        <div class="attendance-list-table-header-item time-col" style="left: 379px; width: 35px;">退勤</div>
                        <div class="attendance-list-table-header-item time-col" style="left: 512px; width: 35px;">休憩</div>
                        <div class="attendance-list-table-header-item time-col" style="left: 645px; width: 35px;">合計</div>
                        <div class="attendance-list-table-header-item detail-col" style="left: 778px; width: 35px;">詳細</div>
                    </div>

                    <!-- テーブルデータ -->
                    @foreach($records as $record)
                        <div class="attendance-list-table-row">
                            <!-- 日付 -->
                            <div class="attendance-list-table-cell date-col" style="left: 55px; width: 93px;">
                                {{ $record->date->format('m/d') }}({{ ['日', '月', '火', '水', '木', '金', '土'][$record->date->format('w')] }})
                            </div>

                            <!-- 出勤時刻 -->
                            <div class="attendance-list-table-cell time-col" style="left: 238px; width: 58px;">
                                @if($record->clock_in)
                                    @php
                                        $clockInParts = explode(':', $record->clock_in);
                                        $clockInHours = str_pad((int)$clockInParts[0], 2, '0', STR_PAD_LEFT);
                                        $clockInMinutes = str_pad((int)$clockInParts[1], 2, '0', STR_PAD_LEFT);
                                        echo $clockInHours . ':' . $clockInMinutes;
                                    @endphp
                                @else
                                    -
                                @endif
                            </div>

                            <!-- 退勤時刻 -->
                            <div class="attendance-list-table-cell time-col" style="left: 368px; width: 55px;">
                                @if($record->clock_out)
                                    @php
                                        $clockOutParts = explode(':', $record->clock_out);
                                        $clockOutHours = str_pad((int)$clockOutParts[0], 2, '0', STR_PAD_LEFT);
                                        $clockOutMinutes = str_pad((int)$clockOutParts[1], 2, '0', STR_PAD_LEFT);
                                        echo $clockOutHours . ':' . $clockOutMinutes;
                                    @endphp
                                @else
                                    -
                                @endif
                            </div>

                            <!-- 休憩時間 -->
                            <div class="attendance-list-table-cell time-col" style="left: 509px; width: 42px;">
                                @if($record->break_time)
                                    @php
                                        $breakParts = explode(':', $record->break_time);
                                        $breakHours = (int)$breakParts[0];
                                        $breakMinutes = (int)$breakParts[1];
                                        echo $breakHours . ':' . str_pad($breakMinutes, 2, '0', STR_PAD_LEFT);
                                    @endphp
                                @else
                                    0:00
                                @endif
                            </div>

                            <!-- 勤務時間（合計） -->
                            <div class="attendance-list-table-cell time-col" style="left: 642px; width: 45px;">
                                @if($record->work_time)
                                    @php
                                        $workParts = explode(':', $record->work_time);
                                        $workHours = (int)$workParts[0];
                                        $workMinutes = (int)$workParts[1];
                                        echo $workHours . ':' . str_pad($workMinutes, 2, '0', STR_PAD_LEFT);
                                    @endphp
                                @else
                                    -
                                @endif
                            </div>

                            <!-- 詳細ボタン -->
                            <div class="attendance-list-table-cell detail-col" style="left: 778px; width: 35px;">
                                <a href="{{ route('attendance.detail', $record->id) }}" class="attendance-list-detail-link">詳細</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="attendance-list-empty">
                    <p>選択した月の勤怠記録がありません。</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
