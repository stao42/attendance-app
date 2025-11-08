@extends('layouts.app')

@section('title', '勤怠一覧（管理者）')

@section('styles')
<style>
    .admin-attendance-list-container {
        width: 100%;
        min-height: calc(100vh - 80px);
        background: #F0EFF2;
        padding: 40px 24px;
    }

    .admin-attendance-list-wrapper {
        max-width: 1512px;
        margin: 0 auto;
        width: 100%;
    }

    .admin-attendance-list-header {
        display: flex;
        align-items: center;
        gap: 21px;
        margin-bottom: 60px;
        padding-left: 20px;
    }

    .admin-attendance-list-vertical-line {
        width: 8px;
        height: 40px;
        background: #000000;
        flex-shrink: 0;
    }

    .admin-attendance-list-title h1 {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 30px;
        line-height: 36px;
        color: #000000;
    }

    .admin-attendance-list-date-section {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 60px;
        position: relative;
    }

    .admin-attendance-list-date-selector {
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

    .admin-attendance-list-date-prev,
    .admin-attendance-list-date-next {
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

    .admin-attendance-list-date-next {
        gap: 4px;
    }

    .admin-attendance-list-date-prev:hover,
    .admin-attendance-list-date-next:hover {
        opacity: 0.7;
    }

    .admin-attendance-list-date-prev span:first-child,
    .admin-attendance-list-date-next span:last-child {
        font-size: 20px;
        line-height: 1;
        flex-shrink: 0;
    }

    .admin-attendance-list-date-text {
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 20px;
        line-height: 24px;
        color: #000000;
        min-width: 119px;
        text-align: left;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .admin-attendance-list-date-text::before {
        content: '';
        display: inline-block;
        width: 25px;
        height: 25px;
        background-image: url("data:image/svg+xml,%3Csvg width='25' height='25' viewBox='0 0 25 25' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M18.75 3.75H6.25C4.86929 3.75 3.75 4.86929 3.75 6.25V18.75C3.75 20.1307 4.86929 21.25 6.25 21.25H18.75C20.1307 21.25 21.25 20.1307 21.25 18.75V6.25C21.25 4.86929 20.1307 3.75 18.75 3.75Z' stroke='%23000000' stroke-width='2'/%3E%3Cpath d='M3.75 10H21.25' stroke='%23000000' stroke-width='2'/%3E%3Cpath d='M8.75 3.75V10' stroke='%23000000' stroke-width='2'/%3E%3Cpath d='M16.25 3.75V10' stroke='%23000000' stroke-width='2'/%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        flex-shrink: 0;
    }

    .admin-attendance-list-table-container {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background: #FFFFFF;
        border-radius: 10px;
        padding: 15px 0 15px 0;
        overflow-x: auto;
        border: 1px solid #E1E1E1;
    }

    .admin-attendance-list-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .admin-attendance-list-table thead {
        border-bottom: 3px solid #E1E1E1;
    }

    .admin-attendance-list-table-header-row {
        height: 31px;
    }

    .admin-attendance-list-table-header-item {
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

    .admin-attendance-list-table-header-item.name-col {
        padding-left: 36px;
        width: 78px;
        text-align: center;
    }

    .admin-attendance-list-table-header-item.time-col {
        text-align: left;
    }

    .admin-attendance-list-table-header-item.clock-in-col {
        padding-left: 0;
        width: 58px;
    }

    .admin-attendance-list-table-header-item.clock-out-col {
        padding-left: 0;
        width: 55px;
    }

    .admin-attendance-list-table-header-item.break-col {
        padding-left: 0;
        width: 42px;
    }

    .admin-attendance-list-table-header-item.total-col {
        padding-left: 0;
        width: 45px;
    }

    .admin-attendance-list-table-header-item.detail-col {
        text-align: right;
        padding-right: 0;
        width: 35px;
    }

    .admin-attendance-list-table-row {
        height: 33px;
        border-bottom: 2px solid #E1E1E1;
    }

    .admin-attendance-list-table-row:last-child {
        border-bottom: none;
    }

    .admin-attendance-list-table-cell {
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

    .admin-attendance-list-table-cell.name-col {
        padding-left: 36px;
        width: 78px;
        text-align: center;
    }

    .admin-attendance-list-table-cell.time-col {
        text-align: left;
    }

    .admin-attendance-list-table-cell.clock-in-col {
        padding-left: 0;
        width: 58px;
    }

    .admin-attendance-list-table-cell.clock-out-col {
        padding-left: 0;
        width: 55px;
    }

    .admin-attendance-list-table-cell.break-col {
        padding-left: 0;
        width: 42px;
    }

    .admin-attendance-list-table-cell.total-col {
        padding-left: 0;
        width: 45px;
    }

    .admin-attendance-list-table-cell.detail-col {
        text-align: right;
        padding-right: 0;
        width: 35px;
    }

    .admin-attendance-list-detail-link {
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #000000;
        text-decoration: none;
    }

    .admin-attendance-list-detail-link:hover {
        text-decoration: underline;
    }

    .admin-attendance-list-empty {
        text-align: center;
        padding: 48px 24px;
        color: #696969;
    }

    .admin-attendance-list-empty p {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        margin: 0;
    }

    /* レスポンシブ対応 */
    @media (max-width: 1024px) {
        .admin-attendance-list-container {
            padding: 24px 16px;
        }

        .admin-attendance-list-header {
            margin-bottom: 40px;
        }

        .admin-attendance-list-title h1 {
            font-size: 24px;
            line-height: 28px;
        }

        .admin-attendance-list-vertical-line {
            height: 32px;
        }

        .admin-attendance-list-date-section {
            margin-bottom: 40px;
        }
    }

    @media (max-width: 768px) {
        .admin-attendance-list-container {
            padding: 20px 12px;
        }

        .admin-attendance-list-header {
            margin-bottom: 32px;
        }

        .admin-attendance-list-title h1 {
            font-size: 20px;
            line-height: 24px;
        }

        .admin-attendance-list-vertical-line {
            width: 6px;
            height: 28px;
        }

        .admin-attendance-list-date-section {
            margin-bottom: 32px;
        }

        .admin-attendance-list-table-container {
            overflow-x: auto;
        }
    }

    @media (max-width: 480px) {
        .admin-attendance-list-header {
            padding-left: 0;
        }

        .admin-attendance-list-date-selector {
            padding: 8px 16px;
            height: auto;
            min-height: 60px;
        }

        .admin-attendance-list-date-text {
            font-size: 16px;
        }
    }
</style>
@endsection

@section('content')
@php
    $dateObj = Carbon\Carbon::parse($date);
    $formattedDate = $dateObj->format('Y年n月j日');
@endphp
<div class="admin-attendance-list-container">
    <div class="admin-attendance-list-wrapper">
        <!-- タイトルセクション -->
        <div class="admin-attendance-list-header">
            <div class="admin-attendance-list-vertical-line"></div>
            <div class="admin-attendance-list-title">
                <h1>{{ $formattedDate }}の勤怠</h1>
            </div>
        </div>

        <!-- 日付選択セクション -->
        <div class="admin-attendance-list-date-section">
            <form method="GET" action="{{ route('admin.attendance.list') }}" class="admin-attendance-list-date-selector">
                <button type="submit" name="date" value="{{ $prevDate }}" class="admin-attendance-list-date-prev">
                    <span>←</span>
                    <span>前日</span>
                </button>
                <span class="admin-attendance-list-date-text">{{ $dateObj->format('Y/m/d') }}</span>
                <button type="submit" name="date" value="{{ $nextDate }}" class="admin-attendance-list-date-next">
                    <span>翌日</span>
                    <span>→</span>
                </button>
            </form>
        </div>

        <!-- テーブルセクション -->
        <div class="admin-attendance-list-table-container">
            @if($records->count() > 0)
                <table class="admin-attendance-list-table">
                    <thead>
                        <tr class="admin-attendance-list-table-header-row">
                            <th class="admin-attendance-list-table-header-item name-col">名前</th>
                            <th class="admin-attendance-list-table-header-item time-col clock-in-col">出勤</th>
                            <th class="admin-attendance-list-table-header-item time-col clock-out-col">退勤</th>
                            <th class="admin-attendance-list-table-header-item time-col break-col">休憩</th>
                            <th class="admin-attendance-list-table-header-item time-col total-col">合計</th>
                            <th class="admin-attendance-list-table-header-item detail-col">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr class="admin-attendance-list-table-row">
                                <td class="admin-attendance-list-table-cell name-col">
                                    {{ $record->user->name }}
                                </td>
                                <td class="admin-attendance-list-table-cell time-col clock-in-col">
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
                                <td class="admin-attendance-list-table-cell time-col clock-out-col">
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
                                <td class="admin-attendance-list-table-cell time-col break-col">
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
                                <td class="admin-attendance-list-table-cell time-col total-col">
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
                                <td class="admin-attendance-list-table-cell detail-col">
                                    <a href="{{ route('admin.attendance.detail', $record->id) }}" class="admin-attendance-list-detail-link">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="admin-attendance-list-empty">
                    <p>選択した日の勤怠記録がありません。</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
