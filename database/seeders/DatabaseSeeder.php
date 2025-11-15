<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 管理者ユーザーの作成
        $admin = User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
        // メール認証通知を送信
        $admin->sendEmailVerificationNotification();

        // テストユーザーの作成（オプション）
        $testUser = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
        // メール認証通知を送信
        $testUser->sendEmailVerificationNotification();
    }
}
