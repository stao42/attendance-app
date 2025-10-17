@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'プロフィール - CoachTech')

@section('content')
<div style="margin-top: 20px;">
    <!-- ユーザー情報 -->
    <div class="profile-section">
        <div class="profile-image-container">
            @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="profile-image">
            @else
                <div class="profile-image-placeholder">プロフィール画像</div>
            @endif
        </div>
        <div class="profile-info">
            <h1 class="profile-name">{{ $user->name }}</h1>
        </div>
        <div class="profile-actions">
            <a href="{{ route('profile.edit') }}" class="edit-profile-btn">プロフィールを編集</a>
        </div>
    </div>

    <!-- 出品した商品 -->
    <div style="margin-bottom: 30px;">
        <div style="display: flex; gap: 20px; margin-bottom: 20px; border-bottom: 2px solid #5F5F5F; padding-bottom: 10px;">
            <a href="{{ route('profile.show') }}" class="category-tab {{ !request('page') ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('profile.show', ['page' => 'buy']) }}" class="category-tab {{ request('page') === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>

        @if(request('page') === 'buy')
            <!-- 購入した商品 -->
            <div class="products-grid">
                @forelse($purchases as $purchase)
                    <div class="product-card">
                        <a href="{{ route('products.show', $purchase->product) }}" style="text-decoration: none; color: inherit;">
                            <div class="product-image">
                                @if($purchase->product->image)
                                    @if(str_starts_with($purchase->product->image, 'http'))
                                        <img src="{{ $purchase->product->image }}" alt="{{ $purchase->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        @php
                                            $filename = basename($purchase->product->image);
                                        @endphp
                                        <img src="{{ asset('images/products/' . $filename) }}" alt="{{ $purchase->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                @else
                                    商品画像
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $purchase->product->name }}</h3>
                                <p class="product-price">{{ $purchase->product->formatted_price }}</p>
                                <p style="font-size: 16px; color: #5F5F5F; margin-top: 10px;">購入日: {{ $purchase->created_at->format('Y/m/d') }}</p>
                                <p style="font-size: 16px; color: #5F5F5F;">ステータス: {{ $purchase->status_text }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <p style="font-size: 24px; color: #5F5F5F;">購入した商品はありません。</p>
                    </div>
                @endforelse
            </div>
        @else
            <!-- 出品した商品 -->
            <div class="products-grid">
                @forelse($products as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                            <div class="product-image">
                                @if($product->image)
                                    @if(str_starts_with($product->image, 'http'))
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        @php
                                            $filename = basename($product->image);
                                        @endphp
                                        <img src="{{ asset('images/products/' . $filename) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                @else
                                    商品画像
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <p class="product-price">{{ $product->formatted_price }}</p>
                                <p style="font-size: 16px; color: #5F5F5F; margin-top: 10px;">出品日: {{ $product->created_at->format('Y/m/d') }}</p>
                                @if($product->is_sold)
                                    <p style="font-size: 16px; color: #FF5555; font-weight: 700;">売り切れ</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <p style="font-size: 24px; color: #5F5F5F;">出品した商品はありません。</p>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div style="margin-top: 40px; text-align: center;">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
/* プロフィールセクション */
.profile-section {
    display: flex;
    align-items: center;
    gap: 30px;
    margin-bottom: 40px;
    padding: 0;
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

.profile-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #D9D9D9;
    color: #666666;
    font-size: 14px;
    text-align: center;
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 36px;
    font-weight: 700;
    color: #000000;
    margin: 0;
    line-height: 1.2;
}

.profile-actions {
    flex-shrink: 0;
}

.edit-profile-btn {
    display: inline-block;
    padding: 12px 24px;
    background-color: #FFFFFF;
    color: #FF5555;
    border: 2px solid #FF5555;
    border-radius: 6px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.edit-profile-btn:hover {
    background-color: #FF5555;
    color: #FFFFFF;
    text-decoration: none;
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

    /* プロフィールセクション */
    .profile-section {
        flex-direction: column;
        text-align: center;
        gap: 20px;
        padding: 0;
    }

    .profile-image-container {
        width: 120px;
        height: 120px;
    }

    .profile-name {
        font-size: 28px;
    }

    /* 商品グリッド */
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 30px;
    }

    .product-card {
        width: 100%;
        min-height: 280px;
    }

    .product-image {
        height: 240px;
        font-size: 30px;
    }

    .product-name {
        font-size: 20px;
    }

    .product-price {
        font-size: 18px;
    }

    /* カテゴリタブ */
    .category-tabs {
        gap: 30px;
        margin: 30px 0 15px 0;
    }

    .category-tab {
        font-size: 20px;
        width: 120px;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 0 15px;
    }

    /* プロフィールセクション */
    .profile-section {
        padding: 0;
        gap: 15px;
    }

    .profile-image-container {
        width: 100px;
        height: 100px;
    }

    .profile-name {
        font-size: 24px;
    }

    .edit-profile-btn {
        font-size: 14px;
        padding: 10px 20px;
    }

    /* 商品グリッド */
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 25px;
    }

    .product-card {
        width: 100%;
        min-height: 260px;
    }

    .product-image {
        height: 220px;
        font-size: 28px;
    }

    .product-name {
        font-size: 18px;
    }

    .product-price {
        font-size: 16px;
    }

    /* カテゴリタブ */
    .category-tabs {
        gap: 25px;
        margin: 25px 0 12px 0;
    }

    .category-tab {
        font-size: 18px;
        width: 100px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 10px;
    }

    /* ユーザー情報セクション */
    .user-info-section {
        padding: 10px;
        gap: 12px;
    }

    .profile-image {
        width: 80px;
        height: 80px;
    }

    .user-name {
        font-size: 20px;
        margin-bottom: 10px;
    }

    /* 商品グリッド */
    .products-grid {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-top: 20px;
    }

    .product-card {
        width: 100%;
        min-height: 280px;
    }

    .product-image {
        height: 240px;
        font-size: 30px;
    }

    .product-name {
        font-size: 20px;
    }

    .product-price {
        font-size: 18px;
    }

    /* カテゴリタブ */
    .category-tabs {
        gap: 20px;
        margin: 20px 0 10px 0;
        flex-direction: column;
        align-items: center;
    }

    .category-tab {
        font-size: 16px;
        width: 150px;
        text-align: center;
    }
}
</style>
@endsection
