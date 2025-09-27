@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'プロフィール設定 - CoachTech')

@section('content')
@if($user->is_first_login)
<div class="first-login-notice">
    <h2>初回ログイン</h2>
    <p>プロフィール情報を設定してください。設定完了後、フリマアプリをご利用いただけます。</p>
</div>
@endif

<div class="profile-edit-container">
    <h1 class="profile-edit-title">プロフィール設定</h1>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit-form">
        @csrf
        @method('PUT')
        
        <!-- プロフィール画像 -->
        <div class="profile-image-section">
            <div class="profile-image-container">
                @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                    @php
                        $imageUrl = Storage::disk('public')->url($user->profile_image);
                    @endphp
                    <img src="{{ $imageUrl }}" alt="プロフィール画像" class="profile-image" onerror="this.style.display='none';">
                    <!-- デバッグ情報 -->
                    <!-- Profile image path: {{ $user->profile_image }} -->
                    <!-- Storage URL: {{ $imageUrl }} -->
                    <!-- File exists: {{ Storage::disk('public')->exists($user->profile_image) ? 'yes' : 'no' }} -->
                @endif
            </div>
            <div class="image-select-container">
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="file-input" onchange="previewProfileImage(this)">
                <label for="profile_image" class="image-select-btn">画像を選択する</label>
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

        <div class="form-actions">
            <button type="submit" class="update-btn">更新する</button>
        </div>
    </form>
</div>

<style>
/* 初回ログイン通知 */
.first-login-notice {
    background-color: #FFF5F5;
    border: 1px solid #FFE5E5;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.first-login-notice h2 {
    color: #FF5555;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}

.first-login-notice p {
    color: #333333;
    font-size: 16px;
    margin: 0;
}

/* プロフィール編集コンテナ */
.profile-edit-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px 20px 20px;
}

.profile-edit-title {
    font-size: 32px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 30px;
    text-align: center;
}

/* プロフィール画像セクション */
.profile-image-section {
    display: flex;
    align-items: center;
    gap: 30px;
    margin-bottom: 40px;
}

.profile-image-container {
    width: 150px;
    height: 150px;
    background-color: #D9D9D9;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-select-container {
    flex-shrink: 0;
}

.file-input {
    display: none;
}

.image-select-btn {
    display: inline-block;
    padding: 12px 24px;
    background-color: #FFFFFF;
    color: #FF5555;
    border: 2px solid #FF5555;
    border-radius: 6px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.image-select-btn:hover {
    background-color: #FF5555;
    color: #FFFFFF;
}

/* フォーム要素 */
.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    font-size: 18px;
    font-weight: 600;
    color: #000000;
    margin-bottom: 8px;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #E0E0E0;
    border-radius: 6px;
    font-size: 16px;
    background-color: white;
    box-sizing: border-box;
}

.form-input:focus {
    outline: none;
    border-color: #0066CC;
    box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.1);
}

.error-message {
    color: #FF0000;
    font-size: 14px;
    margin-top: 5px;
}

.form-actions {
    text-align: center;
    margin-top: 40px;
}

.update-btn {
    background-color: #FF5555;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 16px;
    font-size: 20px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.2s ease;
}

.update-btn:hover {
    background-color: #CC4444;
}

/* レスポンシブデザイン */
@media (max-width: 850px) {
    .profile-edit-container {
        padding: 30px 15px 15px 15px;
    }
    
    .profile-edit-title {
        font-size: 28px;
        margin-bottom: 25px;
    }
    
    .profile-image-section {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .profile-image-container {
        width: 120px;
        height: 120px;
    }
    
    .form-label {
        font-size: 16px;
    }
    
    .form-input {
        font-size: 15px;
        padding: 10px 14px;
    }
    
    .image-select-btn {
        font-size: 15px;
        padding: 10px 20px;
    }
    
    .update-btn {
        font-size: 18px;
        padding: 14px;
        width: 100%;
    }
}

@media (max-width: 768px) {
    .profile-edit-container {
        padding: 25px 10px 10px 10px;
    }
    
    .profile-edit-title {
        font-size: 24px;
        margin-bottom: 20px;
    }
    
    .profile-image-container {
        width: 100px;
        height: 100px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-size: 15px;
        margin-bottom: 6px;
    }
    
    .form-input {
        font-size: 14px;
        padding: 8px 12px;
    }
    
    .image-select-btn {
        font-size: 14px;
        padding: 8px 16px;
    }
    
    .update-btn {
        font-size: 16px;
        padding: 12px;
        width: 100%;
    }
}
</style>

<script>
function previewProfileImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = input.parentElement.parentElement.querySelector('img');
            if (img) {
                img.src = e.target.result;
            } else {
                const container = input.parentElement.parentElement.querySelector('.profile-image-container');
                container.innerHTML = '<img src="' + e.target.result + '" alt="プロフィール画像" class="profile-image">';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
