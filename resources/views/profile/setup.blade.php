@extends('layouts.app')

@section('content')
<div class="container">
    <div class="profile-setup">
        <div class="setup-card">
            <h1 class="setup-title">プロフィール設定</h1>
            <p class="setup-subtitle">メール認証が完了しました！プロフィール情報を設定してください。</p>

            <form method="POST" action="{{ route('profile.setup') }}" class="setup-form" enctype="multipart/form-data">
                @csrf

                <!-- プロフィール画像 -->
                <div class="form-group">
                    <label for="profile_image" class="form-label">プロフィール画像</label>
                    <div class="image-upload">
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="image-input">
                        <label for="profile_image" class="image-label">
                            <div class="image-preview">
                                <div class="image-placeholder">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="7" r="4" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span>画像を選択</span>
                                </div>
                                <img id="image-preview" style="display: none;">
                            </div>
                        </label>
                    </div>
                    @error('profile_image')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 郵便番号 -->
                <div class="form-group">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', auth()->user()->postal_code) }}"
                           class="form-input" placeholder="例: 123-4567">
                    @error('postal_code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 住所 -->
                <div class="form-group">
                    <label for="address" class="form-label">住所</label>
                    <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}"
                           class="form-input" placeholder="例: 東京都渋谷区恵比寿1-1-1">
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 建物名 -->
                <div class="form-group">
                    <label for="building" class="form-label">建物名・部屋番号（任意）</label>
                    <input type="text" id="building" name="building" value="{{ old('building', auth()->user()->building) }}"
                           class="form-input" placeholder="例: 恵比寿ビル 101号室">
                    @error('building')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        設定を完了する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.profile-setup {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.setup-card {
    background: #FFFFFF;
    border-radius: 8px;
    padding: 40px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
}

.setup-title {
    font-size: 32px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 10px;
    text-align: center;
}

.setup-subtitle {
    font-size: 16px;
    color: #666666;
    text-align: center;
    margin-bottom: 40px;
    line-height: 1.5;
}

.setup-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-size: 18px;
    font-weight: 600;
    color: #000000;
}

.form-input {
    height: 48px;
    padding: 0 16px;
    border: 2px solid #CCCCCC;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #FF5555;
}

.image-upload {
    display: flex;
    justify-content: center;
}

.image-input {
    display: none;
}

.image-label {
    cursor: pointer;
    display: block;
}

.image-preview {
    width: 120px;
    height: 120px;
    border: 2px dashed #CCCCCC;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: border-color 0.3s ease;
}

.image-preview:hover {
    border-color: #FF5555;
}

.image-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #666666;
}

.image-placeholder span {
    font-size: 14px;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
}

.error-message {
    color: #FF5555;
    font-size: 14px;
    margin-top: 5px;
}

.form-actions {
    margin-top: 20px;
}

.submit-btn {
    width: 100%;
    height: 56px;
    background-color: #FF5555;
    color: #FFFFFF;
    border: none;
    border-radius: 4px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #E04444;
}

/* レスポンシブデザイン */
@media (max-width: 768px) {
    .setup-card {
        padding: 30px 20px;
    }

    .setup-title {
        font-size: 28px;
    }

    .setup-subtitle {
        font-size: 15px;
    }

    .form-label {
        font-size: 16px;
    }

    .form-input {
        height: 44px;
        font-size: 15px;
    }

    .image-preview {
        width: 100px;
        height: 100px;
    }

    .submit-btn {
        height: 50px;
        font-size: 16px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('profile_image');
    const imagePreview = document.getElementById('image-preview');
    const imagePlaceholder = document.querySelector('.image-placeholder');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                imagePlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection
