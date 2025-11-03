@extends('layouts.app')

@section('title', '勤怠一覧')

@section('content')
<div style="background-color: #F0EFF2; min-height: calc(100vh - 80px); padding: 32px; margin: -24px;">
    <div style="max-width: 1512px; margin: 0 auto; background-color: #FFFFFF; border-radius: 12px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 32px; margin-bottom: 24px; color: #000000;">勤怠一覧</h2>

        <!-- 月選択 -->
        <div style="margin-bottom: 32px; display: flex; align-items: center; gap: 16px;">
            <form method="GET" action="{{ route('attendance.list') }}" style="display: flex; align-items: center; gap: 16px;">
                <button type="submit" name="month" value="{{ $prevMonth }}" style="padding: 8px 16px; background-color: #000000; color: #FFFFFF; border: none; border-radius: 8px; cursor: pointer; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px;">前月</button>
                <span style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; color: #000000;">{{ Carbon\Carbon::parse($month)->format('Y年m月') }}</span>
                <button type="submit" name="month" value="{{ $nextMonth }}" style="padding: 8px 16px; background-color: #000000; color: #FFFFFF; border: none; border-radius: 8px; cursor: pointer; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px;">翌月</button>
            </form>
        </div>

        @if($records->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #F5F5F5; border-bottom: 2px solid #E0E0E0;">
                            <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">日付</th>
                            <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">出勤時刻</th>
                            <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">退勤時刻</th>
                            <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">休憩時間</th>
                            <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">勤務時間</th>
                            <th style="padding: 16px; text-align: left; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr style="border-bottom: 1px solid #E0E0E0;">
                                <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">{{ $record->date->format('Y年m月d日') }}</td>
                                <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                    {{ $record->clock_in ?? '-' }}
                                </td>
                                <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                    {{ $record->clock_out ?? '-' }}
                                </td>
                                <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                    {{ $record->break_time ?? '00:00:00' }}
                                </td>
                                <td style="padding: 16px; font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">
                                    {{ $record->work_time ?? '-' }}
                                </td>
                                <td style="padding: 16px;">
                                    <a href="{{ route('attendance.detail', $record->id) }}" style="padding: 8px 16px; background-color: #000000; color: #FFFFFF; text-decoration: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 14px;">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 48px 24px; color: #696969;">
                <p style="font-family: 'Inter', sans-serif; font-size: 18px; margin-bottom: 8px;">選択した月の勤怠記録がありません。</p>
            </div>
        @endif
    </div>
</div>
@endsection

