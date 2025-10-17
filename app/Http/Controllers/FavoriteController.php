<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $user = Auth::user();

        // 既にいいねしているかチェック
        if (! $product->isFavoritedBy($user)) {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }

        return back();
    }

    public function destroy(Request $request, Product $product)
    {
        $user = Auth::user();

        // いいねを削除
        Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();

        return back();
    }
}
