<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
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

        // 配送先を変更
        $response = $this->put("/purchase/address/{$product->id}", [
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市北区梅田1-2-3',
            'building' => 'テストビル101号室',
        ]);

        $response->assertRedirect("/purchase/{$product->id}");

        // 購入画面を表示
        $response = $this->get("/purchase/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('987-6543');
        $response->assertSee('大阪府大阪市北区梅田1-2-3');
    }

    #[Test]
    public function 購入した商品に送付先住所が紐づいて登録される()
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

        // 購入処理を実行
        $response = $this->post("/purchase/{$product->id}", [
            'payment_method' => 'card',
            'shipping_address' => '大阪府大阪市北区梅田1-2-3',
            'shipping_postal_code' => '987-6543',
            'shipping_building' => 'テストビル101号室',
        ]);

        // リダイレクトされることを確認
        $response->assertRedirect();

        // 購入レコードに配送先が保存されたことを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'shipping_postal_code' => '987-6543',
            'shipping_address' => '大阪府大阪市北区梅田1-2-3',
            'shipping_building' => 'テストビル101号室',
        ]);
    }

    #[Test]
    public function 配送先住所変更画面には初期値が表示される()
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

        // 配送先住所変更画面を表示
        $response = $this->get("/purchase/address/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区テスト1-2-3');
    }

    #[Test]
    public function 郵便番号が入力されていない場合エラーが表示される()
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

        // 郵便番号なしで配送先を変更
        $response = $this->put("/purchase/address/{$product->id}", [
            'postal_code' => '',
            'address' => '大阪府大阪市北区梅田1-2-3',
        ]);

        $response->assertSessionHasErrors(['postal_code']);
    }

    #[Test]
    public function 住所が入力されていない場合エラーが表示される()
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

        // 住所なしで配送先を変更
        $response = $this->put("/purchase/address/{$product->id}", [
            'postal_code' => '987-6543',
            'address' => '',
        ]);

        $response->assertSessionHasErrors(['address']);
    }
}
