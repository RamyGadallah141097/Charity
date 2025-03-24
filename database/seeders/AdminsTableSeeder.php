<?php

namespace Database\Seeders;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('admins')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = Admin::first();

        if (!$admin) {
            die("Admin user not found!");
        }

        $role = Role::where('name', 'super_admin')->first();

        if (!$role) {
            die("Role not found!");
        }

        $admin->assignRole($role->name); // أو $admin->assignRole($role);




        DB::table('admins')->insert([
            'name' => 'Admin2',
            'email' => 'admin2@admin.com',
            'password' => Hash::make('123456'),
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = Admin::where('email', 'admin2@admin.com')->first();

        if (!$admin) {
            die("Admin user not found!");
        }

        $role = Role::where('name', 'super_admin')->first();

        if (!$role) {
            die("Role not found!");
        }

        $admin->assignRole($role->name);


    }
}
