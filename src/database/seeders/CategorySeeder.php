<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('categories')->insert([
            ['content' => '商品のお届け',         'created_at' => $now, 'updated_at' => $now],
            ['content' => '商品の交換',           'created_at' => $now, 'updated_at' => $now],
            ['content' => '商品トラブル',         'created_at' => $now, 'updated_at' => $now],
            ['content' => 'ショップへのお問い合わせ', 'created_at' => $now, 'updated_at' => $now],
            ['content' => 'その他',               'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
