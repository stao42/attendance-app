@extends('layouts.app')

@section('title', '申請一覧')

@section('styles')
<style>
    .stamp-correction-request-list-container {
        width: 100%;
        min-height: calc(100vh - 80px);
        background: #F0EFF2;
        padding: 40px 24px;
    }

    .stamp-correction-request-list-wrapper {
        max-width: 1512px;
        margin: 0 auto;
        width: 100%;
    }

    .stamp-correction-request-list-header {
        display: flex;
        align-items: center;
        gap: 21px;
        margin-bottom: 60px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .stamp-correction-request-list-vertical-line {
        width: 8px;
        height: 40px;
        background: #000000;
        flex-shrink: 0;
    }

    .stamp-correction-request-list-title h1 {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 30px;
        line-height: 36px;
        color: #000000;
    }

    .stamp-correction-request-list-tabs {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 0;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        padding-bottom: 36px;
    }

    .stamp-correction-request-list-tabs::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 900px;
        height: 0;
        border-top: 1px solid #000000;
    }

    .stamp-correction-request-list-tab {
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #000000;
        padding: 0;
        background: none;
        border: none;
        cursor: pointer;
        position: relative;
    }

    .stamp-correction-request-list-tab:first-child {
        margin-left: 57px;
        margin-right: 178px;
    }

    .stamp-correction-request-list-tab.active {
        font-weight: 700;
    }

    .stamp-correction-request-list-tab.inactive {
        font-weight: 400;
    }

    .stamp-correction-request-list-tab-separator {
        display: none;
    }

    .stamp-correction-request-list-table-container {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        margin-top: 45px;
        background: #FFFFFF;
        border-radius: 10px;
        padding: 15px 0 15px 0;
        overflow-x: auto;
        border: 1px solid #E1E1E1;
    }

    .stamp-correction-request-list-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .stamp-correction-request-list-table thead {
        border-bottom: 3px solid #E1E1E1;
    }

    .stamp-correction-request-list-table-header-row {
        height: 32px;
    }

    .stamp-correction-request-list-table-header-item {
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
        text-align: left;
    }

    .stamp-correction-request-list-table-header-item.status-col {
        padding-left: 37px;
        width: 72px;
    }

    .stamp-correction-request-list-table-header-item.name-col {
        padding-left: 0;
        width: 53px;
    }

    .stamp-correction-request-list-table-header-item.date-col {
        padding-left: 0;
        width: 95px;
    }

    .stamp-correction-request-list-table-header-item.reason-col {
        padding-left: 0;
        width: 90px;
    }

    .stamp-correction-request-list-table-header-item.request-date-col {
        padding-left: 0;
        width: 97px;
    }

    .stamp-correction-request-list-table-header-item.detail-col {
        text-align: right;
        padding-right: 36px;
        width: 35px;
    }

    .stamp-correction-request-list-table-row {
        height: 33px;
        border-bottom: 2px solid #E1E1E1;
    }

    .stamp-correction-request-list-table-row:last-child {
        border-bottom: none;
    }

    .stamp-correction-request-list-table-cell {
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
        text-align: left;
    }

    .stamp-correction-request-list-table-cell.status-col {
        padding-left: 37px;
        width: 72px;
    }

    .stamp-correction-request-list-table-cell.name-col {
        padding-left: 0;
        width: 53px;
    }

    .stamp-correction-request-list-table-cell.date-col {
        padding-left: 0;
        width: 95px;
    }

    .stamp-correction-request-list-table-cell.reason-col {
        padding-left: 0;
        width: 90px;
    }

    .stamp-correction-request-list-table-cell.request-date-col {
        padding-left: 0;
        width: 97px;
    }

    .stamp-correction-request-list-table-cell.detail-col {
        text-align: right;
        padding-right: 36px;
        width: 35px;
    }

    .stamp-correction-request-list-detail-link {
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

    .stamp-correction-request-list-detail-link:hover {
        text-decoration: underline;
    }

    .stamp-correction-request-list-empty {
        padding: 100px 24px;
        text-align: center;
        color: #696969;
    }

    .stamp-correction-request-list-empty p {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        margin: 0;
    }

    /* レスポンシブ対応 */
    @media (max-width: 1024px) {
        .stamp-correction-request-list-container {
            padding: 24px 16px;
        }

        .stamp-correction-request-list-header {
            padding-left: 0;
            margin-bottom: 40px;
        }

        .stamp-correction-request-list-title h1 {
            font-size: 24px;
            line-height: 28px;
        }

        .stamp-correction-request-list-vertical-line {
            height: 32px;
        }

        .stamp-correction-request-list-tabs {
            padding-bottom: 24px;
        }

        .stamp-correction-request-list-table-container {
            border-radius: 8px;
        }
    }

    @media (max-width: 768px) {
        .stamp-correction-request-list-container {
            padding: 20px 12px;
        }

        .stamp-correction-request-list-header {
            margin-bottom: 32px;
        }

        .stamp-correction-request-list-title h1 {
            font-size: 20px;
            line-height: 24px;
        }

        .stamp-correction-request-list-vertical-line {
            width: 6px;
            height: 28px;
        }

        .stamp-correction-request-list-tabs {
            padding-bottom: 20px;
        }

        .stamp-correction-request-list-table-header-item,
        .stamp-correction-request-list-table-cell {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .stamp-correction-request-list-header {
            gap: 12px;
        }

        .stamp-correction-request-list-title h1 {
            font-size: 18px;
            line-height: 22px;
        }

        .stamp-correction-request-list-vertical-line {
            width: 4px;
            height: 24px;
        }
    }
</style>
@endsection

@section('content')
<div class="stamp-correction-request-list-container">
    <div class="stamp-correction-request-list-wrapper">
        <!-- タイトルセクション -->
        <div class="stamp-correction-request-list-header">
            <div class="stamp-correction-request-list-vertical-line"></div>
            <div class="stamp-correction-request-list-title">
                <h1>申請一覧</h1>
            </div>
        </div>

        <!-- タブセクション -->
        <div class="stamp-correction-request-list-tabs">
            <button class="stamp-correction-request-list-tab active" id="pending-tab" onclick="showPending()">承認待ち</button>
            <button class="stamp-correction-request-list-tab inactive" id="approved-tab" onclick="showApproved()">承認済み</button>
        </div>

        <!-- 承認待ちテーブル -->
        <div id="pending-section" class="stamp-correction-request-list-table-container">
            @if($pendingRequests->count() > 0)
                <table class="stamp-correction-request-list-table">
                    <thead>
                        <tr class="stamp-correction-request-list-table-header-row">
                            <th class="stamp-correction-request-list-table-header-item status-col">状態</th>
                            <th class="stamp-correction-request-list-table-header-item name-col">名前</th>
                            <th class="stamp-correction-request-list-table-header-item date-col">対象日時</th>
                            <th class="stamp-correction-request-list-table-header-item reason-col">申請理由</th>
                            <th class="stamp-correction-request-list-table-header-item request-date-col">申請日時</th>
                            <th class="stamp-correction-request-list-table-header-item detail-col">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $request)
                            <tr class="stamp-correction-request-list-table-row">
                                <td class="stamp-correction-request-list-table-cell status-col">承認待ち</td>
                                <td class="stamp-correction-request-list-table-cell name-col">{{ $request->attendanceRecord->user->name }}</td>
                                <td class="stamp-correction-request-list-table-cell date-col">{{ $request->attendanceRecord->date->format('Y/m/d') }}</td>
                                <td class="stamp-correction-request-list-table-cell reason-col">{{ $request->requested_notes ?? '-' }}</td>
                                <td class="stamp-correction-request-list-table-cell request-date-col">{{ $request->created_at->format('Y/m/d') }}</td>
                                <td class="stamp-correction-request-list-table-cell detail-col">
                                    <a href="{{ route('attendance.detail', $request->attendance_record_id) }}" class="stamp-correction-request-list-detail-link">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="stamp-correction-request-list-empty">
                    <p>承認待ちの申請がありません。</p>
                </div>
            @endif
        </div>

        <!-- 承認済みテーブル -->
        <div id="approved-section" class="stamp-correction-request-list-table-container" style="display: none;">
            @if($approvedRequests->count() > 0)
                <table class="stamp-correction-request-list-table">
                    <thead>
                        <tr class="stamp-correction-request-list-table-header-row">
                            <th class="stamp-correction-request-list-table-header-item status-col">状態</th>
                            <th class="stamp-correction-request-list-table-header-item name-col">名前</th>
                            <th class="stamp-correction-request-list-table-header-item date-col">対象日時</th>
                            <th class="stamp-correction-request-list-table-header-item reason-col">申請理由</th>
                            <th class="stamp-correction-request-list-table-header-item request-date-col">申請日時</th>
                            <th class="stamp-correction-request-list-table-header-item detail-col">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedRequests as $request)
                            <tr class="stamp-correction-request-list-table-row">
                                <td class="stamp-correction-request-list-table-cell status-col">承認済み</td>
                                <td class="stamp-correction-request-list-table-cell name-col">{{ $request->attendanceRecord->user->name }}</td>
                                <td class="stamp-correction-request-list-table-cell date-col">{{ $request->attendanceRecord->date->format('Y/m/d') }}</td>
                                <td class="stamp-correction-request-list-table-cell reason-col">{{ $request->requested_notes ?? '-' }}</td>
                                <td class="stamp-correction-request-list-table-cell request-date-col">{{ $request->created_at->format('Y/m/d') }}</td>
                                <td class="stamp-correction-request-list-table-cell detail-col">
                                    <a href="{{ route('attendance.detail', $request->attendance_record_id) }}" class="stamp-correction-request-list-detail-link">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="stamp-correction-request-list-empty">
                    <p>承認済みの申請がありません。</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function showPending() {
    document.getElementById('pending-section').style.display = 'block';
    document.getElementById('approved-section').style.display = 'none';
    document.getElementById('pending-tab').classList.remove('inactive');
    document.getElementById('pending-tab').classList.add('active');
    document.getElementById('approved-tab').classList.add('inactive');
    document.getElementById('approved-tab').classList.remove('active');
}

function showApproved() {
    document.getElementById('pending-section').style.display = 'none';
    document.getElementById('approved-section').style.display = 'block';
    document.getElementById('approved-tab').classList.remove('inactive');
    document.getElementById('approved-tab').classList.add('active');
    document.getElementById('pending-tab').classList.add('inactive');
    document.getElementById('pending-tab').classList.remove('active');
}

// 初期状態で承認待ちタブをアクティブにする
document.addEventListener('DOMContentLoaded', function() {
    showPending();
});
</script>
@endsection
