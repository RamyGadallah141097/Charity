<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->insert([
            ['name' => 'super_admin', 'guard_name' => 'admin'],
            ['name' => 'admin', 'guard_name' => 'admin'],
        ]);

        $role = Role::query()->find(1);
        $role2 = Role::query()->find(2);

        $permissions = Permission::all();

        $role->syncPermissions($permissions);
        $role2->syncPermissions($permissions);
    }
}
