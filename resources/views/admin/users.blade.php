@extends('layouts.app')

@section('title', 'ユーザー一覧')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem;">ユーザー一覧</h2>
    
    @if($users->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>管理者</th>
                    <th>勤怠記録数</th>
                    <th>登録日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->is_admin ? 'はい' : 'いいえ' }}</td>
                        <td>{{ $user->attendance_records_count }}</td>
                        <td>{{ $user->created_at->format('Y/m/d') }}</td>
                        <td>
                            <a href="{{ route('admin.user.attendance', $user->id) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">勤怠履歴</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 1rem;">
            {{ $users->links() }}
        </div>
    @else
        <p style="text-align: center; color: #999; padding: 2rem;">ユーザーが登録されていません。</p>
    @endif
    
    <div style="text-align: center; margin-top: 1rem;">
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">管理画面トップに戻る</a>
    </div>
</div>
@endsection
