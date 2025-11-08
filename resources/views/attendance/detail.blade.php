@extends('layouts.app')

@section('title', '勤怠詳細')

@section('styles')
<style>
    .attendance-detail-container {
        min-height: calc(100vh - 80px);
        background: #F0EFF2;
        padding: clamp(32px, 6vw, 80px) clamp(16px, 6vw, 80px) clamp(96px, 10vw, 128px);
    }

    .attendance-detail-wrapper {
        max-width: 920px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .attendance-detail-header {
        display: inline-flex;
        align-items: center;
        gap: 16px;
    }

    .attendance-detail-vertical-line {
        width: 8px;
        height: 40px;
        background: #000000;
        border-radius: 99px;
    }

    .attendance-detail-header h1 {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: clamp(22px, 3vw, 30px);
        letter-spacing: 0.08em;
    }

    .attendance-detail-card {
        background: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: clamp(24px, 4vw, 56px);
        --value-column-width: clamp(120px, 14vw, 160px);
        --value-column-gap: clamp(16px, 3.5vw, 48px);
    }

    .attendance-detail-pending-message {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #FF000080;
    }

    .attendance-detail-form,
    .attendance-detail-rows {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .attendance-detail-row {
        padding: clamp(16px, 3vw, 28px) 0;
        border-bottom: 1px solid #E3E3E3;
    }

    .attendance-detail-row:last-child {
        border-bottom: none;
    }

    .attendance-detail-row-content {
        display: grid;
        grid-template-columns: minmax(120px, 180px) 1fr;
        column-gap: clamp(16px, 4vw, 64px);
        align-items: center;
    }

    .attendance-detail-label {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #737373;
        white-space: nowrap;
    }

    .attendance-detail-value {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #000000;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .attendance-detail-date-group {
        display: grid;
        grid-template-columns: var(--value-column-width) minmax(20px, max-content) var(--value-column-width);
        column-gap: var(--value-column-gap);
        row-gap: 8px;
        align-items: center;
        justify-content: flex-start;
    }

    .attendance-detail-date-group span:nth-child(1) {
        grid-column: 1;
    }

    .attendance-detail-date-group span:nth-child(2) {
        grid-column: 3;
    }

    .attendance-detail-time-group,
    .attendance-detail-break-item {
        display: grid;
        grid-template-columns: var(--value-column-width) minmax(20px, max-content) var(--value-column-width);
        column-gap: var(--value-column-gap);
        align-items: center;
        justify-content: flex-start;
    }

    .attendance-detail-time-input,
    .attendance-detail-time-display {
        width: 100%;
        height: 40px;
        border-radius: 6px;
        border: 1px solid #E1E1E1;
        padding: 0 16px;
        background: #FFFFFF;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #000000;
    }

    .attendance-detail-time-display {
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        background: #F9F9F9;
    }

    .attendance-detail-time-input::-webkit-calendar-picker-indicator {
        display: none;
    }

    .attendance-detail-time-input::-webkit-inner-spin-button,
    .attendance-detail-time-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .attendance-detail-time-separator {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.15em;
        color: #000000;
        text-align: center;
    }

    .attendance-detail-break-group {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .attendance-detail-notes-input,
    .attendance-detail-notes-display {
        width: min(100%, 360px);
        min-height: 72px;
        border-radius: 6px;
        border: 1px solid #D9D9D9;
        padding: 12px 16px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.12em;
        line-height: 1.6;
        resize: none;
        background: #FFFFFF;
    }

    .attendance-detail-notes-display {
        border: 1px solid #F0F0F0;
        background: #F9F9F9;
    }

    .attendance-detail-error {
        font-size: 14px;
        color: #D93025;
        letter-spacing: normal;
    }

    .attendance-detail-action {
        display: flex;
        justify-content: flex-end;
        margin-top: clamp(24px, 4vw, 40px);
    }

    .attendance-detail-submit-button {
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

    .attendance-detail-submit-button:hover {
        opacity: 0.85;
    }

    @media (max-width: 768px) {
        .attendance-detail-container {
            padding: 24px 16px 64px;
        }

        .attendance-detail-row-content {
            grid-template-columns: 1fr;
            row-gap: 12px;
        }

        .attendance-detail-label {
            white-space: normal;
        }

        .attendance-detail-date-group {
            grid-template-columns: 1fr;
            column-gap: 0;
        }

        .attendance-detail-date-group span:nth-child(1),
        .attendance-detail-date-group span:nth-child(2) {
            grid-column: auto;
        }

        .attendance-detail-time-group,
        .attendance-detail-break-item {
            grid-template-columns: 1fr auto 1fr;
            column-gap: 12px;
            row-gap: 8px;
        }

        .attendance-detail-time-input,
        .attendance-detail-time-display {
            width: 100%;
        }

        .attendance-detail-action {
            justify-content: stretch;
        }

        .attendance-detail-submit-button {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
@php
    $isEditable = !$hasPendingRequest;
@endphp
<div class="attendance-detail-container">
    <div class="attendance-detail-wrapper">
        <div class="attendance-detail-header">
            <span class="attendance-detail-vertical-line" aria-hidden="true"></span>
            <h1>勤怠詳細</h1>
        </div>

        @if($isEditable)
            <form id="attendance-detail-form" method="POST" action="{{ route('attendance.request-correction', $record->id) }}" class="attendance-detail-form">
                @csrf
        @endif

        <div class="attendance-detail-card">
            <div class="attendance-detail-rows">
                <div class="attendance-detail-row">
                    <div class="attendance-detail-row-content">
                        <div class="attendance-detail-label">名前</div>
                        <div class="attendance-detail-value">{{ $record->user->name }}</div>
                    </div>
                </div>

                <div class="attendance-detail-row">
                    <div class="attendance-detail-row-content">
                        <div class="attendance-detail-label">日付</div>
                        <div class="attendance-detail-value">
                            <div class="attendance-detail-date-group">
                                <span>{{ $record->date->format('Y年') }}</span>
                                <span>{{ $record->date->format('n月j日') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="attendance-detail-row">
                    <div class="attendance-detail-row-content">
                        <div class="attendance-detail-label">出勤・退勤</div>
                        <div class="attendance-detail-value">
                            <div class="attendance-detail-time-group">
                                @if($isEditable)
                                    <input type="time" name="clock_in" value="{{ old('clock_in', $record->clock_in ? substr($record->clock_in, 0, 5) : '') }}" required class="attendance-detail-time-input">
                                    <span class="attendance-detail-time-separator">〜</span>
                                    <input type="time" name="clock_out" value="{{ old('clock_out', $record->clock_out ? substr($record->clock_out, 0, 5) : '') }}" class="attendance-detail-time-input">
                                @else
                                    <span class="attendance-detail-time-display">{{ $record->clock_in ? substr($record->clock_in, 0, 5) : '-' }}</span>
                                    <span class="attendance-detail-time-separator">〜</span>
                                    <span class="attendance-detail-time-display">{{ $record->clock_out ? substr($record->clock_out, 0, 5) : '-' }}</span>
                                @endif
                            </div>
                            @error('clock_in')
                                <p class="attendance-detail-error">{{ $message }}</p>
                            @enderror
                            @error('clock_out')
                                <p class="attendance-detail-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="attendance-detail-row">
                    <div class="attendance-detail-row-content">
                        <div class="attendance-detail-label">休憩</div>
                        <div class="attendance-detail-value">
                            <div class="attendance-detail-break-group">
                                @if($isEditable)
                                    @if($record->breaks->count() > 0)
                                        @foreach($record->breaks as $index => $break)
                                            <div class="attendance-detail-break-item">
                                                <input type="time" name="break_starts[]" value="{{ old('break_starts.'.$index, $break->break_start ? substr($break->break_start, 0, 5) : '') }}" class="attendance-detail-time-input">
                                                <span class="attendance-detail-time-separator">〜</span>
                                                <input type="time" name="break_ends[]" value="{{ old('break_ends.'.$index, $break->break_end ? substr($break->break_end, 0, 5) : '') }}" class="attendance-detail-time-input">
                                            </div>
                                        @endforeach
                                    @endif
                                    @php
                                        $nextIndex = $record->breaks->count() ?? 0;
                                    @endphp
                                    <div class="attendance-detail-break-item">
                                        <input type="time" name="break_starts[]" value="{{ old('break_starts.'.$nextIndex, '') }}" class="attendance-detail-time-input">
                                        <span class="attendance-detail-time-separator">〜</span>
                                        <input type="time" name="break_ends[]" value="{{ old('break_ends.'.$nextIndex, '') }}" class="attendance-detail-time-input">
                                    </div>
                                @else
                                    @if($record->breaks->count() > 0)
                                        @foreach($record->breaks as $break)
                                            <div class="attendance-detail-break-item">
                                                <span class="attendance-detail-time-display">{{ $break->break_start ? substr($break->break_start, 0, 5) : '-' }}</span>
                                                <span class="attendance-detail-time-separator">〜</span>
                                                <span class="attendance-detail-time-display">{{ $break->break_end ? substr($break->break_end, 0, 5) : '-' }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span>-</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($isEditable)
                    <div class="attendance-detail-row">
                        <div class="attendance-detail-row-content">
                            <div class="attendance-detail-label">休憩2</div>
                            <div class="attendance-detail-value">
                                <div class="attendance-detail-break-item">
                                    <input type="time" name="break_starts[]" value="" class="attendance-detail-time-input">
                                    <span class="attendance-detail-time-separator">〜</span>
                                    <input type="time" name="break_ends[]" value="" class="attendance-detail-time-input">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="attendance-detail-row">
                    <div class="attendance-detail-row-content">
                        <div class="attendance-detail-label">備考</div>
                        <div class="attendance-detail-value">
                            @if($isEditable)
                                <textarea name="notes" class="attendance-detail-notes-input" required>{{ old('notes', $record->notes ?? '') }}</textarea>
                                @error('notes')
                                    <p class="attendance-detail-error">{{ $message }}</p>
                                @enderror
                            @else
                                <div class="attendance-detail-notes-display">{{ $record->notes ?? '-' }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($hasPendingRequest)
            <div class="attendance-detail-pending-message">
                *承認待ちのため修正はできません。
            </div>
        @endif

        @if($isEditable)
                </form>
            <div class="attendance-detail-action">
                <button type="submit" form="attendance-detail-form" class="attendance-detail-submit-button">修正</button>
            </div>
        @endif
    </div>
</div>
@endsection
