@extends('layouts.app')

@section('title', 'プロフィール - CoachTech')

@section('content')
<div style="margin-top: 20px;">
    <!-- ユーザー情報 -->
    <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 40px; padding: 20px; background-color: #F5F5F5; border-radius: 10px;">
        <div style="width: 150px; height: 150px; background-color: #D9D9D9; border-radius: 50%; overflow: hidden;">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" style="width: 100%; height: 100%; object-fit: cover;">
            @endif
        </div>
        <div>
            <h1 style="font-size: 36px; font-weight: 700; color: #000000; margin-bottom: 20px;">{{ $user->name }}</h1>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">プロフィールを編集</a>
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
                                        <img src="{{ asset('storage/' . $purchase->product->image) }}" alt="{{ $purchase->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
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
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
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
@endsection
