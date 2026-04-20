<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(DonationTypeSeeder::class);
        $this->call(BorrowersSeeder::class);
        $this->call(GuarantorsSeeder::class);
        $this->call(MediaSeeder::class);
    }
}
