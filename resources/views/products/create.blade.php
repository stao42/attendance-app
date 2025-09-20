@extends('layouts.app')

@section('title', '商品の出品 - CoachTech')

@section('content')
<h1 style="font-size: 36px; font-weight: 700; color: #000000; margin-bottom: 30px;">商品の出品</h1>

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- 商品画像 -->
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 30px; font-weight: 700; color: #000000; margin-bottom: 20px;">商品画像</h2>
        <div style="border: 2px dashed #5F5F5F; border-radius: 4px; padding: 40px; text-align: center; margin-bottom: 20px;">
            <input type="file" name="image" id="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
            <label for="image" style="cursor: pointer; display: block;">
                <div style="background-color: #D9D9D9; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                    <p style="font-size: 16px; font-weight: 700; color: #FF5655;">画像を選択する</p>
                </div>
            </label>
            <div id="image-preview" style="display: none;">
                <img id="preview-img" src="" alt="プレビュー" style="max-width: 200px; max-height: 200px; border-radius: 4px;">
            </div>
        </div>
        @error('image')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <!-- 商品名と説明 -->
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 30px; font-weight: 700; color: #5F5F5F; margin-bottom: 20px;">商品名と説明</h2>
        
        <div class="form-group">
            <label for="name" class="form-label">商品名</label>
            <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="brand" class="form-label">ブランド名</label>
            <input type="text" name="brand" id="brand" class="form-input" value="{{ old('brand') }}">
            @error('brand')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">商品の説明</label>
            <textarea name="description" id="description" class="form-textarea" rows="5" required>{{ old('description') }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- 商品の詳細 -->
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 30px; font-weight: 700; color: #5F5F5F; margin-bottom: 20px;">商品の詳細</h2>
        
        <div class="form-group">
            <label for="category_id" class="form-label">カテゴリー</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">選択してください</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="condition" class="form-label">商品の状態</label>
            <select name="condition" id="condition" class="form-select" required>
                <option value="">選択してください</option>
                <option value="excellent" {{ old('condition') == 'excellent' ? 'selected' : '' }}>良好</option>
                <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>状態が悪い</option>
            </select>
            @error('condition')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="price" class="form-label">販売価格</label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 24px; font-weight: 700; color: #000000;">¥</span>
                <input type="number" name="price" id="price" class="form-input" value="{{ old('price') }}" min="1" required>
            </div>
            @error('price')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <button type="submit" class="btn btn-primary" style="width: 680px; padding: 20px; font-size: 26px;">出品する</button>
    </div>
</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
