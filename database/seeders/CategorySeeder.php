<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
        [
            'list' => '野菜',
        ],
        [
            'list' => 'タンパク質',
        ],
        [
            'list' => '炭水化物',
        ]
    ];
    # DB::table->insertでレコードの登録
    DB::table('categories')->insert($param);
    }
}
