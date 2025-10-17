<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function いいねアイコンを押下することによっていいねした商品として登録することができる()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $productOwner = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $productOwner->id,
        ]);

        // いいね前のいいね数を確認
        $initialFavoriteCount = $product->favorites()->count();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // いいねを追加
        $response = $this->post("/favorites/{$product->id}");

        $response->assertRedirect();
        
        // いいねが追加されたことを確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // いいね数が増加したことを確認
        $this->assertEquals($initialFavoriteCount + 1, $product->fresh()->favorites()->count());
    }

    #[Test]
    public function 追加済みのアイコンは色が変化する()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $productOwner = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $productOwner->id,
        ]);

        // いいねを追加
        Favorite::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200);
        // いいね済みの場合、ボタンにクラスが追加されることを確認
        $response->assertSee('favorited', false); // CSSクラス名を確認
    }

    #[Test]
    public function 再度いいねアイコンを押下することによっていいねを解除することができる()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $productOwner = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $productOwner->id,
        ]);

        // いいねを追加
        Favorite::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // いいね前のいいね数を確認
        $initialFavoriteCount = $product->favorites()->count();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // いいねを削除
        $response = $this->delete("/favorites/{$product->id}");

        $response->assertRedirect();
        
        // いいねが削除されたことを確認
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // いいね数が減少したことを確認
        $this->assertEquals($initialFavoriteCount - 1, $product->fresh()->favorites()->count());
    }
}
