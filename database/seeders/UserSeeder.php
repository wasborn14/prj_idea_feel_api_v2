<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('users')->truncate();

        $num = 1;

        $users = [];
        for ($i = 1; $i <= $num; $i++) {
            $users[] = [
                'name' => 'Test User' . $i,
                'email' => 'ideafeel.app+' . $i . '@gmail.com',
                'email_verified_at' => '2023-01-01 00:00:00',
                'password' => Hash::make('password'),
                'created_at' => '2023-01-01 00:00:00',
                'updated_at' => '2023-01-01 00:00:00',
            ];
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert($users);
    }
}
