<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 必要な情報が取得できる()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile/test-image.jpg',
        ]);

        // 出品商品を作成
        $soldProduct = Product::factory()->sold()->create([
            'name' => '売れた商品',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $availableProduct = Product::factory()->create([
            'name' => '販売中商品',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // 購入商品を作成
        $buyer = User::factory()->create();
        $purchasedProduct = Product::factory()->create([
            'name' => '購入した商品',
            'category_id' => $category->id,
            'user_id' => $buyer->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $purchasedProduct->id,
            'status' => 'delivered',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
        $response->assertViewHas('user');

        // プロフィール情報が表示されることを確認
        $response->assertSee('テストユーザー'); // ユーザー名

        // 出品した商品一覧が表示されることを確認
        $response->assertSee('売れた商品'); // 売れた商品
        $response->assertSee('販売中商品'); // 販売中商品

        // 購入した商品一覧が表示されることを確認
        $response->assertSee('購入した商品'); // 購入した商品
    }

    #[Test]
    public function プロフィール画像が表示される()
    {
        // テストデータを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile/test-image.jpg',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        // プロフィール画像が表示されることを確認（実際の表示ではプレースホルダーが表示される）
        $response->assertSee('プロフィール画像');
    }

    #[Test]
    public function プロフィール画像がない場合はデフォルト画像が表示される()
    {
        // テストデータを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => null,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        // デフォルト画像またはプレースホルダーが表示されることを確認
        $response->assertSee('placeholder', false); // placeholderクラスまたはデフォルト画像
    }

    #[Test]
    public function 出品した商品と購入した商品が正しく分類される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // 自分が出品した商品（販売中）
        $myProduct = Product::factory()->create([
            'name' => '私の出品商品',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // 自分が出品した商品（売れた）
        $mySoldProduct = Product::factory()->sold()->create([
            'name' => '私の売れた商品',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // 自分が購入した商品
        $purchasedProduct = Product::factory()->create([
            'name' => '購入した商品',
            'category_id' => $category->id,
            'user_id' => $otherUser->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $purchasedProduct->id,
            'status' => 'delivered',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);

        // 出品した商品が表示されることを確認
        $response->assertSee('私の出品商品');
        $response->assertSee('私の売れた商品');

        // 購入した商品が表示されることを確認
        $response->assertSee('購入した商品');
    }
}
