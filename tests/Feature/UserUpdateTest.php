<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    #[Test]
    public function 変更項目が初期値として過去設定されていること()
    {
        // テストデータを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile/test-image.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
        
        // 初期値が表示されることを確認
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区テスト1-2-3');
    }

    #[Test]
    public function プロフィール画像を変更できる()
    {
        // テストデータを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => null,
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // テスト用の画像ファイルを作成
        $image = UploadedFile::fake()->image('new-profile.jpg', 800, 600);

        // プロフィールを更新
        $response = $this->put('/profile', [
            'name' => 'テストユーザー',
            'profile_image' => $image,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);

        $response->assertRedirect('/mypage');
        
        // 画像が保存されたことを確認
        $user->refresh();
        $this->assertNotNull($user->profile_image);
        Storage::disk('public')->assertExists($user->profile_image);
    }

    #[Test]
    public function ユーザー名を変更できる()
    {
        // テストデータを作成
        $user = User::factory()->create([
            'name' => '元の名前',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // プロフィールを更新
        $response = $this->put('/profile', [
            'name' => '新しい名前',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);

        $response->assertRedirect('/mypage');
        
        // ユーザー名が更新されたことを確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '新しい名前',
        ]);
    }

    #[Test]
    public function 郵便番号を変更できる()
    {
        // テストデータを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // プロフィールを更新
        $response = $this->put('/profile', [
            'name' => 'テストユーザー',
            'postal_code' => '987-6543',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);

        $response->assertRedirect('/mypage');
        
        // 郵便番号が更新されたことを確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'postal_code' => '987-6543',
        ]);
    }

    #[Test]
    public function 住所を変更できる()
    {
        // テストデータを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // プロフィールを更新
        $response = $this->put('/profile', [
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市北区梅田1-2-3',
        ]);

        $response->assertRedirect('/mypage');
        
        // 住所が更新されたことを確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'address' => '大阪府大阪市北区梅田1-2-3',
        ]);
    }

    #[Test]
    public function 複数の項目を同時に変更できる()
    {
        // テストデータを作成
        $user = User::factory()->create([
            'name' => '元の名前',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト1-2-3',
        ]);

        // 認証済みユーザーとしてアクセス
        $this->actingAs($user);

        // テスト用の画像ファイルを作成
        $image = UploadedFile::fake()->image('new-profile.jpg', 800, 600);

        // プロフィールを更新
        $response = $this->put('/profile', [
            'name' => '新しい名前',
            'profile_image' => $image,
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市北区梅田1-2-3',
        ]);

        $response->assertRedirect('/mypage');
        
        // すべての項目が更新されたことを確認
        $user->refresh();
        $this->assertEquals('新しい名前', $user->name);
        $this->assertEquals('987-6543', $user->postal_code);
        $this->assertEquals('大阪府大阪市北区梅田1-2-3', $user->address);
        $this->assertNotNull($user->profile_image);
        Storage::disk('public')->assertExists($user->profile_image);
    }
}
