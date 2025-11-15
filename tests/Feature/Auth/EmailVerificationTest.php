<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_verification_email_and_shows_notice(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Verify Target',
            'email' => 'verify@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'verify@example.com')->first();
        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verification_notice_contains_direct_link_button(): void
    {
        $user = User::factory()->unverified()->create();
        config(['mail.preview_url' => 'http://example-mail.test']);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertSee('認証はこちらから', escape: false);
        $response->assertSee('http://example-mail.test', escape: false);
    }

    public function test_unverified_user_is_redirected_to_notice_when_logging_in(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'unverified@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'unverified@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verification_link_can_be_resent(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertSessionHas('status', 'verification-link-sent');
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_user_can_verify_email_via_signed_url(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect('/attendance');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
