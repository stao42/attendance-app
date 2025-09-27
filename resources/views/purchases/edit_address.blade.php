@extends('layouts.app')

@section('title', '住所の変更 - CoachTech')

@section('content')
<div class="address-edit-container">
    <h1 class="address-edit-title">住所の変更</h1>
    
    <form action="{{ route('purchases.update_address', $product) }}" method="POST" class="address-edit-form">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="postal_code" class="form-label">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" 
                   class="form-input" placeholder="1234567">
            @error('postal_code')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="address" class="form-label">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" 
                   class="form-input" placeholder="あいうえお">
            @error('address')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="building" class="form-label">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $user->building) }}" 
                   class="form-input">
            @error('building')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-update">更新する</button>
        </div>
    </form>
</div>

<style>
.address-edit-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 40px 20px;
}

.address-edit-title {
    font-size: 32px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 40px;
    text-align: center;
}

.address-edit-form {
    background: white;
}

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
    margin-top: 40px;
    text-align: center;
}

.btn-update {
    background-color: #FF0000;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 16px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.2s ease;
}

.btn-update:hover {
    background-color: #CC0000;
}

/* レスポンシブデザイン */
@media (max-width: 850px) {
    .address-edit-container {
        padding: 30px 15px;
    }
    
    .address-edit-title {
        font-size: 28px;
        margin-bottom: 30px;
    }
    
    .form-label {
        font-size: 16px;
    }
    
    .form-input {
        font-size: 15px;
        padding: 10px 14px;
    }
    
    .btn-update {
        font-size: 16px;
        padding: 14px;
        width: 100%;
    }
}

@media (max-width: 768px) {
    .address-edit-container {
        padding: 20px 10px;
    }
    
    .address-edit-title {
        font-size: 24px;
        margin-bottom: 25px;
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
    
    .form-actions {
        margin-top: 30px;
    }
    
    .btn-update {
        font-size: 15px;
        padding: 12px;
        width: 100%;
    }
}
</style>
@endsection
