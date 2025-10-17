<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'brand',
        'price',
        'condition',
        'image',
        'user_id',
        'category_id',
        'is_sold',
    ];

    protected $casts = [
        'is_sold' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavoritedBy(User $user): bool
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function getFormattedPriceAttribute(): string
    {
        return '¥' . number_format($this->price) . ' (税込)';
    }

    public function getConditionTextAttribute(): string
    {
        return match($this->condition) {
            'excellent' => '良好',
            'good' => '目立った傷や汚れなし',
            'fair' => 'やや傷や汚れあり',
            'poor' => '状態が悪い',
            default => '不明',
        };
    }
}
