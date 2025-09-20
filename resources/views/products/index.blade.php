@extends('layouts.app')

@section('title', '商品一覧 - CoachTech')

@section('content')
<div class="category-tabs">
    <a href="{{ route('products.index') }}" class="category-tab {{ !request('tab') ? 'active' : '' }}">すべて</a>
    @if(auth()->check())
        <a href="{{ route('products.index', ['tab' => 'mylist']) }}" class="category-tab {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    @endif
    @foreach($categories as $category)
        <a href="#" class="category-tab">{{ $category->name }}</a>
    @endforeach
</div>

<div class="products-grid">
    @forelse($products as $product)
        <div class="product-card">
            <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                <div class="product-image" style="position: relative;">
                    @if($product->image)
                        @if(str_starts_with($product->image, 'http'))
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    @else
                        商品画像
                    @endif
                    @if($product->is_sold)
                        <div style="position: absolute; top: 0; left: 0; background: rgba(0,0,0,0.7); color: white; padding: 5px 10px; font-weight: bold;">Sold</div>
                    @endif
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product->name }}</h3>
                </div>
            </a>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            @if(request('tab') === 'mylist')
                <p style="font-size: 24px; color: #5F5F5F;">マイリストに登録された商品はありません。</p>
            @else
                <p style="font-size: 24px; color: #5F5F5F;">商品が見つかりませんでした。</p>
            @endif
        </div>
    @endforelse
</div>

@if($products->hasPages())
    <div style="margin-top: 40px; text-align: center;">
        {{ $products->links() }}
    </div>
@endif
@endsection
