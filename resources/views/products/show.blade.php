@extends('layouts.app')

@section('title', $product->name . ' - CoachTech')

@section('content')
<div class="product-detail-container">
    <!-- 商品画像エリア -->
    <div class="product-image-section">
        <div class="product-image-wrapper">
            @if($product->image)
                @if(str_starts_with($product->image, 'http'))
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                @endif
            @else
                <div class="product-image-placeholder">商品画像</div>
            @endif
        </div>
    </div>

    <!-- 商品情報エリア -->
    <div class="product-info-section">
        <!-- 商品タイトルと価格 -->
        <div class="product-header">
            <h1 class="product-title">{{ $product->name }}</h1>
            @if($product->brand)
                <p class="product-brand">{{ $product->brand }}</p>
            @endif
            <p class="product-price">{{ $product->formatted_price }}</p>
            
            <!-- アクションアイコン -->
            <div class="product-actions">
                <div class="action-item">
                    @auth
                        @if($product->isFavoritedBy(auth()->user()))
                            <form action="{{ route('favorites.destroy', $product) }}" method="POST" class="favorite-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="favorite-btn favorited">❤️</button>
                            </form>
                        @else
                            <form action="{{ route('favorites.store', $product) }}" method="POST" class="favorite-form">
                                @csrf
                                <button type="submit" class="favorite-btn">🤍</button>
                            </form>
                        @endif
                    @else
                        <span class="favorite-btn disabled">🤍</span>
                    @endauth
                    <span class="action-count">{{ $product->favorites->count() }}</span>
                </div>
                <div class="action-item">
                    <img src="{{ asset('images/comment-icon.png') }}" alt="コメント" class="comment-icon">
                    <span class="action-count">{{ $product->comments->count() }}</span>
                </div>
            </div>
        </div>

        <!-- 購入ボタン -->
        @if($product->user_id !== auth()->id())
            @if($product->is_sold)
                <div class="purchase-section">
                    <button disabled class="purchase-btn sold-out">売り切れ</button>
                </div>
            @else
                <div class="purchase-section">
                    <a href="{{ route('purchases.create', $product) }}" class="purchase-btn">購入手続きへ</a>
                </div>
            @endif
        @endif

        <!-- 商品の情報 -->
        <div class="product-details">
            <h2 class="section-title">商品の情報</h2>
            
            <!-- カテゴリー -->
            <div class="detail-item">
                <h3 class="detail-label">カテゴリー</h3>
                <div class="category-tags">
                    <span class="category-tag">{{ $product->category->name }}</span>
                </div>
            </div>

            <!-- 商品の状態 -->
            <div class="detail-item">
                <h3 class="detail-label">商品の状態</h3>
                <p class="detail-value">{{ $product->condition_text }}</p>
            </div>
        </div>

        <!-- 商品説明 -->
        <div class="product-description">
            <h2 class="section-title">商品説明</h2>
            <div class="description-text">{{ $product->description }}</div>
        </div>

        <!-- コメントセクション -->
        <div class="comments-section">
            <h2 class="section-title">コメント({{ $product->comments->count() }})</h2>
            
            <!-- コメント一覧 -->
            @foreach($product->comments as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        <div class="comment-avatar"></div>
                        <span class="comment-author">{{ $comment->user->name }}</span>
                    </div>
                    <p class="comment-content">{{ $comment->content }}</p>
                </div>
            @endforeach

            <!-- コメント投稿フォーム -->
            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="comment-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="form-group">
                        <label for="content" class="form-label">商品へのコメント</label>
                        <textarea name="content" id="content" class="form-textarea" rows="4" placeholder="コメントを入力してください" required></textarea>
                        @error('content')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">コメントを送信する</button>
                </form>
            @endauth
        </div>
    </div>
</div>

<style>
.product-detail-container {
    display: flex;
    gap: 40px;
    margin-top: 20px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.product-image-section {
    flex: 1;
}

.product-image-wrapper {
    width: 100%;
    height: 600px;
    background-color: #D9D9D9;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #000000;
    font-size: 40px;
    font-weight: 400;
}

.product-info-section {
    flex: 1;
}

.product-header {
    margin-bottom: 30px;
}

.product-title {
    font-size: 45px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 10px;
    line-height: 1.2;
}

.product-brand {
    font-size: 20px;
    font-weight: 400;
    color: #000000;
    margin-bottom: 10px;
}

.product-price {
    font-size: 36px;
    font-weight: 400;
    color: #000000;
    margin-bottom: 20px;
}

.product-actions {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.favorite-form {
    display: inline;
}

.favorite-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 24px;
    color: #ccc;
    transition: color 0.3s ease;
}

.favorite-btn.favorited {
    color: #ff6b6b;
}

.favorite-btn.disabled {
    cursor: default;
}

.comment-icon {
    width: 40px;
    height: 40px;
}

.action-count {
    font-size: 18px;
    font-weight: 700;
    color: #000000;
}

.purchase-section {
    margin-bottom: 30px;
}

.purchase-btn {
    width: 100%;
    padding: 20px;
    font-size: 30px;
    background-color: #FF5555;
    color: #FFFFFF;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: block;
    text-align: center;
    transition: background-color 0.3s ease;
}

.purchase-btn:hover {
    background-color: #E04444;
}

.purchase-btn.sold-out {
    background-color: #ccc;
    color: #666;
    cursor: not-allowed;
}

.product-details {
    margin-bottom: 30px;
}

.section-title {
    font-size: 36px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 20px;
}

.detail-item {
    margin-bottom: 20px;
}

.detail-label {
    font-size: 24px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 10px;
}

.category-tags {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.category-tag {
    background-color: #D9D9D9;
    color: #000000;
    padding: 3px 20px;
    border-radius: 15px;
    font-size: 20px;
    font-weight: 400;
}

.detail-value {
    font-size: 20px;
    font-weight: 400;
    color: #000000;
}

.product-description {
    margin-bottom: 30px;
}

.description-text {
    white-space: pre-line;
    font-size: 24px;
    font-weight: 400;
    color: #000000;
    line-height: 1.5;
}

.comments-section {
    margin-bottom: 30px;
}

.comment-item {
    margin-bottom: 20px;
    padding: 20px;
    background-color: #E5E5E5;
    border-radius: 5px;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
}

.comment-avatar {
    width: 70px;
    height: 70px;
    background-color: #D9D9D9;
    border-radius: 50%;
}

.comment-author {
    font-size: 30px;
    font-weight: 700;
    color: #000000;
}

.comment-content {
    font-size: 20px;
    font-weight: 300;
    color: #000000;
    line-height: 1.5;
}

.comment-form {
    margin-top: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 10px;
}

.form-textarea {
    width: 100%;
    padding: 15px;
    border: 1px solid #D9D9D9;
    border-radius: 4px;
    font-size: 18px;
    resize: vertical;
}

.error-message {
    color: #FF5555;
    font-size: 16px;
    margin-top: 5px;
}

.btn {
    padding: 15px 30px;
    font-size: 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background-color: #FF5555;
    color: #FFFFFF;
}

.btn-primary:hover {
    background-color: #E04444;
}
</style>
@endsection
