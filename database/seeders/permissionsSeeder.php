<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
//            donations
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

//            goodLoans
            "goodLoans.index",
            "goodLoans.create",
            "goodLoans.store",
            "goodLoans.edit",
            "goodLoans.update",
            "goodLoans.destroy",
            "delete_goodLoans",
//            borrower
        "borrower.index",
        "borrower.create",
        "borrower.store",
        "borrower.edit",
        "borrower.update",
        "borrower.destroy",
        "delete_borrower",

//            zakat
            "zakat.index",
            "zakat.create",
            "zakat.store",
            "zakat.edit",
            "zakat.update",
            "zakat.destroy",
            "delete_zakat",
//            assets
            "assets.index",
            "assets.create",
            "assets.store",
            "assets.edit",
            "assets.update",
            "assets.destroy",
            "delete_assets",

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission , "guard_name" => 'admin']);
        }



        $this->command->info('Permissions seeded successfully!');
    }
}
