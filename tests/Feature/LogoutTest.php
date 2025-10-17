<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function ログアウトができる()
    {
        // テストユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // ログアウト実行
        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
