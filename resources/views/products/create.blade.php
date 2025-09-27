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

    <!-- 商品の詳細 -->
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 30px; font-weight: 700; color: #5F5F5F; margin-bottom: 20px;">商品の詳細</h2>
        
        <div class="form-group">
            <label class="form-label">カテゴリー</label>
            <div class="category-grid">
                @php
                    $categoryOrder = [1, 5, 4, 2, 29, 6, 7, 8, 30, 31, 11, 32, 33, 34]; // 画像の順序に合わせて配置
                    $orderedCategories = collect($categories)->sortBy(function($category) use ($categoryOrder) {
                        return array_search($category->id, $categoryOrder);
                    });
                @endphp
                @foreach($orderedCategories as $category)
                    <label class="category-option">
                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" 
                               {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                               class="category-checkbox">
                        <span class="category-tag">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('category_ids')
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
    </div>

    <!-- 商品名と説明 -->
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 30px; font-weight: 700; color: #5F5F5F; margin-bottom: 20px;">商品名と説明</h2>
        
        <div class="form-group">
            <label for="name" class="form-label">商品名</label>
            <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required autocomplete="off">
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
            <textarea name="description" id="description" class="form-textarea" rows="5" required autocomplete="off">{{ old('description') }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- 販売価格 -->
    <div style="margin-bottom: 30px;">
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

<style>
.category-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.category-option {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    border: 2px solid #FF5655;
    border-radius: 200px;
    background-color: rgba(217, 217, 217, 0);
    transition: all 0.3s ease;
    min-height: 32px;
    flex-shrink: 0;
}

.category-option:hover {
    background-color: rgba(255, 240, 240, 0.5);
}

.category-checkbox {
    display: none;
}

.category-tag {
    display: inline-block;
    text-align: center;
    padding: 8px 16px;
    border-radius: 200px;
    background-color: rgba(217, 217, 217, 0);
    color: #FF5655;
    border: 2px solid #FF5655;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.3s ease;
    line-height: 1.2;
    white-space: nowrap;
}

.category-option:has(.category-checkbox:checked) {
    background-color: #FF5655;
}

.category-option:has(.category-checkbox:checked) .category-tag {
    background-color: #FF5655;
    color: #FFFFFF;
    border-color: #FF5655;
}

/* レスポンシブデザイン対応 */
@media (max-width: 1540px) {
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
}

@media (max-width: 850px) {
    .container {
        padding: 0 20px;
    }

    h1 {
        font-size: 28px;
        margin-bottom: 25px;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .form-label {
        font-size: 20px;
        margin-bottom: 8px;
    }

    .form-input, .form-select {
        height: 40px;
        font-size: 16px;
        padding: 0 12px;
    }

    .form-textarea {
        min-height: 100px;
        font-size: 16px;
        padding: 12px;
    }

    .category-grid {
        gap: 10px;
        margin-bottom: 15px;
    }

    .category-tag {
        font-size: 14px;
        padding: 6px 12px;
    }

    .btn {
        width: 100%;
        max-width: 600px;
        padding: 18px;
        font-size: 22px;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 0 15px;
    }

    h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    h2 {
        font-size: 20px;
        margin-bottom: 12px;
    }

    .form-label {
        font-size: 18px;
        margin-bottom: 6px;
    }

    .form-input, .form-select {
        height: 38px;
        font-size: 15px;
        padding: 0 10px;
    }

    .form-textarea {
        min-height: 90px;
        font-size: 15px;
        padding: 10px;
    }

    .category-grid {
        gap: 8px;
        margin-bottom: 12px;
    }

    .category-tag {
        font-size: 13px;
        padding: 5px 10px;
    }

    .btn {
        width: 100%;
        padding: 16px;
        font-size: 20px;
    }
}
</style>
@endsection
