<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function いいねした商品だけが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        // いいねした商品としていない商品を作成
        $favoriteProduct = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
            'name' => 'Favorite Product',
        ]);
        
        $notFavoriteProduct = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
            'name' => 'Not Favorite Product',
        ]);
        
        // いいねを追加
        Favorite::factory()->create([
            'user_id' => $user->id,
            'product_id' => $favoriteProduct->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Favorite Product'); // いいねした商品は表示される
        $response->assertDontSee('Not Favorite Product'); // いいねしていない商品は表示されない
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
        
        // いいねを追加
        Favorite::factory()->create([
            'user_id' => $user->id,
            'product_id' => $soldProduct->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    #[Test]
    public function 未認証の場合は何も表示されない()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        
        // いいねを追加
        Favorite::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // 未認証でアクセス（リダイレクトされる）
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(302); // リダイレクトされる
    }
}
