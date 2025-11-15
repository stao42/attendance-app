<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--name= : 管理者の名前}
                            {--email= : 管理者のメールアドレス}
                            {--password= : 管理者のパスワード}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '管理者ユーザーを作成します';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?: $this->ask('管理者の名前を入力してください');
        $email = $this->option('email') ?: $this->ask('管理者のメールアドレスを入力してください');
        $password = $this->option('password') ?: $this->secret('管理者のパスワードを入力してください（8文字以上）');

        // バリデーション
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->error('入力に誤りがあります:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  - ' . $error);
            }
            return 1;
        }

        // 既存のユーザーを確認
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            if ($this->confirm('このメールアドレスは既に登録されています。管理者権限を付与しますか？', true)) {
                $existingUser->update([
                    'is_admin' => true,
                    'password' => Hash::make($password),
                ]);
                $this->info("✓ ユーザー「{$existingUser->name}」に管理者権限を付与しました。");
                $this->info("  メールアドレス: {$email}");
                $this->info("  パスワード: {$password}");
                return 0;
            }
            return 1;
        }

        // 管理者ユーザーを作成
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        // メール認証通知を送信
        $user->sendEmailVerificationNotification();

        $this->info("✓ 管理者ユーザーを作成しました！");
        $this->info("  名前: {$user->name}");
        $this->info("  メールアドレス: {$user->email}");
        $this->info("  パスワード: {$password}");
        $this->newLine();
        $this->info("管理者ログインURL: http://localhost:8000/admin/login");
        $this->info("メール認証が必要です。MailHog（http://localhost:8025）で認証メールを確認してください。");

        return 0;
    }
}
