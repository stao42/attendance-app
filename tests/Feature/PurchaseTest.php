<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 購入するボタンを押下すると購入が完了する()
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
            'is_sold' => false,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($buyer);

        // 購入処理を実行
        $response = $this->post("/purchase/{$product->id}", [
            'payment_method' => 'convenience_store',
            'shipping_address' => '東京都渋谷区テスト1-2-3',
            'shipping_postal_code' => '123-4567',
        ]);

        // コンビニ払いの場合は直接完了画面にリダイレクト
        $response->assertRedirect();

        // 購入レコードが作成されたことを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'convenience_store',
            'status' => 'delivered',
        ]);
    }

    #[Test]
    public function 購入した商品は商品一覧画面にてsoldと表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);

        $product = Product::factory()->sold()->create([
            'name' => '売れた商品',
            'price' => 10000,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        // 購入レコードを作成
        Purchase::factory()->create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'status' => 'delivered',
        ]);

        // 別のユーザーとしてアクセス
        $viewer = User::factory()->create();
        $this->actingAs($viewer);

        // 商品一覧を表示
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    #[Test]
    public function プロフィールの購入した商品一覧に追加されている()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);

        $product = Product::factory()->create([
            'name' => '購入した商品',
            'price' => 10000,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        // 購入レコードを作成
        Purchase::factory()->create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'status' => 'delivered',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($buyer);

        // プロフィールページを表示
        $response = $this->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入した商品');
    }

    #[Test]
    public function 購入時にユーザーの住所情報が使用される()
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
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区テスト1-2-3');
    }

    #[Test]
    public function 未認証ユーザーは購入できない()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $seller = User::factory()->create();

        $product = Product::factory()->create([
            'name' => 'テスト商品',
            'price' => 10000,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        // 未認証でアクセス
        $response = $this->post("/purchase/{$product->id}", [
            'payment_method' => 'card',
            'shipping_address' => '東京都渋谷区テスト1-2-3',
            'shipping_postal_code' => '123-4567',
        ]);

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');

        // 購入レコードが作成されていないことを確認
        $this->assertDatabaseMissing('purchases', [
            'product_id' => $product->id,
        ]);
    }

    #[Test]
    public function カード支払いの場合は決済画面に遷移する()
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
            'is_sold' => false,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($buyer);

        // カード支払いで購入処理を実行
        $response = $this->post("/purchase/{$product->id}", [
            'payment_method' => 'card',
            'shipping_address' => '東京都渋谷区テスト1-2-3',
            'shipping_postal_code' => '123-4567',
        ]);

        // 決済画面にリダイレクトされることを確認
        $response->assertRedirect();

        // 購入レコードがpending状態で作成されたことを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'payment_method' => 'card',
            'status' => 'pending',
        ]);

        // 商品はまだ売り切れになっていないことを確認
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_sold' => false,
        ]);
    }
}
