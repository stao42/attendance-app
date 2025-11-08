@extends('layouts.app')

@section('title', '修正申請承認')

@section('styles')
<style>
    .admin-approve-container {
        min-height: calc(100vh - 80px);
        background: #F0EFF2;
        padding: clamp(32px, 6vw, 80px) clamp(16px, 6vw, 80px) clamp(96px, 10vw, 128px);
    }

    .admin-approve-wrapper {
        max-width: 920px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .admin-approve-header {
        display: inline-flex;
        align-items: center;
        gap: 16px;
    }

    .admin-approve-vertical-line {
        width: 8px;
        height: 40px;
        background: #000000;
        border-radius: 99px;
    }

    .admin-approve-title h1 {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: clamp(22px, 3vw, 30px);
        letter-spacing: 0.08em;
        color: #000000;
    }

    .admin-approve-card {
        background: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: clamp(24px, 4vw, 56px);
        --value-column-width: clamp(120px, 14vw, 160px);
        --value-column-gap: clamp(16px, 3.5vw, 48px);
    }

    .admin-approve-rows {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .admin-approve-row {
        padding: clamp(12px, 2vw, 20px) 0;
        border-bottom: 1px solid #E3E3E3;
    }

    .admin-approve-row:last-child {
        border-bottom: none;
    }

    .admin-approve-row-content {
        display: grid;
        grid-template-columns: minmax(120px, 180px) 1fr;
        column-gap: clamp(16px, 4vw, 64px);
        align-items: center;
    }

    .admin-approve-label {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #737373;
        white-space: nowrap;
    }

    .admin-approve-value {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #000000;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .admin-approve-date-group {
        display: grid;
        grid-template-columns: var(--value-column-width) minmax(20px, max-content) var(--value-column-width);
        column-gap: var(--value-column-gap);
        row-gap: 8px;
        align-items: center;
        justify-content: flex-start;
    }

    .admin-approve-date-group span:nth-child(1) {
        grid-column: 1;
    }

    .admin-approve-date-group span:nth-child(2) {
        grid-column: 3;
    }

    .admin-approve-time-group,
    .admin-approve-break-item {
        display: grid;
        grid-template-columns: var(--value-column-width) minmax(20px, max-content) var(--value-column-width);
        column-gap: var(--value-column-gap);
        align-items: center;
        justify-content: flex-start;
    }

    .admin-approve-time-display {
        width: 100%;
        height: auto;
        padding: 0;
        background: transparent;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #000000;
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
    }

    .admin-approve-time-separator {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #000000;
        text-align: center;
    }

    .admin-approve-break-group {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .admin-approve-notes {
        width: min(100%, 360px);
        min-height: auto;
        padding: 0;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.12em;
        line-height: 1.6;
        background: transparent;
        color: #000000;
    }

    .admin-approve-button-section {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        margin-top: clamp(24px, 4vw, 40px);
    }

    .admin-approve-button {
        width: 150px;
        height: 52px;
        border-radius: 6px;
        border: none;
        background: #000000;
        color: #FFFFFF;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 20px;
        letter-spacing: 0.15em;
        cursor: pointer;
        transition: opacity 0.2s ease;
    }

    .admin-approve-button:hover {
        opacity: 0.85;
    }

    .admin-approve-button-reject {
        background: #696969;
    }

    .admin-approve-button-reject:hover {
        opacity: 0.85;
    }

    .admin-approve-button-approved {
        background: #696969;
        cursor: not-allowed;
    }

    .admin-approve-button-approved:hover {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .admin-approve-container {
            padding: 24px 16px 64px;
        }

        .admin-approve-row-content {
            grid-template-columns: 1fr;
            row-gap: 12px;
        }

        .admin-approve-label {
            white-space: normal;
        }

        .admin-approve-date-group {
            grid-template-columns: 1fr;
            column-gap: 0;
        }

        .admin-approve-date-group span:nth-child(1),
        .admin-approve-date-group span:nth-child(2) {
            grid-column: auto;
        }

        .admin-approve-time-group,
        .admin-approve-break-item {
            grid-template-columns: 1fr auto 1fr;
            column-gap: 12px;
            row-gap: 8px;
        }

        .admin-approve-time-display {
            width: 100%;
        }

        .admin-approve-button-section {
            justify-content: stretch;
        }

        .admin-approve-button {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-approve-container">
    <div class="admin-approve-wrapper">
        <!-- タイトルセクション -->
        <div class="admin-approve-header">
            <div class="admin-approve-vertical-line"></div>
            <div class="admin-approve-title">
                <h1>勤怠詳細</h1>
            </div>
        </div>

        <!-- カード -->
        <div class="admin-approve-card">
            <div class="admin-approve-rows">
                <!-- 名前 -->
                <div class="admin-approve-row">
                    <div class="admin-approve-row-content">
                        <div class="admin-approve-label">名前</div>
                        <div class="admin-approve-value">
                            {{ $request->user->name }}
                        </div>
                    </div>
                </div>

                <!-- 日付 -->
                <div class="admin-approve-row">
                    <div class="admin-approve-row-content">
                        <div class="admin-approve-label">日付</div>
                        <div class="admin-approve-value">
                            <div class="admin-approve-date-group">
                                <span>{{ $request->attendanceRecord->date->format('Y年') }}</span>
                                <span>{{ $request->attendanceRecord->date->format('n月j日') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 出勤・退勤 -->
                <div class="admin-approve-row">
                    <div class="admin-approve-row-content">
                        <div class="admin-approve-label">出勤・退勤</div>
                        <div class="admin-approve-value">
                            <div class="admin-approve-time-group">
                                <div class="admin-approve-time-display">
                                    {{ $request->requested_clock_in ? \Carbon\Carbon::parse($request->requested_clock_in)->format('H:i') : '-' }}
                                </div>
                                <div class="admin-approve-time-separator">〜</div>
                                <div class="admin-approve-time-display">
                                    {{ $request->requested_clock_out ? \Carbon\Carbon::parse($request->requested_clock_out)->format('H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 休憩 -->
                @if($request->attendanceRecord->breaks && $request->attendanceRecord->breaks->count() > 0)
                    @if($request->attendanceRecord->breaks->count() === 1)
                        <div class="admin-approve-row">
                            <div class="admin-approve-row-content">
                                <div class="admin-approve-label">休憩</div>
                                <div class="admin-approve-value">
                                    @php $break = $request->attendanceRecord->breaks->first(); @endphp
                                    <div class="admin-approve-break-item">
                                        <div class="admin-approve-time-display">
                                            {{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '-' }}
                                        </div>
                                        <div class="admin-approve-time-separator">〜</div>
                                        <div class="admin-approve-time-display">
                                            {{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="admin-approve-row">
                            <div class="admin-approve-row-content">
                                <div class="admin-approve-label">休憩</div>
                                <div class="admin-approve-value">
                                    <div class="admin-approve-break-group">
                                        @foreach($request->attendanceRecord->breaks as $index => $break)
                                            <div class="admin-approve-break-item">
                                                <div class="admin-approve-time-display">
                                                    {{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '-' }}
                                                </div>
                                                <div class="admin-approve-time-separator">〜</div>
                                                <div class="admin-approve-time-display">
                                                    {{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '-' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="admin-approve-row">
                        <div class="admin-approve-row-content">
                            <div class="admin-approve-label">休憩</div>
                            <div class="admin-approve-value">
                                <div class="admin-approve-break-item">
                                    <div class="admin-approve-time-display">-</div>
                                    <div class="admin-approve-time-separator">〜</div>
                                    <div class="admin-approve-time-display">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 備考 -->
                <div class="admin-approve-row">
                    <div class="admin-approve-row-content">
                        <div class="admin-approve-label">備考</div>
                        <div class="admin-approve-value">
                            <div class="admin-approve-notes">
                                {{ $request->requested_notes ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($request->status === 'pending')
            <!-- 承認・却下ボタン -->
            <div class="admin-approve-button-section">
                <form method="POST" action="{{ route('admin.stamp_correction_request.reject', $request->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="admin-approve-button admin-approve-button-reject" onclick="return confirm('この申請を却下しますか？')">却下</button>
                </form>
                <form method="POST" action="{{ route('admin.stamp_correction_request.approve', $request->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="admin-approve-button">承認</button>
                </form>
            </div>
        @elseif($request->status === 'approved')
            <!-- 承認済みボタン -->
            <div class="admin-approve-button-section">
                <button type="button" class="admin-approve-button admin-approve-button-approved" disabled>承認済み</button>
            </div>
        @elseif($request->status === 'rejected')
            <!-- 却下済みボタン -->
            <div class="admin-approve-button-section">
                <button type="button" class="admin-approve-button admin-approve-button-approved" disabled>却下済み</button>
            </div>
        @endif
    </div>
</div>
@endsection
