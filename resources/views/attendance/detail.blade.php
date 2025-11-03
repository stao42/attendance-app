@extends('layouts.app')

@section('title', '勤怠詳細')

@section('content')
<div style="background-color: #F0EFF2; min-height: calc(100vh - 80px); padding: 32px; margin: -24px;">
    <div style="max-width: 1512px; margin: 0 auto; background-color: #FFFFFF; border-radius: 12px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 32px; margin-bottom: 24px; color: #000000;">勤怠詳細</h2>

        @if($hasPendingRequest)
            <div style="padding: 16px; background-color: #FFF3CD; border: 1px solid #FFE69C; border-radius: 8px; margin-bottom: 24px;">
                <p style="font-family: 'Inter', sans-serif; font-size: 16px; color: #856404; margin: 0;">承認待ちのため修正はできません。</p>
            </div>
        @endif

        <!-- 基本情報 -->
        <div style="margin-bottom: 32px;">
            <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">基本情報</h3>
            <div style="display: grid; grid-template-columns: 200px 1fr; gap: 16px; padding: 16px; background-color: #F9F9F9; border-radius: 8px;">
                <div style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">名前</div>
                <div style="font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">{{ $record->user->name }}</div>

                <div style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; color: #000000;">日付</div>
                <div style="font-family: 'Inter', sans-serif; font-size: 16px; color: #000000;">{{ $record->date->format('Y年m月d日') }}</div>
            </div>
        </div>

        <!-- 修正申請フォーム -->
        @if(!$hasPendingRequest)
            <form method="POST" action="{{ route('attendance.request-correction', $record->id) }}" style="margin-bottom: 32px;">
                @csrf

                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">出勤・退勤</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">出勤時刻</label>
                        <input type="time" name="clock_in" value="{{ old('clock_in', $record->clock_in) }}" required style="width: 100%; padding: 12px; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">
                        @error('clock_in')
                            <p style="color: #DC3545; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">退勤時刻</label>
                        <input type="time" name="clock_out" value="{{ old('clock_out', $record->clock_out) }}" style="width: 100%; padding: 12px; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">
                        @error('clock_out')
                            <p style="color: #DC3545; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">休憩</h3>
                <div id="breaks-container" style="margin-bottom: 24px;">
                    @if($record->breaks->count() > 0)
                        @foreach($record->breaks as $index => $break)
                            <div class="break-row" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; margin-bottom: 16px; align-items: end;">
                                <div>
                                    <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">休憩開始</label>
                                    <input type="time" name="break_starts[]" value="{{ old('break_starts.'.$index, $break->break_start) }}" style="width: 100%; padding: 12px; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">
                                </div>
                                <div>
                                    <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">休憩終了</label>
                                    <input type="time" name="break_ends[]" value="{{ old('break_ends.'.$index, $break->break_end) }}" style="width: 100%; padding: 12px; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <!-- 追加用の空行 -->
                    <div class="break-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; align-items: end;">
                        <div>
                            <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">休憩開始</label>
                            <input type="time" name="break_starts[]" style="width: 100%; padding: 12px; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">
                        </div>
                        <div>
                            <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">休憩終了</label>
                            <input type="time" name="break_ends[]" style="width: 100%; padding: 12px; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">
                        </div>
                    </div>
                </div>

                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">備考</h3>
                <div style="margin-bottom: 24px;">
                    <textarea name="notes" required style="width: 100%; padding: 12px; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px; min-height: 120px;">{{ old('notes', $record->notes) }}</textarea>
                    @error('notes')
                        <p style="color: #DC3545; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; gap: 16px;">
                    <button type="submit" style="padding: 12px 32px; background-color: #000000; color: #FFFFFF; border: none; border-radius: 8px; cursor: pointer; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px;">修正申請</button>
                    <a href="{{ route('attendance.list') }}" style="padding: 12px 32px; background-color: #E0E0E0; color: #000000; text-decoration: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; display: inline-block;">戻る</a>
                </div>
            </form>
        @else
            <!-- 承認待ちの場合、表示のみ -->
            <div style="margin-bottom: 32px;">
                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">出勤・退勤</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">出勤時刻</label>
                        <div style="padding: 12px; background-color: #F9F9F9; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">{{ $record->clock_in ?? '-' }}</div>
                    </div>
                    <div>
                        <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">退勤時刻</label>
                        <div style="padding: 12px; background-color: #F9F9F9; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">{{ $record->clock_out ?? '-' }}</div>
                    </div>
                </div>

                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">休憩</h3>
                @if($record->breaks->count() > 0)
                    @foreach($record->breaks as $break)
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">休憩開始</label>
                                <div style="padding: 12px; background-color: #F9F9F9; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">{{ $break->break_start ?? '-' }}</div>
                            </div>
                            <div>
                                <label style="display: block; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: #000000;">休憩終了</label>
                                <div style="padding: 12px; background-color: #F9F9F9; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px;">{{ $break->break_end ?? '-' }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p style="font-family: 'Inter', sans-serif; font-size: 16px; color: #696969;">休憩記録がありません。</p>
                @endif

                <h3 style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #000000;">備考</h3>
                <div style="padding: 12px; background-color: #F9F9F9; border: 2px solid #E0E0E0; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 16px; min-height: 120px;">{{ $record->notes ?? '-' }}</div>

                <div style="margin-top: 24px;">
                    <a href="{{ route('attendance.list') }}" style="padding: 12px 32px; background-color: #E0E0E0; color: #000000; text-decoration: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 16px; display: inline-block;">戻る</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

