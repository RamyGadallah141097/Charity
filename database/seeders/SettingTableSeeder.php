<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'title' => 'My Company',
            'logo' => 'path/to/logo.png',
            'vat_number' => 'VAT1234567',
            'address' => '1234 Main Street, Anytown, USA',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
