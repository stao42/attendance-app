<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['user', 'category', 'favorites']);
        
        // 検索機能
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // マイリスト表示
        if ($request->tab === 'mylist') {
            if (Auth::check()) {
                $query->whereHas('favorites', function($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                $query->whereRaw('1 = 0'); // 未認証の場合は空の結果
            }
        } else {
            // 自分の出品商品は除外
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
        }
        
        $products = $query->latest()->paginate(12);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['user', 'category', 'comments.user', 'favorites']);
        
        return view('products.show', compact('product'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|integer|min:1',
            'condition' => 'required|in:excellent,good,fair,poor',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', '商品を出品しました。');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|integer|min:1',
            'condition' => 'required|in:excellent,good,fair,poor',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.show', $product)->with('success', '商品情報を更新しました。');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }
}
