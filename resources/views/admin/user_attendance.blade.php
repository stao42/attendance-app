@extends('layouts.app')

@section('title', 'ユーザー勤怠履歴')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem;">{{ $user->name }} さんの勤怠履歴</h2>
    
    <form method="GET" action="{{ route('admin.user.attendance', $user->id) }}" style="margin-bottom: 2rem;">
        <div class="form-group" style="max-width: 300px;">
            <label for="month">月を選択</label>
            <input type="month" id="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
        </div>
    </form>
    
    @if($records->count() > 0)
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
                        <td>{{ $record->date->format('Y年m月d日') }}</td>
                        <td>{{ $record->clock_in ?? '-' }}</td>
                        <td>{{ $record->clock_out ?? '-' }}</td>
                        <td>{{ $record->break_time ?? '00:00:00' }}</td>
                        <td>{{ $record->work_time ?? '-' }}</td>
                        <td>{{ $record->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 1rem;">
            {{ $records->links() }}
        </div>
    @else
        <p style="text-align: center; color: #999; padding: 2rem;">選択した月の勤怠記録がありません。</p>
    @endif
    
    <div style="text-align: center; margin-top: 1rem;">
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">ユーザー一覧に戻る</a>
    </div>
</div>
@endsection
