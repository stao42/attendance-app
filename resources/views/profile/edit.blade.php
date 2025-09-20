@extends('layouts.app')

@section('title', 'プロフィール設定 - CoachTech')

@section('content')
@if($user->is_first_login)
<div style="background-color: #FFF5F5; border: 1px solid #FFE5E5; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
    <h2 style="color: #FF5555; font-size: 18px; font-weight: 600; margin-bottom: 10px;">初回ログイン</h2>
    <p style="color: #333333; font-size: 16px; margin: 0;">プロフィール情報を設定してください。設定完了後、フリマアプリをご利用いただけます。</p>
</div>
@endif

<h1 style="font-size: 36px; font-weight: 700; color: #000000; margin-bottom: 30px;">プロフィール設定</h1>

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- プロフィール画像 -->
    <div style="margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 30px;">
            <div style="width: 150px; height: 150px; background-color: #D9D9D9; border-radius: 50%; overflow: hidden; position: relative;">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>
            <div>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;" onchange="previewProfileImage(this)">
                <label for="profile_image" class="btn btn-secondary" style="cursor: pointer;">画像を選択する</label>
            </div>
        </div>
        @error('profile_image')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <!-- ユーザー情報 -->
    <div class="form-group">
        <label for="name" class="form-label">ユーザー名</label>
        <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required>
        @error('name')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="postal_code" class="form-label">郵便番号</label>
        <input type="text" name="postal_code" id="postal_code" class="form-input" value="{{ old('postal_code', $user->postal_code) }}">
        @error('postal_code')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="address" class="form-label">住所</label>
        <input type="text" name="address" id="address" class="form-input" value="{{ old('address', $user->address) }}">
        @error('address')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="building" class="form-label">建物名</label>
        <input type="text" name="building" id="building" class="form-input" value="{{ old('building', $user->building) }}">
        @error('building')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <button type="submit" class="btn btn-primary" style="width: 680px; padding: 20px; font-size: 26px;">更新する</button>
    </div>
</form>

<script>
function previewProfileImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = input.parentElement.parentElement.querySelector('img');
            if (img) {
                img.src = e.target.result;
            } else {
                const div = input.parentElement.parentElement;
                div.innerHTML = '<img src="' + e.target.result + '" alt="プロフィール画像" style="width: 100%; height: 100%; object-fit: cover;">';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
