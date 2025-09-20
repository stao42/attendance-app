@extends('layouts.app')

@section('title', '購入手続き - CoachTech')

@section('content')
<div class="purchase-container">
    <!-- 商品情報 -->
    <div class="purchase-main">
        <h1 class="purchase-title">購入手続き</h1>
        
        <!-- 商品詳細 -->
        <div class="product-summary">
            <div class="product-image-container">
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
            <div class="product-details">
                <h2 class="product-name">{{ $product->name }}</h2>
                <p class="product-price">{{ $product->formatted_price }}</p>
            </div>
        </div>

        <!-- 配送先情報 -->
        <div class="shipping-section">
            <h2 class="section-title">配送先</h2>
            <div class="shipping-info">
                <p class="shipping-address">
                    〒 {{ auth()->user()->postal_code ?? 'XXX-YYYY' }}<br>
                    {{ auth()->user()->address ?? 'ここには住所と建物が入ります' }}
                    @if(auth()->user()->building)
                        <br>{{ auth()->user()->building }}
                    @endif
                </p>
                <a href="{{ route('purchases.edit_address', $product) }}" class="change-link">変更する</a>
            </div>
        </div>

        <!-- 支払い方法 -->
        <div class="payment-section">
            <h2 class="section-title">支払い方法</h2>
            <div class="payment-selector">
                <select name="payment_method" id="payment_method" class="payment-select">
                    <option value="">選択してください</option>
                    <option value="card">カード支払い</option>
                    <option value="convenience_store">コンビニ払い</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 購入確認 -->
    <div class="purchase-sidebar">
        <div class="purchase-summary">
            <div class="summary-section">
                <div class="summary-row">
                    <span class="summary-label">商品代金</span>
                    <span class="summary-value">{{ $product->formatted_price }}</span>
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
    margin-top: 20px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
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
    border: 1px solid #E0E0E0;
    border-radius: 8px;
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
}

.shipping-section {
    margin-bottom: 30px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 20px;
}

.shipping-info {
    padding: 20px;
    background-color: #F8F9FA;
    border-radius: 8px;
    border: 1px solid #E0E0E0;
}

.shipping-address {
    font-size: 20px;
    font-weight: 600;
    color: #000000;
    line-height: 1.5;
    margin-bottom: 15px;
}

.change-link {
    color: #FF5555;
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
    padding: 20px;
    background-color: #FFFFFF;
}

.payment-select {
    width: 100%;
    font-size: 16px;
    font-weight: 600;
    padding: 12px;
    border: none;
    background-color: transparent;
    color: #333333;
}

.payment-select:focus {
    outline: none;
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
</style>

<script>
document.getElementById('payment_method').addEventListener('change', function() {
    const selectedMethod = this.value;
    const displayElement = document.getElementById('payment-method-display');
    const hiddenInput = document.getElementById('payment_method_hidden');
    const purchaseButton = document.getElementById('purchase-button');
    
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
});
</script>
@endsection
