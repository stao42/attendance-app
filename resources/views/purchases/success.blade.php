@extends('layouts.app')

@section('title', '購入完了 - CoachTech')

@section('content')
<div class="success-container">
    <div class="success-content">
        <div class="success-icon">
            ✅
        </div>
        
        <h1 class="success-title">購入が完了しました！</h1>
        
        <div class="success-message">
            <p>ご購入いただき、ありがとうございました。</p>
            <p>商品は指定された住所にお届けいたします。</p>
        </div>

        <div class="purchase-details">
            <h2 class="details-title">購入内容</h2>
            <div class="product-info">
                <div class="product-image-container">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                    @else
                        <div class="product-image-placeholder">商品画像</div>
                    @endif
                </div>
                <div class="product-details">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-price">
                        <span class="currency-symbol">¥</span>
                        <span class="price-amount">{{ number_format($product->price) }}</span>
                    </p>
                    <p class="payment-method">支払い方法: {{ $purchase->payment_method_text }}</p>
                </div>
            </div>
            
            <div class="shipping-info">
                <h3 class="shipping-title">配送先</h3>
                <p class="shipping-address">
                    〒 {{ $purchase->shipping_postal_code }}<br>
                    {{ $purchase->shipping_address }}
                    @if($purchase->shipping_building)
                        <br>{{ $purchase->shipping_building }}
                    @endif
                </p>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('profile.show', ['page' => 'buy']) }}" class="btn btn-primary">
                購入履歴を見る
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                商品一覧に戻る
            </a>
        </div>
    </div>
</div>

<style>
.success-container {
    max-width: 800px;
    margin: 60px auto;
    padding: 0 20px;
}

.success-content {
    background-color: #FFFFFF;
    border-radius: 12px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    padding: 40px;
    text-align: center;
}

.success-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.success-title {
    font-size: 32px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 20px;
}

.success-message {
    font-size: 18px;
    color: #333333;
    margin-bottom: 40px;
    line-height: 1.6;
}

.success-message p {
    margin-bottom: 10px;
}

.purchase-details {
    background-color: #F8F9FA;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 40px;
    text-align: left;
}

.details-title {
    font-size: 24px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 20px;
    text-align: center;
}

.product-info {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #E0E0E0;
}

.product-image-container {
    width: 120px;
    height: 120px;
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
    font-size: 14px;
}

.product-details {
    flex: 1;
}

.product-name {
    font-size: 20px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 10px;
}

.product-price {
    font-size: 18px;
    font-weight: 600;
    color: #000000;
    margin-bottom: 8px;
    display: flex;
    align-items: baseline;
    gap: 2px;
}

.currency-symbol {
    font-size: 14px;
}

.price-amount {
    font-size: 18px;
}

.payment-method {
    font-size: 16px;
    color: #666666;
    margin: 0;
}

.shipping-info {
    text-align: left;
}

.shipping-title {
    font-size: 18px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 10px;
}

.shipping-address {
    font-size: 16px;
    color: #333333;
    line-height: 1.5;
    margin: 0;
}

.action-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.btn {
    display: inline-block;
    padding: 15px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    text-align: center;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background-color: #FF5555;
    color: #FFFFFF;
}

.btn-primary:hover {
    background-color: #E04444;
    color: #FFFFFF;
    text-decoration: none;
}

.btn-secondary {
    background-color: #FFFFFF;
    color: #FF5555;
    border: 2px solid #FF5555;
}

.btn-secondary:hover {
    background-color: #FF5555;
    color: #FFFFFF;
    text-decoration: none;
}

/* レスポンシブデザイン */
@media (max-width: 768px) {
    .success-container {
        margin: 40px auto;
        padding: 0 15px;
    }
    
    .success-content {
        padding: 30px 20px;
    }
    
    .success-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    .success-title {
        font-size: 24px;
        margin-bottom: 15px;
    }
    
    .success-message {
        font-size: 16px;
        margin-bottom: 30px;
    }
    
    .purchase-details {
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .details-title {
        font-size: 20px;
        margin-bottom: 15px;
    }
    
    .product-info {
        flex-direction: column;
        text-align: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
    }
    
    .product-image-container {
        width: 100px;
        height: 100px;
        align-self: center;
    }
    
    .product-name {
        font-size: 18px;
    }
    
    .product-price {
        font-size: 16px;
    }
    
    .payment-method {
        font-size: 14px;
    }
    
    .shipping-title {
        font-size: 16px;
    }
    
    .shipping-address {
        font-size: 14px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 15px;
    }
    
    .btn {
        padding: 12px 20px;
        font-size: 14px;
    }
}
</style>
@endsection
