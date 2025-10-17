<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 商品名で部分一致検索ができる()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        // 検索対象の商品を作成
        $targetProduct = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
            'name' => 'iPhone 15 Pro',
        ]);
        
        // 検索対象外の商品を作成
        $otherProduct = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
            'name' => 'Samsung Galaxy',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/?search=iPhone');

        $response->assertStatus(200);
        $response->assertSee('iPhone 15 Pro'); // 検索結果に含まれる
        $response->assertDontSee('Samsung Galaxy'); // 検索結果に含まれない
    }

    #[Test]
    public function 検索状態がマイリストでも保持されている()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
            'name' => 'iPhone 15 Pro',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // 検索を実行
        $response = $this->get('/?search=iPhone');
        $response->assertStatus(200);

        // マイリストに遷移
        $response = $this->get('/?tab=mylist&search=iPhone');

        $response->assertStatus(200);
        // 検索キーワードが保持されていることを確認（URLパラメータとして）
        $response->assertSee('value="iPhone"', false); // 検索入力フィールドに値が保持されている
    }
}
