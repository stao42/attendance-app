@extends('layouts.app')

@section('title', '管理画面')

@section('content')
<div class="card">
    <h2>管理画面</h2>
    
    <div class="grid grid-cols-3" style="margin-bottom: 32px;">
        <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); padding: 24px; border-radius: var(--radius-lg); color: white; box-shadow: var(--shadow-md);">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">総ユーザー数</div>
            <div style="font-size: 32px; font-weight: 700;">{{ $stats['total_users'] }}</div>
        </div>
        <div style="background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%); padding: 24px; border-radius: var(--radius-lg); color: white; box-shadow: var(--shadow-md);">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">本日出勤者</div>
            <div style="font-size: 32px; font-weight: 700;">{{ $stats['today_attendances'] }}</div>
        </div>
        <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); padding: 24px; border-radius: var(--radius-lg); color: white; box-shadow: var(--shadow-md);">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">本日完了者</div>
            <div style="font-size: 32px; font-weight: 700;">{{ $stats['today_completed'] }}</div>
        </div>
    </div>
    
    <div style="display: flex; gap: 12px; margin-bottom: 32px; flex-wrap: wrap;">
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">ユーザー一覧</a>
        <a href="{{ route('admin.attendance.status') }}" class="btn btn-secondary">勤怠状況</a>
    </div>
</div>

<div class="card">
    <h3>最近の勤怠記録</h3>
    
    @if($recentRecords->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ユーザー</th>
                        <th>日付</th>
                        <th>出勤時刻</th>
                        <th>退勤時刻</th>
                        <th>勤務時間</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRecords as $record)
                        <tr>
                            <td style="font-weight: 600;">{{ $record->user->name }}</td>
                            <td>{{ $record->date->format('Y/m/d') }}</td>
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
                            <td>
                                @if($record->work_time)
                                    <span style="color: var(--primary-color); font-weight: 600;">{{ $record->work_time }}</span>
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 48px 24px; color: var(--text-secondary);">
            <p style="font-size: 18px;">勤怠記録がありません。</p>
        </div>
    @endif
</div>
@endsection
