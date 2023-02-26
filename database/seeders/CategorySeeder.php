<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('categories')->truncate();

        $num = 1;

        $categories = [];
        for ($i = 1; $i <= $num; $i++) {
            $categories[] = [
                'category_list' => '[]',
                'user_id' => $i,
                'created_at' => '2023-01-01 00:00:00',
                'updated_at' => '2023-01-01 00:00:00',
            ];
        }

        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('categories')->insert($categories);
    }
}
