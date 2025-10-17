@extends('layouts.app')

@section('title', '決済 - CoachTech')

@section('content')
<div class="purchase-container">
    <!-- 商品情報 -->
    <div class="purchase-main">
        <!-- 商品詳細 -->
        <div class="product-summary">
            <div class="product-image-container">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <div class="product-image-placeholder">商品画像</div>
                @endif
            </div>
            <div class="product-details">
                <h2 class="product-name">{{ $product->name }}</h2>
                <p class="product-price">
                    <span class="currency-symbol">¥</span>
                    <span class="price-amount">{{ number_format($product->price) }}</span>
                </p>
            </div>
        </div>

        <!-- 支払い方法 -->
        <div class="payment-section">
            <div class="section-divider-top"></div>
            <h2 class="section-title">支払い方法</h2>
            <div class="payment-info">
                <p class="payment-method">{{ $purchase->payment_method_text }}</p>
            </div>
            <div class="section-divider-bottom"></div>
        </div>

        <!-- 配送先情報 -->
        <div class="shipping-section">
            <div class="section-header">
                <h2 class="section-title">配送先</h2>
                <a href="{{ route('purchases.edit_address', $product) }}" class="change-link">変更する</a>
            </div>
            <div class="shipping-info">
                <p class="shipping-address">
                    〒 {{ $purchase->shipping_postal_code }}<br>
                    {{ $purchase->shipping_address }}
                    @if($purchase->shipping_building)
                        <br>{{ $purchase->shipping_building }}
                    @endif
                </p>
            </div>
            <div class="section-divider-bottom"></div>
        </div>
    </div>

    <!-- 購入確認 -->
    <div class="purchase-sidebar">
        <div class="purchase-summary">
            <div class="summary-section">
                <div class="summary-row">
                    <span class="summary-label">商品代金</span>
                    <span class="summary-value">
                        <span class="currency-symbol">¥</span>
                        <span class="price-amount">{{ number_format($product->price) }}</span>
                    </span>
                </div>
            </div>
            
            <div class="summary-section">
                <div class="summary-row">
                    <span class="summary-label">支払い方法</span>
                    <span class="summary-value">{{ $purchase->payment_method_text }}</span>
                </div>
            </div>

            @if($purchase->payment_method === 'card')
                <div class="payment-actions">
                    <a href="{{ route('purchases.success', ['product' => $product, 'purchase' => $purchase]) }}" 
                       class="purchase-button">
                        決済を完了する
                    </a>
                    <a href="{{ route('purchases.cancel', ['product' => $product, 'purchase' => $purchase]) }}" 
                       class="cancel-button">
                        キャンセル
                    </a>
                </div>
            @else
                <div class="payment-actions">
                    <p class="convenience-info">コンビニ払いは手続きが完了しています。</p>
                    <a href="{{ route('profile.show', ['page' => 'buy']) }}" class="purchase-button">
                        購入履歴を見る
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.purchase-container {
    display: flex;
    gap: 40px;
    margin-top: 60px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 20px;
}

.purchase-main {
    flex: 2;
}

.purchase-title {
    font-size: 36px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 30px;
}

.product-summary {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    background-color: #FFFFFF;
}

.product-image-container {
    width: 177px;
    height: 177px;
    background-color: #D9D9D9;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
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
    font-size: 18px;
}

.product-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.product-name {
    font-size: 30px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 10px;
}

.product-price {
    font-size: 30px;
    font-weight: 400;
    color: #000000;
    display: flex;
    align-items: baseline;
    gap: 2px;
}

.currency-symbol {
    font-size: 20px;
    font-weight: 400;
}

.price-amount {
    font-size: 30px;
    font-weight: 400;
}

.shipping-section {
    margin-bottom: 30px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #000000;
    margin: 0;
}

.section-divider-top {
    width: 100%;
    height: 1px;
    background-color: #E0E0E0;
    margin-bottom: 25px;
}

.section-divider-bottom {
    width: 100%;
    height: 1px;
    background-color: #E0E0E0;
    margin-top: 25px;
}

.shipping-info {
    padding: 0;
}

.shipping-address {
    font-size: 20px;
    font-weight: 600;
    color: #000000;
    line-height: 1.5;
    margin-bottom: 15px;
}

.change-link {
    color: #0066CC;
    font-size: 18px;
    text-decoration: none;
    font-weight: 500;
}

.change-link:hover {
    text-decoration: underline;
}

.payment-section {
    margin-bottom: 30px;
}

.payment-info {
    border: 2px solid #E0E0E0;
    border-radius: 8px;
    padding: 15px 20px;
    background-color: #FFFFFF;
}

.payment-method {
    font-size: 16px;
    font-weight: 600;
    color: #333333;
    margin: 0;
}

.purchase-sidebar {
    flex: 1;
}

.purchase-summary {
    border: 1px solid #E0E0E0;
    border-radius: 8px;
    padding: 30px;
    background-color: #FFFFFF;
    position: sticky;
    top: 20px;
}

.summary-section {
    border-bottom: 1px solid #E0E0E0;
    padding-bottom: 20px;
    margin-bottom: 20px;
}

.summary-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-label {
    font-size: 20px;
    font-weight: 400;
    color: #000000;
}

.summary-value {
    font-size: 20px;
    font-weight: 600;
    color: #000000;
    display: flex;
    align-items: baseline;
    gap: 2px;
}

.payment-actions {
    margin-top: 20px;
}

.purchase-button {
    display: block;
    width: 100%;
    padding: 20px;
    font-size: 24px;
    font-weight: 600;
    background-color: #FF5555;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease;
    margin-bottom: 10px;
}

.purchase-button:hover {
    background-color: #E04444;
    color: #FFFFFF;
    text-decoration: none;
}

.cancel-button {
    display: block;
    width: 100%;
    padding: 15px;
    font-size: 18px;
    font-weight: 600;
    background-color: #CCCCCC;
    color: #666666;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease;
}

.cancel-button:hover {
    background-color: #BBBBBB;
    color: #666666;
    text-decoration: none;
}

.convenience-info {
    font-size: 16px;
    color: #0073CC;
    margin-bottom: 15px;
    padding: 10px;
    background-color: #E5F2FF;
    border-radius: 4px;
    text-align: center;
}

/* レスポンシブデザイン */
@media (max-width: 850px) {
    .purchase-container {
        flex-direction: column;
        gap: 30px;
        margin-top: 40px;
        padding: 0 20px;
    }
    
    .product-summary {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }
    
    .product-image-container {
        width: 150px;
        height: 150px;
        align-self: center;
    }
    
    .product-name {
        font-size: 24px;
        text-align: center;
    }
    
    .product-price {
        font-size: 24px;
        text-align: center;
    }
}
</style>
@endsection
