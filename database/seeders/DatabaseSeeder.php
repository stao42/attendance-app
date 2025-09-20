<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // カテゴリシーダーを実行
        $this->call(CategorySeeder::class);
        
        // 商品シーダーを実行
        $this->call(ProductSeeder::class);
    }
}
