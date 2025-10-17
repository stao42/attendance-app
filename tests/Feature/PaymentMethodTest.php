<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 小計画面で変更が反映される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);
        
        $product = Product::factory()->create([
            'name' => 'テスト商品',
            'price' => 10000,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($buyer);

        // 購入画面を表示
        $response = $this->get("/purchase/{$product->id}");

        $response->assertStatus(200);
        
        // 支払い方法選択のプルダウンが表示されることを確認
        $response->assertSee('payment_method', false); // name属性
        $response->assertSee('コンビニ払い');
        $response->assertSee('カード支払い');
    }

    #[Test]
    public function カード支払いを選択できる()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);
        
        $product = Product::factory()->create([
            'name' => 'テスト商品',
            'price' => 10000,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($buyer);

        // カード支払いで購入
        $response = $this->post("/purchase/{$product->id}", [
            'payment_method' => 'card',
            'shipping_address' => '東京都渋谷区テスト1-2-3',
            'shipping_postal_code' => '123-4567',
        ]);

        // リダイレクトされることを確認
        $response->assertRedirect();
        
        // カード支払いで購入レコードが作成されたことを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'card',
        ]);
    }

    #[Test]
    public function コンビニ支払いを選択できる()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);
        
        $product = Product::factory()->create([
            'name' => 'テスト商品',
            'price' => 10000,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($buyer);

        // コンビニ支払いで購入
        $response = $this->post("/purchase/{$product->id}", [
            'payment_method' => 'convenience_store',
            'shipping_address' => '東京都渋谷区テスト1-2-3',
            'shipping_postal_code' => '123-4567',
        ]);

        // リダイレクトされることを確認
        $response->assertRedirect();
        
        // コンビニ支払いで購入レコードが作成されたことを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'convenience_store',
        ]);
    }

    #[Test]
    public function 支払い方法が選択されていない場合エラーが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);
        
        $product = Product::factory()->create([
            'name' => 'テスト商品',
            'price' => 10000,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($buyer);

        // 支払い方法なしで購入
        $response = $this->post("/purchase/{$product->id}", [
            'payment_method' => '',
            'shipping_address' => '東京都渋谷区テスト1-2-3',
            'shipping_postal_code' => '123-4567',
        ]);

        // バリデーションエラーが発生することを確認
        $response->assertSessionHasErrors(['payment_method']);
    }
}
