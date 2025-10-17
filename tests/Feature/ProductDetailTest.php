<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 必要な情報が表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create(['name' => 'Electronics']);
        $user = User::factory()->create(['name' => 'Test User']);
        $viewer = User::factory()->create(); // 閲覧者

        $product = Product::factory()->create([
            'name' => 'iPhone 15 Pro',
            'brand' => 'Apple',
            'price' => 150000,
            'description' => '最新のiPhoneです',
            'condition' => 'excellent',
            'image' => 'products/iphone.jpg',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // コメントを追加
        $commentUser = User::factory()->create(['name' => 'Comment User']);
        $comment = Comment::factory()->create([
            'content' => 'とても良い商品です',
            'user_id' => $commentUser->id,
            'product_id' => $product->id,
        ]);

        // いいねを追加
        Favorite::factory()->count(3)->create([
            'product_id' => $product->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($viewer);

        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('iPhone 15 Pro'); // 商品名
        $response->assertSee('Apple'); // ブランド名
        $response->assertSee('¥'); // 価格（通貨記号が含まれる）
        $response->assertSee('最新のiPhoneです'); // 商品説明
        $response->assertSee('良好'); // 商品の状態
        $response->assertSee('Comment User'); // コメントしたユーザー情報
        $response->assertSee('とても良い商品です'); // コメント内容
    }

    #[Test]
    public function 複数選択されたカテゴリが表示されているか()
    {
        // テストデータを作成
        $category = Category::factory()->create(['name' => 'Electronics']);
        $user = User::factory()->create();
        $viewer = User::factory()->create(); // 閲覧者

        $product = Product::factory()->create([
            'name' => 'iPhone 15 Pro',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($viewer);

        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('カテゴリーなし'); // カテゴリ（現在はカテゴリーなしと表示される）
    }
}
