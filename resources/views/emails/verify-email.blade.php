@component('mail::message')
# メールアドレス認証のお願い

{{ $user->name }} 様

COACHTECHをご利用いただき、ありがとうございます。

アカウント登録を完了するために、以下のボタンをクリックしてメールアドレスを認証してください。

@component('mail::button', ['url' => $verificationUrl])
メールアドレスを認証
@endcomponent

このボタンをクリックすると、アカウントが有効化され、COACHTECHの全機能をご利用いただけます。

---

**このメールに心当たりがない場合**
このメールに心当たりがない場合は、このメールを無視してください。アカウントは作成されません。

**認証リンクの有効期限**
この認証リンクは60分間有効です。有効期限が切れた場合は、再度ログインして新しい認証メールを送信してください。

---

COACHTECHチーム

@component('mail::subcopy')
認証ボタンが動作しない場合は、以下のリンクをコピーしてブラウザのアドレスバーに貼り付けてください：

[{{ $verificationUrl }}]({{ $verificationUrl }})
@endcomponent
@endcomponent
