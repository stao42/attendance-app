<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmail
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('メールアドレス認証のお願い')
            ->greeting('こんにちは！')
            ->line('COACHTECHにご登録いただき、ありがとうございます。')
            ->line('アカウントを有効化するために、以下のボタンをクリックしてメールアドレスを認証してください。')
            ->action('メールアドレスを認証', $verificationUrl)
            ->line('このボタンをクリックすると、アカウントが有効化され、COACHTECHの全機能をご利用いただけます。')
            ->line('このメールに心当たりがない場合は、このメールを無視してください。')
            ->salutation('COACHTECHチーム');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
