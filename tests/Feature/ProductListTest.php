<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 全商品を取得できる()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // 他のユーザーの商品を作成（認証済みユーザーには表示される）
        $products = Product::factory()->count(5)->create([
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');

        // すべての商品が表示されることを確認
        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    #[Test]
    public function 購入済み商品はSoldと表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $soldProduct = Product::factory()->sold()->create([
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    #[Test]
    public function 自分が出品した商品は表示されない()
    {
        // ログインユーザーを作成
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        // 自分の商品と他のユーザーの商品を作成
        $myProduct = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'My Product',
        ]);

        $otherUser = User::factory()->create();
        $otherProduct = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
            'name' => 'Other Product',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('My Product'); // 自分の商品は表示されない
        $response->assertSee('Other Product'); // 他のユーザーの商品は表示される
    }
}
