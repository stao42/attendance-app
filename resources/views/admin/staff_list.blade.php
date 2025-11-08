@extends('layouts.app')

@section('title', 'スタッフ一覧（管理者）')

@section('styles')
<style>
    .admin-staff-list-container {
        width: 100%;
        min-height: calc(100vh - 80px);
        background: #F0EFF2;
        padding: 40px 24px;
    }

    .admin-staff-list-wrapper {
        max-width: 1512px;
        margin: 0 auto;
        width: 100%;
    }

    .admin-staff-list-header {
        display: flex;
        align-items: center;
        gap: 21px;
        margin-bottom: 60px;
        padding-left: 20px;
    }

    .admin-staff-list-vertical-line {
        width: 8px;
        height: 40px;
        background: #000000;
        flex-shrink: 0;
    }

    .admin-staff-list-title h1 {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 30px;
        line-height: 36px;
        color: #000000;
    }

    .admin-staff-list-table-container {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background: #FFFFFF;
        border-radius: 10px;
        padding: 15px 0 15px 0;
        overflow-x: auto;
        border: 1px solid #E1E1E1;
    }

    .admin-staff-list-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .admin-staff-list-table thead {
        border-bottom: 3px solid #E1E1E1;
    }

    .admin-staff-list-table-header-row {
        height: 31px;
    }

    .admin-staff-list-table-header-item {
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

    .admin-staff-list-table-header-item.name-col {
        padding-left: 36px;
        width: 200px;
    }

    .admin-staff-list-table-header-item.email-col {
        padding-left: 0;
        width: 400px;
    }

    .admin-staff-list-table-header-item.detail-col {
        text-align: right;
        padding-right: 0;
        width: 35px;
    }

    .admin-staff-list-table-row {
        height: 50px;
        border-bottom: 2px solid #E1E1E1;
    }

    .admin-staff-list-table-row:last-child {
        border-bottom: none;
    }

    .admin-staff-list-table-cell {
        height: 19px;
        padding: 15px 0;
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

    .admin-staff-list-table-cell.name-col {
        padding-left: 36px;
        width: 200px;
    }

    .admin-staff-list-table-cell.email-col {
        padding-left: 0;
        width: 400px;
    }

    .admin-staff-list-table-cell.detail-col {
        text-align: right;
        padding-right: 0;
        width: 35px;
    }

    .admin-staff-list-detail-link {
        font-family: 'Inter', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.15em;
        color: #000000;
        text-decoration: none;
    }

    .admin-staff-list-detail-link:hover {
        text-decoration: underline;
    }

    .admin-staff-list-empty {
        text-align: center;
        padding: 48px 24px;
        color: #696969;
    }

    .admin-staff-list-empty p {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        margin: 0;
    }

    /* レスポンシブ対応 */
    @media (max-width: 1024px) {
        .admin-staff-list-container {
            padding: 24px 16px;
        }

        .admin-staff-list-header {
            margin-bottom: 40px;
        }

        .admin-staff-list-title h1 {
            font-size: 24px;
            line-height: 28px;
        }

        .admin-staff-list-vertical-line {
            height: 32px;
        }
    }

    @media (max-width: 768px) {
        .admin-staff-list-container {
            padding: 20px 12px;
        }

        .admin-staff-list-header {
            margin-bottom: 32px;
        }

        .admin-staff-list-title h1 {
            font-size: 20px;
            line-height: 24px;
        }

        .admin-staff-list-vertical-line {
            width: 6px;
            height: 28px;
        }

        .admin-staff-list-table-container {
            overflow-x: auto;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-staff-list-container">
    <div class="admin-staff-list-wrapper">
        <!-- タイトルセクション -->
        <div class="admin-staff-list-header">
            <div class="admin-staff-list-vertical-line"></div>
            <div class="admin-staff-list-title">
                <h1>スタッフ一覧</h1>
            </div>
        </div>

        <!-- テーブルセクション -->
        <div class="admin-staff-list-table-container">
            @if($users->count() > 0)
                <table class="admin-staff-list-table">
                    <thead>
                        <tr class="admin-staff-list-table-header-row">
                            <th class="admin-staff-list-table-header-item name-col">名前</th>
                            <th class="admin-staff-list-table-header-item email-col">メールアドレス</th>
                            <th class="admin-staff-list-table-header-item detail-col">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="admin-staff-list-table-row">
                                <td class="admin-staff-list-table-cell name-col">
                                    {{ $user->name }}
                                </td>
                                <td class="admin-staff-list-table-cell email-col">
                                    {{ $user->email }}
                                </td>
                                <td class="admin-staff-list-table-cell detail-col">
                                    <a href="{{ route('admin.attendance.staff', $user->id) }}" class="admin-staff-list-detail-link">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="admin-staff-list-empty">
                    <p>スタッフが登録されていません。</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
