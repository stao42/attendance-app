<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    #[Test]
    public function 商品出品画面にて必要な情報が保存できること()
    {
        // テストデータを作成
        $category = Category::factory()->create(['name' => 'Electronics']);
        $user = User::factory()->create();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // テスト用の画像ファイルを作成
        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        // 商品情報を送信
        $response = $this->post('/products', [
            'name' => 'iPhone 15 Pro',
            'description' => '最新のiPhoneです。とても良い状態です。',
            'brand' => 'Apple',
            'price' => 150000,
            'condition' => 'excellent',
            'category_id' => $category->id,
            'image' => $image,
        ]);

        $response->assertRedirect('/');
        
        // 商品が保存されたことを確認
        $this->assertDatabaseHas('products', [
            'name' => 'iPhone 15 Pro',
            'description' => '最新のiPhoneです。とても良い状態です。',
            'brand' => 'Apple',
            'price' => 150000,
            'condition' => 'excellent',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // 画像が保存されたことを確認
        $product = Product::where('name', 'iPhone 15 Pro')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    #[Test]
    public function 商品名が入力されていない場合バリデーションメッセージが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // 商品名なしで送信
        $response = $this->post('/products', [
            'name' => '',
            'description' => '商品の説明です',
            'brand' => 'Apple',
            'price' => 150000,
            'condition' => 'excellent',
            'category_id' => $category->id,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    #[Test]
    public function 商品説明が入力されていない場合バリデーションメッセージが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // 商品説明なしで送信
        $response = $this->post('/products', [
            'name' => 'iPhone 15 Pro',
            'description' => '',
            'brand' => 'Apple',
            'price' => 150000,
            'condition' => 'excellent',
            'category_id' => $category->id,
        ]);

        $response->assertSessionHasErrors(['description']);
    }

    #[Test]
    public function 商品画像がアップロードされていない場合でも商品は保存される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // 画像なしで送信
        $response = $this->post('/products', [
            'name' => 'iPhone 15 Pro',
            'description' => '商品の説明です',
            'brand' => 'Apple',
            'price' => 150000,
            'condition' => 'excellent',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect('/');
        
        // 商品が保存されたことを確認
        $this->assertDatabaseHas('products', [
            'name' => 'iPhone 15 Pro',
            'description' => '商品の説明です',
            'brand' => 'Apple',
            'price' => 150000,
            'condition' => 'excellent',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function 商品のカテゴリーが選択されていない場合バリデーションメッセージが表示される()
    {
        // テストデータを作成
        $user = User::factory()->create();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // テスト用の画像ファイルを作成
        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        // カテゴリーなしで送信
        $response = $this->post('/products', [
            'name' => 'iPhone 15 Pro',
            'description' => '商品の説明です',
            'brand' => 'Apple',
            'price' => 150000,
            'condition' => 'excellent',
            'category_id' => '',
            'image' => $image,
        ]);

        $response->assertSessionHasErrors(['category_id']);
    }

    #[Test]
    public function 商品の状態が選択されていない場合バリデーションメッセージが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // テスト用の画像ファイルを作成
        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        // 商品の状態なしで送信
        $response = $this->post('/products', [
            'name' => 'iPhone 15 Pro',
            'description' => '商品の説明です',
            'brand' => 'Apple',
            'price' => 150000,
            'condition' => '',
            'category_id' => $category->id,
            'image' => $image,
        ]);

        $response->assertSessionHasErrors(['condition']);
    }

    #[Test]
    public function 商品価格が入力されていない場合バリデーションメッセージが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // テスト用の画像ファイルを作成
        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        // 価格なしで送信
        $response = $this->post('/products', [
            'name' => 'iPhone 15 Pro',
            'description' => '商品の説明です',
            'brand' => 'Apple',
            'price' => '',
            'condition' => 'excellent',
            'category_id' => $category->id,
            'image' => $image,
        ]);

        $response->assertSessionHasErrors(['price']);
    }

    #[Test]
    public function 商品価格が0円以下の場合バリデーションメッセージが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // テスト用の画像ファイルを作成
        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        // 0円で送信
        $response = $this->post('/products', [
            'name' => 'iPhone 15 Pro',
            'description' => '商品の説明です',
            'brand' => 'Apple',
            'price' => 0,
            'condition' => 'excellent',
            'category_id' => $category->id,
            'image' => $image,
        ]);

        $response->assertSessionHasErrors(['price']);
    }
}
