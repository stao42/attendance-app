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
        // 管理者ユーザーの作成（既に存在する場合はスキップ）
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => '管理者',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
        // 新規作成の場合のみメール認証通知を送信
        if ($admin->wasRecentlyCreated) {
            $admin->sendEmailVerificationNotification();
        }

        // テストユーザーの作成（既に存在する場合はスキップ）
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'テストユーザー',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
        // 新規作成の場合のみメール認証通知を送信
        if ($testUser->wasRecentlyCreated) {
            $testUser->sendEmailVerificationNotification();
        }
    }
}
