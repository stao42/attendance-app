@extends('layouts.app')

@section('title', 'スタッフ別勤怠一覧（管理者）')

@section('styles')
<style>
    .admin-staff-attendance-list-container {
        width: 100%;
        min-height: calc(100vh - 80px);
        background: #F0EFF2;
        padding: 40px 24px;
    }

    .admin-staff-attendance-list-wrapper {
        max-width: 1512px;
        margin: 0 auto;
        width: 100%;
    }

    .admin-staff-attendance-list-header {
        display: flex;
        align-items: center;
        gap: 21px;
        margin-bottom: 60px;
        padding-left: 20px;
    }

    .admin-staff-attendance-list-vertical-line {
        width: 8px;
        height: 40px;
        background: #000000;
        flex-shrink: 0;
    }

    .admin-staff-attendance-list-title h1 {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 30px;
        line-height: 36px;
        color: #000000;
    }

    .admin-staff-attendance-list-month-section {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 60px;
        position: relative;
    }

    .admin-staff-attendance-list-month-selector {
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
        height: 60px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .admin-staff-attendance-list-month-prev,
    .admin-staff-attendance-list-month-next {
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

    .admin-staff-attendance-list-month-next {
        gap: 4px;
    }

    .admin-staff-attendance-list-month-prev:hover,
    .admin-staff-attendance-list-month-next:hover {
        opacity: 0.7;
    }

    .admin-staff-attendance-list-month-prev span:first-child,
    .admin-staff-attendance-list-month-next span:last-child {
        font-size: 20px;
        line-height: 1;
        flex-shrink: 0;
    }

    .admin-staff-attendance-list-month-text {
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

    .admin-staff-attendance-list-month-text::before {
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

    .admin-staff-attendance-list-table-container {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background: #FFFFFF;
        border-radius: 10px;
        padding: 15px 0 15px 0;
        overflow-x: auto;
        border: 1px solid #E1E1E1;
    }

    .admin-staff-attendance-list-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .admin-staff-attendance-list-table thead {
        border-bottom: 3px solid #E1E1E1;
    }

    .admin-staff-attendance-list-table-header-row {
        height: 31px;
    }

    .admin-staff-attendance-list-table-header-item {
        height: 19px;
        padding: 6px 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #737373;
        white-space: nowrap;
    }

    .admin-staff-attendance-list-table-header-item.date-col {
        text-align: left;
        padding-left: 55px;
        width: 93px;
    }

    .admin-staff-attendance-list-table-header-item.time-col {
        text-align: center;
        width: 58px;
    }

    .admin-staff-attendance-list-table-header-item.time-col:nth-of-type(3) {
        width: 55px;
    }

    .admin-staff-attendance-list-table-header-item.time-col:nth-of-type(4) {
        width: 42px;
    }

    .admin-staff-attendance-list-table-header-item.time-col:nth-of-type(5) {
        width: 45px;
    }

    .admin-staff-attendance-list-table-header-item.detail-col {
        text-align: right;
        padding-right: 36px;
        width: 35px;
    }

    .admin-staff-attendance-list-table-row {
        height: 33px;
        border-bottom: 2px solid #E1E1E1;
    }

    .admin-staff-attendance-list-table-row:last-child {
        border-bottom: none;
    }

    .admin-staff-attendance-list-table-cell {
        height: 19px;
        padding: 7px 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #737373;
        white-space: nowrap;
    }

    .admin-staff-attendance-list-table-cell.date-col {
        text-align: left;
        padding-left: 55px;
        width: 93px;
    }

    .admin-staff-attendance-list-table-cell.time-col {
        text-align: center;
        width: 58px;
    }

    .admin-staff-attendance-list-table-cell.time-col:nth-of-type(3) {
        width: 55px;
    }

    .admin-staff-attendance-list-table-cell.time-col:nth-of-type(4) {
        width: 42px;
    }

    .admin-staff-attendance-list-table-cell.time-col:nth-of-type(5) {
        width: 45px;
    }

    .admin-staff-attendance-list-table-cell.detail-col {
        text-align: right;
        padding-right: 36px;
        width: 35px;
    }

    .admin-staff-attendance-list-detail-link {
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

    .admin-staff-attendance-list-detail-link:hover {
        text-decoration: underline;
    }

    .admin-staff-attendance-list-empty {
        padding: 100px 24px;
        text-align: center;
        color: #696969;
    }

    .admin-staff-attendance-list-empty p {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        margin: 0;
    }

    .admin-staff-attendance-list-csv-section {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 20px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .admin-staff-attendance-list-csv-button {
        width: 150px;
        height: 50px;
        border-radius: 5px;
        border: none;
        background: #000000;
        color: #FFFFFF;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        cursor: pointer;
        transition: opacity 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .admin-staff-attendance-list-csv-button:hover {
        opacity: 0.8;
    }

    /* レスポンシブ対応 */
    @media (max-width: 1024px) {
        .admin-staff-attendance-list-container {
            padding: 24px 16px;
        }

        .admin-staff-attendance-list-header {
            padding-left: 0;
            margin-bottom: 40px;
        }

        .admin-staff-attendance-list-title h1 {
            font-size: 24px;
            line-height: 28px;
        }

        .admin-staff-attendance-list-vertical-line {
            height: 32px;
        }

        .admin-staff-attendance-list-month-section {
            margin-bottom: 40px;
        }

        .admin-staff-attendance-list-month-selector {
            padding: 10px 20px;
        }

        .admin-staff-attendance-list-month-text {
            font-size: 18px;
        }

        .admin-staff-attendance-list-month-prev,
        .admin-staff-attendance-list-month-next {
            font-size: 14px;
        }

        .admin-staff-attendance-list-table-container {
            border-radius: 8px;
        }
    }

    @media (max-width: 768px) {
        .admin-staff-attendance-list-container {
            padding: 20px 12px;
        }

        .admin-staff-attendance-list-header {
            margin-bottom: 32px;
        }

        .admin-staff-attendance-list-title h1 {
            font-size: 20px;
            line-height: 24px;
        }

        .admin-staff-attendance-list-vertical-line {
            width: 6px;
            height: 28px;
        }

        .admin-staff-attendance-list-month-section {
            margin-bottom: 32px;
        }

        .admin-staff-attendance-list-month-selector {
            padding: 8px 16px;
            gap: 12px;
        }

        .admin-staff-attendance-list-month-text {
            font-size: 16px;
            min-width: 70px;
        }

        .admin-staff-attendance-list-month-text::before {
            width: 16px;
            height: 16px;
        }

        .admin-staff-attendance-list-month-prev,
        .admin-staff-attendance-list-month-next {
            font-size: 12px;
            gap: 4px;
        }

        .admin-staff-attendance-list-month-prev span:first-child,
        .admin-staff-attendance-list-month-next span:last-child {
            font-size: 16px;
        }

        .admin-staff-attendance-list-table-header-item,
        .admin-staff-attendance-list-table-cell {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .admin-staff-attendance-list-header {
            gap: 12px;
        }

        .admin-staff-attendance-list-title h1 {
            font-size: 18px;
            line-height: 22px;
        }

        .admin-staff-attendance-list-vertical-line {
            width: 4px;
            height: 24px;
        }

        .admin-staff-attendance-list-month-selector {
            padding: 6px 12px;
            gap: 8px;
        }

        .admin-staff-attendance-list-month-text {
            font-size: 14px;
            min-width: 60px;
        }

        .admin-staff-attendance-list-month-text::before {
            width: 14px;
            height: 14px;
        }

        .admin-staff-attendance-list-month-prev span,
        .admin-staff-attendance-list-month-next span {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-staff-attendance-list-container">
    <div class="admin-staff-attendance-list-wrapper">
        <!-- タイトルセクション -->
        <div class="admin-staff-attendance-list-header">
            <div class="admin-staff-attendance-list-vertical-line"></div>
            <div class="admin-staff-attendance-list-title">
                <h1>{{ $user->name }}さんの勤怠</h1>
            </div>
        </div>

        <!-- 月選択セクション -->
        <div class="admin-staff-attendance-list-month-section">
            <form method="GET" action="{{ route('admin.attendance.staff', $user->id) }}" class="admin-staff-attendance-list-month-selector">
                <button type="submit" name="month" value="{{ $prevMonth }}" class="admin-staff-attendance-list-month-prev">
                    <span>←</span>
                    <span>前月</span>
                </button>
                <span class="admin-staff-attendance-list-month-text">{{ Carbon\Carbon::parse($month)->format('Y/m') }}</span>
                <button type="submit" name="month" value="{{ $nextMonth }}" class="admin-staff-attendance-list-month-next">
                    <span>翌月</span>
                    <span>→</span>
                </button>
            </form>
        </div>

        <!-- テーブルセクション -->
        <div class="admin-staff-attendance-list-table-container">
            @if($records->count() > 0)
                <table class="admin-staff-attendance-list-table">
                    <thead>
                        <tr class="admin-staff-attendance-list-table-header-row">
                            <th class="admin-staff-attendance-list-table-header-item date-col">日付</th>
                            <th class="admin-staff-attendance-list-table-header-item time-col">出勤</th>
                            <th class="admin-staff-attendance-list-table-header-item time-col">退勤</th>
                            <th class="admin-staff-attendance-list-table-header-item time-col">休憩</th>
                            <th class="admin-staff-attendance-list-table-header-item time-col">合計</th>
                            <th class="admin-staff-attendance-list-table-header-item detail-col">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr class="admin-staff-attendance-list-table-row">
                                <td class="admin-staff-attendance-list-table-cell date-col">
                                    {{ $record->date->format('m/d') }}({{ ['日', '月', '火', '水', '木', '金', '土'][$record->date->format('w')] }})
                                </td>
                                <td class="admin-staff-attendance-list-table-cell time-col">
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
                                </td>
                                <td class="admin-staff-attendance-list-table-cell time-col">
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
                                </td>
                                <td class="admin-staff-attendance-list-table-cell time-col">
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
                                </td>
                                <td class="admin-staff-attendance-list-table-cell time-col">
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
                                </td>
                                <td class="admin-staff-attendance-list-table-cell detail-col">
                                    <a href="{{ route('admin.attendance.detail', $record->id) }}" class="admin-staff-attendance-list-detail-link">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="admin-staff-attendance-list-empty">
                    <p>選択した月の勤怠記録がありません。</p>
                </div>
            @endif
        </div>

        <!-- CSV出力ボタン -->
        <div class="admin-staff-attendance-list-csv-section">
            <a href="{{ route('admin.attendance.staff.csv', $user->id) }}?month={{ $month }}" class="admin-staff-attendance-list-csv-button">CSV出力</a>
        </div>
    </div>
</div>
@endsection
