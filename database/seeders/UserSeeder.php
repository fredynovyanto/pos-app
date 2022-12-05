<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'foto' => '/img/user.jpg',
                'level' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Kasir',
                'email' => 'kasir@gmail.com',
                'password' => bcrypt('password'),
                'foto' => '/img/user.jpg',
                'level' => 2,
            ]
            ];
        DB::table('users')->insert($data);
    }
}
