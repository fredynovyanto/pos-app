<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'id' => 1,
            'company' => 'Toko Sebelah',
            'address' => 'Jl. Soekarno-Hatta',
            'phone' => '081234779987',
            'tipe_nota' => 1, // kecil
            'path_logo' => '/img/logo.png',
        ]);
    }
}
