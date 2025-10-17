<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function create(Product $product)
    {
        // 自分の商品は購入できない
        if ($product->user_id === auth()->id()) {
            return redirect()->route('products.show', $product)->with('error', '自分の商品は購入できません。');
        }

        // 既に売り切れの商品は購入できない
        if ($product->is_sold) {
            return redirect()->route('products.show', $product)->with('error', 'この商品は既に売り切れです。');
        }

        return view('purchases.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        // 自分の商品は購入できない
        if ($product->user_id === auth()->id()) {
            return redirect()->route('products.show', $product)->with('error', '自分の商品は購入できません。');
        }

        // 既に売り切れの商品は購入できない
        if ($product->is_sold) {
            return redirect()->route('products.show', $product)->with('error', 'この商品は既に売り切れです。');
        }

        $request->validate([
            'payment_method' => 'required|in:card,convenience_store',
            'shipping_address' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_building' => 'nullable|string|max:255',
        ]);

        // 購入記録を作成（pending状態）
        $purchase = Purchase::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'payment_method' => $request->payment_method,
            'shipping_address' => $request->shipping_address,
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_building' => $request->shipping_building,
            'status' => 'pending',
        ]);

        // コンビニ払いの場合は即座に完了
        if ($request->payment_method === 'convenience_store') {
            $purchase->update(['status' => 'delivered']);
            
            // 商品を売り切れにする（重複購入を防ぐため）
            $product->update(['is_sold' => true]);
            
            return redirect()->route('profile.show', ['page' => 'buy'])->with('success', 'コンビニ払いの手続きが完了しました。');
        }

        // カード支払いの場合は決済画面に遷移
        return redirect()->route('purchases.payment', ['product' => $product, 'purchase' => $purchase]);
    }

    public function editAddress(Product $product)
    {
        $user = Auth::user();
        return view('purchases.edit_address', compact('product', 'user'));
    }

    public function updateAddress(Request $request, Product $product)
    {
        $request->validate([
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('purchases.create', $product)->with('success', '住所を更新しました。');
    }

    public function payment(Product $product, Purchase $purchase)
    {
        // 購入者が本人かチェック
        if ($purchase->user_id !== auth()->id()) {
            return redirect()->route('products.show', $product)->with('error', '不正なアクセスです。');
        }

        // 商品と購入の関連をチェック
        if ($purchase->product_id !== $product->id) {
            return redirect()->route('products.show', $product)->with('error', '不正なアクセスです。');
        }

        return view('purchases.payment', compact('product', 'purchase'));
    }

    public function success(Product $product, Purchase $purchase)
    {
        // 購入者が本人かチェック
        if ($purchase->user_id !== auth()->id()) {
            return redirect()->route('products.show', $product)->with('error', '不正なアクセスです。');
        }

        // 商品と購入の関連をチェック
        if ($purchase->product_id !== $product->id) {
            return redirect()->route('products.show', $product)->with('error', '不正なアクセスです。');
        }

        // 決済完了として更新
        $purchase->update(['status' => 'delivered']);
        $product->update(['is_sold' => true]);

        return view('purchases.success', compact('product', 'purchase'));
    }

    public function cancel(Product $product, Purchase $purchase)
    {
        // 購入者が本人かチェック
        if ($purchase->user_id !== auth()->id()) {
            return redirect()->route('products.show', $product)->with('error', '不正なアクセスです。');
        }

        // 商品と購入の関連をチェック
        if ($purchase->product_id !== $product->id) {
            return redirect()->route('products.show', $product)->with('error', '不正なアクセスです。');
        }

        // キャンセルとして更新
        $purchase->update(['status' => 'cancelled']);

        return redirect()->route('products.show', $product)->with('error', '決済がキャンセルされました。');
    }
}
