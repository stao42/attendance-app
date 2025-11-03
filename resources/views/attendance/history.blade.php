@extends('layouts.app')

@section('title', '勤怠履歴')

@section('content')
<div class="card">
    <h2>勤怠履歴</h2>
    
    <form method="GET" action="{{ route('attendance.history') }}" style="margin-bottom: 24px;">
        <div class="form-group" style="max-width: 300px;">
            <label for="month">月を選択</label>
            <input type="month" id="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
        </div>
    </form>
    
    @if($records->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>日付</th>
                        <th>出勤時刻</th>
                        <th>退勤時刻</th>
                        <th>休憩時間</th>
                        <th>勤務時間</th>
                        <th>備考</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td style="font-weight: 600;">{{ $record->date->format('Y年m月d日') }}</td>
                            <td>
                                @if($record->clock_in)
                                    <span style="color: var(--success-color); font-weight: 600;">{{ $record->clock_in }}</span>
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td>
                                @if($record->clock_out)
                                    <span style="color: var(--danger-color); font-weight: 600;">{{ $record->clock_out }}</span>
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td>{{ $record->break_time ?? '00:00:00' }}</td>
                            <td>
                                @if($record->work_time)
                                    <span style="color: var(--primary-color); font-weight: 600;">{{ $record->work_time }}</span>
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary);">{{ $record->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $records->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 48px 24px; color: var(--text-secondary);">
            <p style="font-size: 18px; margin-bottom: 8px;">選択した月の勤怠記録がありません。</p>
            <p style="font-size: 14px;">別の月を選択してください。</p>
        </div>
    @endif
    
    <div style="text-align: center; margin-top: 24px;">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">ダッシュボードに戻る</a>
    </div>
</div>
@endsection
