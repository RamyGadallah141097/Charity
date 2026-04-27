<?php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Permission::firstOrCreate(['name' => 'research.index', 'guard_name' => 'admin']);
$role = Role::where('name', 'Super Admin')->orWhere('id', 1)->first();
if($role) {
    $role->givePermissionTo('research.index');
}

// Give it to the first admin directly just in case
$admin = Admin::first();
if ($admin) {
    $admin->givePermissionTo('research.index');
}

echo "Done\n";
