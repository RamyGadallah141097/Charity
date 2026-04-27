<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
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


            // Admins
            'admins.index',
            'admins.create',
            'admins.edit',
            'delete_admin',

            // Users
            'users.index',
            'users.create',
            'users.edit',
            'delete_users',
            'updateUserStatus',
            'userDetails',
            'DonationDetails',

            // Donors
            'donors.index',
            'donors.create',
            'donors.edit',
            'delete_donors',
            // donations
            'donations_delete',
            'Donations.index',
            'Donations.create',
            'Donations.edit',
            'get_donor_phone',
            'search.donor',


            'lock.index',
            'association.expenses.index',
            'association.expenses.create',
            'association.expenses.edit',
            'association.expenses.delete',
            'association.revenues.index',
            'association.revenues.create',
            'association.revenues.edit',
            'association.revenues.delete',
            // Tasks
            'tasks.index',
            'tasks.create',
            'tasks.edit',
            'delete_task',

            // Safer
            'safer.index',
            'safer.loans',
            'safer.InKindDonations',

            // Subventions
            'subventions.index',
            'subventions.create',
            'subventions.edit',
            'showSubventions',
            'delete_subventions',
            'SubventionsLoans.index',
            'SubventionsLoans.create',
            'SubventionsLoans.delete',
            'in-kind-disbursements.index',
            'in-kind-disbursements.create',
            'in-kind-disbursements.delete',

            'subscription.index',
            // Research
            'research.index',
            'case-research.index',
            'case-research.create',
            'case-research.edit',
            'case-research.delete',
            'case-research.manage-researchers',
            'case-research.researchers.index',
            'case-research.workload.index',
            'social_research',
            'research.receive',

            // Settings
            'setting.index',
            'references.dashboard',
            'references.index',
            'references.create',
            'references.edit',
            'references.delete',

            // Authentication
            'admin.logout',
            'admin.login',

            // Roles
            'roles.index',
            'roles.create',
            'roles.edit',
            'Role_delete',

            // goodLoans
            'goodLoans.index',
            'goodLoans.create',
            'goodLoans.edit',
            'delete_goodLoans',
            // borrower
            'borrower.index',
            'borrower.create',
            'borrower.edit',
            'delete_borrower',

            // zakat
            'zakat.index',
            'zakat.create',
            'zakat.edit',
            'delete_zakat',
            // assets
            'assets.index',
            'assets.create',
            'assets.edit',
            'delete_assets',



        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission , "guard_name" => 'admin']);
        }

        $legacyPermissionsToDelete = [
            'admins.store', 'admins.update', 'admins.destroy',
            'users.store', 'users.update',
            'donors.store', 'donors.update', 'donors.destroy',
            'Donations.store', 'Donations.update', 'Donations.destroy',
            'tasks.store', 'tasks.update', 'tasks.destroy',
            'subventions.store', 'subventions.update', 'subventions.destroy',
            'SubventionsLoans.store',
            'in-kind-disbursements.store',
            'case-research.store', 'case-research.update', 'case-research.researchers.store',
            'settingUpdate',
            'association.expenses.store', 'association.expenses.update',
            'association.revenues.store', 'association.revenues.update',
            'references.store', 'references.update', 'references.toggle-status',
            'roles.store', 'roles.update', 'roles.destroy',
            'goodLoans.store', 'goodLoans.update', 'goodLoans.destroy',
            'borrower.store', 'borrower.update', 'borrower.destroy',
            'zakat.store', 'zakat.update', 'zakat.destroy',
            'assets.store', 'assets.update', 'assets.destroy',
        ];

        Permission::query()
            ->where('guard_name', 'admin')
            ->whereIn('name', $legacyPermissionsToDelete)
            ->delete();

        $researchPermissions = [
            'case-research.index',
            'case-research.manage-researchers',
            'case-research.researchers.index',
            'case-research.workload.index',
        ];

        $roleManagementPermissions = [
            'roles.create',
            'roles.edit',
            'Role_delete',
        ];

        Role::query()
            ->whereHas('permissions', function ($query) {
                $query->where('name', 'research.index');
            })
            ->get()
            ->each(function ($role) use ($researchPermissions) {
                $role->givePermissionTo($researchPermissions);
            });

        Role::query()
            ->whereHas('permissions', function ($query) {
                $query->where('name', 'roles.index');
            })
            ->get()
            ->each(function ($role) use ($roleManagementPermissions) {
                $role->givePermissionTo($roleManagementPermissions);
            });



        $this->command->info('Permissions seeded successfully!');
    }
}
