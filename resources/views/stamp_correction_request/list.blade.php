@extends('layouts.app')

@section('title', '申請一覧')

@section('content')
<div style="background-color: #F0EFF2; min-height: calc(100vh - 80px); padding: 32px; margin: -24px;">
    <div style="max-width: 1512px; margin: 0 auto; background-color: #FFFFFF; border-radius: 12px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 32px; margin-bottom: 24px; color: #000000;">申請一覧</h2>

        <!-- 承認待ち -->
        <div style="margin-bottom: 48px;">
            <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">承認待ち</h3>

            @if($pendingRequests->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #F5F5F5; border-bottom: 2px solid #E0E0E0;">
                                <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">日付</th>
                                <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">申請日時</th>
                                <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">詳細</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $request)
                                <tr style="border-bottom: 1px solid #E0E0E0;">
                                    <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                        {{ $request->attendanceRecord->date->format('Y年m月d日') }}
                                    </td>
                                    <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                        {{ $request->created_at->format('Y年m月d日 H:i') }}
                                    </td>
                                    <td style="padding: 16px;">
                                        <a href="{{ route('attendance.detail', $request->attendance_record_id) }}" style="padding: 8px 16px; background-color: #000000; color: #FFFFFF; text-decoration: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 14px;">詳細</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="font-family: 'Inter', sans-serif; font-size: 16px; color: #696969; padding: 24px;">承認待ちの申請がありません。</p>
            @endif
        </div>

        <!-- 承認済み -->
        <div>
            <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">承認済み</h3>

            @if($approvedRequests->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #F5F5F5; border-bottom: 2px solid #E0E0E0;">
                                <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">日付</th>
                                <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">申請日時</th>
                                <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">承認日時</th>
                                <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">詳細</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedRequests as $request)
                                <tr style="border-bottom: 1px solid #E0E0E0;">
                                    <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                        {{ $request->attendanceRecord->date->format('Y年m月d日') }}
                                    </td>
                                    <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                        {{ $request->created_at->format('Y年m月d日 H:i') }}
                                    </td>
                                    <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                        {{ $request->approved_at ? $request->approved_at->format('Y年m月d日 H:i') : '-' }}
                                    </td>
                                    <td style="padding: 16px;">
                                        <a href="{{ route('attendance.detail', $request->attendance_record_id) }}" style="padding: 8px 16px; background-color: #000000; color: #FFFFFF; text-decoration: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 14px;">詳細</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="font-family: 'Inter', sans-serif; font-size: 16px; color: #696969; padding: 24px;">承認済みの申請がありません。</p>
            @endif
        </div>

        <div style="margin-top: 32px;">
            <a href="{{ route('attendance.index') }}" style="padding: 12px 32px; background-color: #E0E0E0; color: #000000; text-decoration: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; display: inline-block;">戻る</a>
        </div>
    </div>
</div>
@endsection

