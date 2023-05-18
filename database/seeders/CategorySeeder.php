<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('categories')->truncate();

        $num = 1;
        $uuid = config('const.seeders.uuid');

        $categories = [];
        for ($i = 1; $i <= $num; $i++) {
            $categories[] = [
                'id' => (string)Str::uuid(),
                'category_list' => '[]',
                'user_id' => $uuid,
                'created_at' => '2023-01-01 00:00:00',
                'updated_at' => '2023-01-01 00:00:00',
            ];
        }

        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('categories')->insert($categories);
    }
}
