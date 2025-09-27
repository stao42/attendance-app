@extends('layouts.app')

@section('title', '購入手続き - CoachTech')

@section('content')
<div class="purchase-container">
    <!-- 商品情報 -->
    <div class="purchase-main">
        
        <!-- 商品詳細 -->
        <div class="product-summary">
            <div class="product-image-container">
                @if($product->image)
                    @if(str_starts_with($product->image, 'http'))
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
                    @else
                        @php
                            $filename = basename($product->image);
                        @endphp
                        <img src="{{ asset('images/products/' . $filename) }}" alt="{{ $product->name }}" class="product-image">
                    @endif
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
            <div class="payment-selector">
                <select name="payment_method" id="payment_method" class="payment-select">
                    <option value="">選択してください</option>
                    <option value="card">カード支払い</option>
                    <option value="convenience_store">コンビニ払い</option>
                </select>
                <div class="select-arrow">▼</div>
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
                    〒 {{ auth()->user()->postal_code ?? 'XXX-YYYY' }}<br>
                    {{ auth()->user()->address ?? 'ここには住所と建物が入ります' }}
                    @if(auth()->user()->building)
                        <br>{{ auth()->user()->building }}
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
                    <span id="payment-method-display" class="summary-value">選択してください</span>
                </div>
            </div>

            <form action="{{ route('purchases.store', $product) }}" method="POST" id="purchase-form">
                @csrf
                <input type="hidden" name="payment_method" id="payment_method_hidden">
                <input type="hidden" name="shipping_address" value="{{ auth()->user()->address ?? '' }}">
                <input type="hidden" name="shipping_postal_code" value="{{ auth()->user()->postal_code ?? '' }}">
                <input type="hidden" name="shipping_building" value="{{ auth()->user()->building ?? '' }}">
                
                <button type="submit" class="purchase-button" disabled id="purchase-button">購入する</button>
            </form>
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

.payment-selector {
    border: 2px solid #E0E0E0;
    border-radius: 8px;
    padding: 15px 20px;
    background-color: #FFFFFF;
    position: relative;
}

.payment-select {
    width: 100%;
    font-size: 16px;
    font-weight: 600;
    padding: 0;
    border: none;
    background-color: transparent;
    color: #333333;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}

.payment-select:focus {
    outline: none;
}

.select-arrow {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #666666;
    font-size: 12px;
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

.purchase-button {
    width: 100%;
    padding: 20px;
    font-size: 24px;
    font-weight: 600;
    background-color: #FF5555;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.purchase-button:hover:not(:disabled) {
    background-color: #E04444;
}

.purchase-button:disabled {
    background-color: #CCCCCC;
    cursor: not-allowed;
}

/* レスポンシブデザイン */
@media (max-width: 1540px) {
    .purchase-container {
        max-width: 1400px;
        padding: 0 20px;
    }
}

@media (max-width: 850px) {
    .purchase-container {
        flex-direction: column;
        gap: 30px;
        margin-top: 40px;
        padding: 0 20px;
    }
    
    .purchase-title {
        font-size: 28px;
        margin-bottom: 25px;
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
    
    .currency-symbol {
        font-size: 16px;
    }
    
    .price-amount {
        font-size: 24px;
    }
    
    .section-header {
        margin-bottom: 15px;
    }
    
    .section-title {
        font-size: 18px;
        margin: 0;
    }
    
    .section-divider-top {
        margin-bottom: 20px;
    }
    
    .section-divider-bottom {
        margin-top: 20px;
    }
    
    .shipping-address {
        font-size: 16px;
    }
    
    .change-link {
        font-size: 16px;
    }
    
    .payment-select {
        font-size: 15px;
    }
    
    .summary-label,
    .summary-value {
        font-size: 18px;
    }
    
    .purchase-button {
        padding: 16px;
        font-size: 20px;
    }
}

@media (max-width: 768px) {
    .purchase-container {
        margin-top: 30px;
        padding: 0 15px;
        gap: 20px;
    }
    
    .purchase-title {
        font-size: 24px;
        margin-bottom: 20px;
    }
    
    .product-summary {
        padding: 10px;
    }
    
    .product-image-container {
        width: 120px;
        height: 120px;
    }
    
    .product-name {
        font-size: 20px;
    }
    
    .product-price {
        font-size: 20px;
    }
    
    .currency-symbol {
        font-size: 14px;
    }
    
    .price-amount {
        font-size: 20px;
    }
    
    .section-header {
        margin-bottom: 10px;
    }
    
    .section-title {
        font-size: 16px;
        margin: 0;
    }
    
    .shipping-address {
        font-size: 14px;
    }
    
    .change-link {
        font-size: 14px;
    }
    
    .payment-select {
        font-size: 14px;
    }
    
    .summary-label,
    .summary-value {
        font-size: 16px;
    }
    
    .purchase-button {
        padding: 14px;
        font-size: 18px;
    }
    
    .section-divider-top {
        margin-bottom: 15px;
    }
    
    .section-divider-bottom {
        margin-top: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentSelect = document.getElementById('payment_method');
    const displayElement = document.getElementById('payment-method-display');
    const hiddenInput = document.getElementById('payment_method_hidden');
    const purchaseButton = document.getElementById('purchase-button');
    
    function updatePaymentMethod() {
        const selectedMethod = paymentSelect.value;
        
        if (selectedMethod) {
            const methodText = selectedMethod === 'card' ? 'カード支払い' : 'コンビニ払い';
            displayElement.textContent = methodText;
            hiddenInput.value = selectedMethod;
            purchaseButton.disabled = false;
        } else {
            displayElement.textContent = '選択してください';
            hiddenInput.value = '';
            purchaseButton.disabled = true;
        }
    }
    
    // 初期化
    updatePaymentMethod();
    
    // 変更時のイベントリスナー
    paymentSelect.addEventListener('change', updatePaymentMethod);
});
</script>
@endsection
