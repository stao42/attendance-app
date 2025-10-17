<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $productOwner = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $productOwner->id,
        ]);

        // コメント前のコメント数を確認
        $initialCommentCount = $product->comments()->count();

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // コメントを送信
        $response = $this->post('/comments', [
            'product_id' => $product->id,
            'content' => 'とても良い商品です！',
        ]);

        $response->assertRedirect();
        
        // コメントが保存されたことを確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'content' => 'とても良い商品です！',
        ]);

        // コメント数が増加したことを確認
        $this->assertEquals($initialCommentCount + 1, $product->fresh()->comments()->count());
    }

    #[Test]
    public function ログイン前のユーザーはコメントを送信できない()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $productOwner = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $productOwner->id,
        ]);

        // 未認証でコメントを送信
        $response = $this->post('/comments', [
            'product_id' => $product->id,
            'content' => 'とても良い商品です！',
        ]);

        // リダイレクトされる（認証が必要）
        $response->assertRedirect('/login');
        
        // コメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
            'content' => 'とても良い商品です！',
        ]);
    }

    #[Test]
    public function コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $productOwner = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $productOwner->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // 空のコメントを送信
        $response = $this->post('/comments', [
            'product_id' => $product->id,
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['content']);
    }

    #[Test]
    public function コメントが255字以上の場合バリデーションメッセージが表示される()
    {
        // テストデータを作成
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $productOwner = User::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $productOwner->id,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // 256文字のコメントを作成
        $longComment = str_repeat('あ', 256);

        // 長いコメントを送信
        $response = $this->post('/comments', [
            'product_id' => $product->id,
            'content' => $longComment,
        ]);

        $response->assertSessionHasErrors(['content']);
    }
}
