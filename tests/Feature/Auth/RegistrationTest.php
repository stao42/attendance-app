<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required_for_registration(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }

    public function test_email_is_required_for_registration(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function test_password_must_be_at_least_eight_characters(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);
    }

    public function test_password_confirmation_must_match(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password124',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません',
        ]);
    }

    public function test_password_is_required_for_registration(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_valid_registration_creates_user_and_redirects_to_verification_notice(): void
    {
        $response = $this->post('/register', [
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'valid@example.com',
            'name' => 'Valid User',
            'is_admin' => false,
        ]);

        $user = User::where('email', 'valid@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
    }
}
