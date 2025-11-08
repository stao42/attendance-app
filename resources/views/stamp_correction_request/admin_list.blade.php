@extends('layouts.app')

@section('title', '申請一覧（管理者）')

@section('styles')
<style>
    .admin-stamp-correction-request-list-container {
        width: 100%;
        min-height: calc(100vh - 80px);
        background: #F0EFF2;
        padding: 40px 24px;
    }

    .admin-stamp-correction-request-list-wrapper {
        max-width: 1512px;
        margin: 0 auto;
        width: 100%;
    }

    .admin-stamp-correction-request-list-header {
        display: flex;
        align-items: center;
        gap: 21px;
        margin-bottom: 60px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .admin-stamp-correction-request-list-vertical-line {
        width: 8px;
        height: 40px;
        background: #000000;
        flex-shrink: 0;
    }

    .admin-stamp-correction-request-list-title h1 {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 30px;
        line-height: 36px;
        color: #000000;
    }

    .admin-stamp-correction-request-list-tabs {
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

    .admin-stamp-correction-request-list-tabs::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 900px;
        height: 0;
        border-top: 1px solid #000000;
    }

    .admin-stamp-correction-request-list-tab {
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

    .admin-stamp-correction-request-list-tab:first-child {
        margin-left: 57px;
        margin-right: 178px;
    }

    .admin-stamp-correction-request-list-tab.active {
        font-weight: 700;
    }

    .admin-stamp-correction-request-list-tab.inactive {
        font-weight: 400;
    }

    .admin-stamp-correction-request-list-table-container {
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

    .admin-stamp-correction-request-list-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .admin-stamp-correction-request-list-table thead {
        border-bottom: 3px solid #E1E1E1;
    }

    .admin-stamp-correction-request-list-table-header-row {
        height: 32px;
    }

    .admin-stamp-correction-request-list-table-header-item {
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
        text-align: center;
    }

    .admin-stamp-correction-request-list-table-header-item.status-col {
        padding-left: 55px;
        width: 72px;
    }

    .admin-stamp-correction-request-list-table-header-item.name-col {
        padding-left: 0;
        width: 53px;
    }

    .admin-stamp-correction-request-list-table-header-item.date-col {
        padding-left: 0;
        width: 95px;
    }

    .admin-stamp-correction-request-list-table-header-item.reason-col {
        padding-left: 0;
        width: 90px;
    }

    .admin-stamp-correction-request-list-table-header-item.request-date-col {
        padding-left: 0;
        width: 97px;
    }

    .admin-stamp-correction-request-list-table-header-item.approved-date-col {
        padding-left: 0;
        width: 97px;
    }

    .admin-stamp-correction-request-list-table-header-item.detail-col {
        text-align: right;
        padding-right: 0;
        width: 35px;
    }

    .admin-stamp-correction-request-list-table-row {
        height: 33px;
        border-bottom: 2px solid #E1E1E1;
    }

    .admin-stamp-correction-request-list-table-row:last-child {
        border-bottom: none;
    }

    .admin-stamp-correction-request-list-table-cell {
        height: 19px;
        padding: 7px 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        color: #737373;
        white-space: nowrap;
        text-align: center;
    }

    .admin-stamp-correction-request-list-table-cell.status-col {
        padding-left: 55px;
        width: 72px;
    }

    .admin-stamp-correction-request-list-table-cell.name-col {
        padding-left: 0;
        width: 53px;
    }

    .admin-stamp-correction-request-list-table-cell.date-col {
        padding-left: 0;
        width: 95px;
    }

    .admin-stamp-correction-request-list-table-cell.reason-col {
        padding-left: 0;
        width: 90px;
    }

    .admin-stamp-correction-request-list-table-cell.request-date-col {
        padding-left: 0;
        width: 97px;
    }

    .admin-stamp-correction-request-list-table-cell.approved-date-col {
        padding-left: 0;
        width: 97px;
    }

    .admin-stamp-correction-request-list-table-cell.detail-col {
        text-align: right;
        padding-right: 0;
        width: 35px;
    }

    .admin-stamp-correction-request-list-detail-link {
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        color: #000000;
        text-decoration: none;
    }

    .admin-stamp-correction-request-list-empty {
        padding: 24px;
        text-align: center;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 19px;
        color: #696969;
    }

    @media (max-width: 1024px) {
        .admin-stamp-correction-request-list-container {
            padding: 32px 16px;
        }

        .admin-stamp-correction-request-list-header {
            margin-bottom: 40px;
        }

        .admin-stamp-correction-request-list-title h1 {
            font-size: 24px;
            line-height: 29px;
        }

        .admin-stamp-correction-request-list-vertical-line {
            width: 6px;
            height: 32px;
        }
    }

    @media (max-width: 768px) {
        .admin-stamp-correction-request-list-container {
            padding: 24px 12px;
        }

        .admin-stamp-correction-request-list-header {
            margin-bottom: 32px;
        }

        .admin-stamp-correction-request-list-title h1 {
            font-size: 20px;
            line-height: 24px;
        }

        .admin-stamp-correction-request-list-vertical-line {
            width: 4px;
            height: 24px;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-stamp-correction-request-list-container">
    <div class="admin-stamp-correction-request-list-wrapper">
        <!-- タイトルセクション -->
        <div class="admin-stamp-correction-request-list-header">
            <div class="admin-stamp-correction-request-list-vertical-line"></div>
            <div class="admin-stamp-correction-request-list-title">
                <h1>申請一覧</h1>
            </div>
        </div>

        <!-- タブセクション -->
        <div class="admin-stamp-correction-request-list-tabs">
            <button class="admin-stamp-correction-request-list-tab active" id="pending-tab" onclick="showPending()">承認待ち</button>
            <button class="admin-stamp-correction-request-list-tab inactive" id="approved-tab" onclick="showApproved()">承認済み</button>
        </div>

        <!-- 承認待ちテーブル -->
        <div id="pending-section" class="admin-stamp-correction-request-list-table-container">
            @if($pendingRequests->count() > 0)
                <table class="admin-stamp-correction-request-list-table">
                    <thead>
                        <tr class="admin-stamp-correction-request-list-table-header-row">
                            <th class="admin-stamp-correction-request-list-table-header-item status-col">状態</th>
                            <th class="admin-stamp-correction-request-list-table-header-item name-col">名前</th>
                            <th class="admin-stamp-correction-request-list-table-header-item date-col">対象日時</th>
                            <th class="admin-stamp-correction-request-list-table-header-item reason-col">申請理由</th>
                            <th class="admin-stamp-correction-request-list-table-header-item request-date-col">申請日時</th>
                            <th class="admin-stamp-correction-request-list-table-header-item detail-col">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $request)
                            <tr class="admin-stamp-correction-request-list-table-row">
                                <td class="admin-stamp-correction-request-list-table-cell status-col">承認待ち</td>
                                <td class="admin-stamp-correction-request-list-table-cell name-col">{{ $request->user->name }}</td>
                                <td class="admin-stamp-correction-request-list-table-cell date-col">{{ $request->attendanceRecord->date->format('Y/m/d') }}</td>
                                <td class="admin-stamp-correction-request-list-table-cell reason-col">{{ $request->requested_notes ?? '-' }}</td>
                                <td class="admin-stamp-correction-request-list-table-cell request-date-col">{{ $request->created_at->format('Y/m/d') }}</td>
                                <td class="admin-stamp-correction-request-list-table-cell detail-col">
                                    <a href="{{ route('admin.stamp_correction_request.approve.detail', $request->id) }}" class="admin-stamp-correction-request-list-detail-link">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="admin-stamp-correction-request-list-empty">
                    <p>承認待ちの申請がありません。</p>
                </div>
            @endif
        </div>

        <!-- 承認済みテーブル -->
        <div id="approved-section" class="admin-stamp-correction-request-list-table-container" style="display: none;">
            @if($approvedRequests->count() > 0)
                <table class="admin-stamp-correction-request-list-table">
                    <thead>
                        <tr class="admin-stamp-correction-request-list-table-header-row">
                            <th class="admin-stamp-correction-request-list-table-header-item status-col">状態</th>
                            <th class="admin-stamp-correction-request-list-table-header-item name-col">名前</th>
                            <th class="admin-stamp-correction-request-list-table-header-item date-col">対象日時</th>
                            <th class="admin-stamp-correction-request-list-table-header-item reason-col">申請理由</th>
                            <th class="admin-stamp-correction-request-list-table-header-item approved-date-col">承認日時</th>
                            <th class="admin-stamp-correction-request-list-table-header-item detail-col">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedRequests as $request)
                            <tr class="admin-stamp-correction-request-list-table-row">
                                <td class="admin-stamp-correction-request-list-table-cell status-col">承認済み</td>
                                <td class="admin-stamp-correction-request-list-table-cell name-col">{{ $request->user->name }}</td>
                                <td class="admin-stamp-correction-request-list-table-cell date-col">{{ $request->attendanceRecord->date->format('Y/m/d') }}</td>
                                <td class="admin-stamp-correction-request-list-table-cell reason-col">{{ $request->requested_notes ?? '-' }}</td>
                                <td class="admin-stamp-correction-request-list-table-cell approved-date-col">{{ $request->approved_at ? $request->approved_at->format('Y/m/d') : '-' }}</td>
                                <td class="admin-stamp-correction-request-list-table-cell detail-col">
                                    <a href="{{ route('admin.stamp_correction_request.approve.detail', $request->id) }}" class="admin-stamp-correction-request-list-detail-link">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="admin-stamp-correction-request-list-empty">
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
        document.getElementById('pending-tab').classList.add('active');
        document.getElementById('pending-tab').classList.remove('inactive');
        document.getElementById('approved-tab').classList.add('inactive');
        document.getElementById('approved-tab').classList.remove('active');
    }

    function showApproved() {
        document.getElementById('pending-section').style.display = 'none';
        document.getElementById('approved-section').style.display = 'block';
        document.getElementById('approved-tab').classList.add('active');
        document.getElementById('approved-tab').classList.remove('inactive');
        document.getElementById('pending-tab').classList.add('inactive');
        document.getElementById('pending-tab').classList.remove('active');
    }
</script>
@endsection
