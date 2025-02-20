<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class permissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Home
            'admin.home',

            // Admins
            'admins.index',
            'admins.create',
            'admins.store',
            'admins.edit',
            'admins.update',
            'admins.destroy',
            'delete_admin',
            'myProfile',

            // Users
            'users.index',
            'users.create',
            'users.store',
            'delete_users',
            'updateUserStatus',
            'userDetails',
            'DonationDetails',

            // Donors
            'donors.index',
            'donors.create',
            'donors.store',
            'donors.edit',
            'donors.update',
            'donors.destroy',
            'delete_donors',
            'donations_delete',
            'Donations.index',
            'Donations.create',
            'Donations.store',
            'Donations.edit',
            'Donations.update',
            'Donations.destroy',
            'get_donor_phone',
            'search.donor',

            // Tasks
            'tasks.index',
            'tasks.create',
            'tasks.store',
            'tasks.edit',
            'tasks.update',
            'tasks.destroy',
            'delete_task',

            // Safer
            'safer.index',
            'safer.loans',
            'safer.InKindDonations',

            // Subventions
            'subventions.index',
            'subventions.create',
            'subventions.store',
            'subventions.edit',
            'subventions.update',
            'subventions.destroy',
            'showSubventions',
            'delete_subventions',

            // Research
            'research.index',
            'social_research',
            'research.receive',

            // Settings
            'setting.index',
            'settingUpdate',

            // Authentication
            'admin.logout',
            'admin.login',

            // Roles
            'roles.index',
            'roles.create',
            'roles.store',
            'roles.edit',
            'roles.update',
            'roles.destroy',
            'Role_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
