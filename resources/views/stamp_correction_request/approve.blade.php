@extends('layouts.app')

@section('title', '修正申請承認')

@section('content')
<div style="background-color: #F0EFF2; min-height: calc(100vh - 80px); padding: 32px; margin: -24px;">
    <div style="max-width: 1512px; margin: 0 auto; background-color: #FFFFFF; border-radius: 12px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 32px; margin-bottom: 24px; color: #000000;">修正申請承認</h2>

        <!-- 基本情報 -->
        <div style="margin-bottom: 32px;">
            <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">基本情報</h3>
            <div style="display: grid; grid-template-columns: 200px 1fr; gap: 16px; padding: 16px; background-color: #F9F9F9; border-radius: 8px;">
                <div style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">申請者</div>
                <div style="font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">{{ $request->user->name }}</div>

                <div style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">日付</div>
                <div style="font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">{{ $request->attendanceRecord->date->format('Y年m月d日') }}</div>
            </div>
        </div>

        @if($request->status === 'pending')
            <!-- 現在の値と申請内容の比較 -->
            <div style="margin-bottom: 32px;">
                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">現在の値</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">出勤時刻</label>
                        <div style="padding: 12px; background-color: #F9F9F9; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">{{ $request->attendanceRecord->clock_in ?? '-' }}</div>
                    </div>
                    <div>
                        <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">退勤時刻</label>
                        <div style="padding: 12px; background-color: #F9F9F9; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">{{ $request->attendanceRecord->clock_out ?? '-' }}</div>
                    </div>
                </div>

                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">申請内容</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">出勤時刻</label>
                        <div style="padding: 12px; background-color: #E8F5E9; border: 2px solid #4CAF50; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">{{ $request->requested_clock_in ?? '-' }}</div>
                    </div>
                    <div>
                        <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">退勤時刻</label>
                        <div style="padding: 12px; background-color: #E8F5E9; border: 2px solid #4CAF50; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">{{ $request->requested_clock_out ?? '-' }}</div>
                    </div>
                </div>

                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">備考</h3>
                <div style="margin-bottom: 24px;">
                    <div style="padding: 12px; background-color: #E8F5E9; border: 2px solid #4CAF50; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px; min-height: 120px;">{{ $request->requested_notes ?? '-' }}</div>
                </div>

                <!-- 承認フォーム -->
                <form method="POST" action="{{ route('admin.stamp_correction_request.approve', $request->id) }}" style="margin-top: 32px;">
                    @csrf
                    <div style="display: flex; gap: 16px;">
                        <button type="submit" style="padding: 12px 32px; background-color: #000000; color: #FFFFFF; border: none; border-radius: 8px; cursor: pointer; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px;">承認</button>
                        <a href="{{ route('admin.stamp_correction_request.list') }}" style="padding: 12px 32px; background-color: #E0E0E0; color: #000000; text-decoration: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; display: inline-block;">戻る</a>
                    </div>
                </form>
        @else
            <div style="padding: 16px; background-color: #D4EDDA; border: 1px solid #C3E6CB; border-radius: 8px; margin-bottom: 24px;">
                <p style="font-family: 'Inter', sans-serif; font-size: 16px; color: #155724; margin: 0;">この申請は既に承認済みです。</p>
            </div>

            <div style="margin-top: 32px;">
                <a href="{{ route('admin.stamp_correction_request.list') }}" style="padding: 12px 32px; background-color: #E0E0E0; color: #000000; text-decoration: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; display: inline-block;">戻る</a>
            </div>
        @endif
    </div>
</div>
@endsection

