<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_is_required_for_admin_login(): void
    {
        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function test_password_is_required_for_admin_login(): void
    {
        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_invalid_admin_credentials_show_error_message(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function test_non_admin_user_cannot_access_admin_portal(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors([
            'email' => '管理者権限がありません',
        ]);
        $this->assertGuest();
    }

    public function test_admin_user_can_login_with_valid_credentials(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/attendance/list');
        $this->assertAuthenticatedAs($admin);
    }
}
