<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'payment_method',
        'shipping_address',
        'shipping_postal_code',
        'shipping_building',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'card' => 'カード支払い',
            'convenience_store' => 'コンビニ払い',
            default => '不明',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => '支払い待ち',
            'paid' => '支払い完了',
            'shipped' => '発送済み',
            'delivered' => '配送完了',
            'cancelled' => 'キャンセル',
            default => '不明',
        };
    }
}
