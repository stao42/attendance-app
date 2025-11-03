@extends('layouts.app')

@section('title', '勤怠状況')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem;">全ユーザーの勤怠状況</h2>
    
    <form method="GET" action="{{ route('admin.attendance.status') }}" style="margin-bottom: 2rem;">
        <div class="form-group" style="max-width: 300px;">
            <label for="date">日付を選択</label>
            <input type="date" id="date" name="date" value="{{ $date }}" onchange="this.form.submit()">
        </div>
    </form>
    
    <h3 style="margin-bottom: 1rem; margin-top: 2rem;">勤怠記録があるユーザー</h3>
    
    @if($records->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ユーザー名</th>
                    <th>出勤時刻</th>
                    <th>退勤時刻</th>
                    <th>勤務時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    <tr>
                        <td>{{ $record->user->name }}</td>
                        <td>{{ $record->clock_in ?? '-' }}</td>
                        <td>{{ $record->clock_out ?? '-' }}</td>
                        <td>{{ $record->work_time ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #999; padding: 2rem;">選択した日の勤怠記録がありません。</p>
    @endif
    
    @if($usersWithoutRecord->count() > 0)
        <h3 style="margin-bottom: 1rem; margin-top: 2rem;">勤怠記録がないユーザー</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ユーザー名</th>
                    <th>メールアドレス</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usersWithoutRecord as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    <div style="text-align: center; margin-top: 1rem;">
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">管理画面トップに戻る</a>
    </div>
</div>
@endsection
