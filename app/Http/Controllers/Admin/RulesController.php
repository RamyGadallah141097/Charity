<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RulesController extends Controller
{
    private function permissionGroupsOrder(): array
    {
        return [
            'admins' => 'المشرفين',
            'users' => 'المستفيدين',
            'research' => 'الأبحاث الاجتماعية',
            'case-research' => 'الحالات قيد البحث',
            'case-research-researchers' => 'الباحثون',
            'case-research-workload' => 'عبء العمل',
            'donors' => 'المتبرعين',
            'Donations' => 'التبرعات',
            'goodLoans' => 'القروض الحسنة',
            'borrower' => 'المقترضين',
            'subventions' => 'الإعانات الشهرية',
            'SubventionsLoans' => 'الإعانات الفردية',
            'in-kind-disbursements' => 'صرف التبرعات العينية',
            'zakat' => 'الزكاة',
            'lock' => 'الخزنة',
            'association-expenses' => 'مصروفات الجمعية',
            'association-revenues' => 'إيرادات الجمعية',
            'setting' => 'الإعدادات',
            'references' => 'التعريفات العامة',
            'roles' => 'الصلاحيات',
            'tasks' => 'الأفكار',
            'assets' => 'الأصول',
            'subscription' => 'الاشتراكات',
            'auth' => 'المصادقة',
            'other' => 'صلاحيات أخرى',
        ];
    }

    private function permissionLabel(string $permission): string
    {
        if (str_ends_with($permission, '.store')) {
            $permission = str_replace('.store', '.create', $permission);
        }

        if (str_ends_with($permission, '.update')) {
            $permission = str_replace('.update', '.edit', $permission);
        }

        if (str_ends_with($permission, '.destroy')) {
            $permission = preg_replace('/\.destroy$/', '.delete', $permission);
        }

        $translated = __('permissions.' . $permission);

        if ($translated !== 'permissions.' . $permission) {
            return $translated;
        }

        return ucwords(str_replace(['.', '-'], ' ', $permission));
    }

    private function permissionGroupKey(string $permission): string
    {
        if ($permission === 'delete_admin') {
            return 'admins';
        }

        if ($permission === 'delete_users') {
            return 'users';
        }

        if ($permission === 'delete_donors') {
            return 'donors';
        }

        if ($permission === 'delete_task') {
            return 'tasks';
        }

        if ($permission === 'delete_assets') {
            return 'assets';
        }

        if ($permission === 'delete_borrower') {
            return 'borrower';
        }

        if ($permission === 'delete_goodLoans') {
            return 'goodLoans';
        }

        if ($permission === 'delete_zakat') {
            return 'zakat';
        }

        if ($permission === 'delete_subventions') {
            return 'subventions';
        }

        if ($permission === 'Role_delete') {
            return 'roles';
        }

        if ($permission === 'donations_delete') {
            return 'Donations';
        }

        if (str_starts_with($permission, 'case-research.researchers')) {
            return 'case-research-researchers';
        }

        if (str_starts_with($permission, 'case-research.workload')) {
            return 'case-research-workload';
        }

        if (str_starts_with($permission, 'case-research.')) {
            return 'case-research';
        }

        if (str_starts_with($permission, 'association.expenses')) {
            return 'association-expenses';
        }

        if (str_starts_with($permission, 'association.revenues')) {
            return 'association-revenues';
        }

        if (str_starts_with($permission, 'admin.')) {
            return 'auth';
        }

        if (str_starts_with($permission, 'research.')) {
            return 'research';
        }

        if (str_starts_with($permission, 'references.')) {
            return 'references';
        }

        if (str_starts_with($permission, 'in-kind-disbursements.')) {
            return 'in-kind-disbursements';
        }

        if (str_starts_with($permission, 'SubventionsLoans.')) {
            return 'SubventionsLoans';
        }

        if (str_contains($permission, '.')) {
            return explode('.', $permission)[0];
        }

        return 'other';
    }

    private function groupedPermissions()
    {
        $groupLabels = $this->permissionGroupsOrder();

        $permissions = Permission::all()->map(function ($permission) {
            $permission->label = $this->permissionLabel($permission->name);
            $permission->group_key = $this->permissionGroupKey($permission->name);
            return $permission;
        })->filter(function ($permission) {
            $name = $permission->name;

            if (str_ends_with($name, '.store') || str_ends_with($name, '.update') || str_ends_with($name, '.destroy')) {
                return false;
            }

            if (in_array($name, ['updateUserStatus', 'userDetails', 'DonationDetails', 'get_donor_phone', 'search.donor', 'showSubventions', 'settingUpdate', 'references.store', 'references.update', 'references.toggle-status', 'social_research', 'research.receive', 'admin.login', 'admin.logout', 'safer.loans', 'safer.InKindDonations'], true)) {
                return false;
            }

            return true;
        });

        $grouped = $permissions->groupBy('group_key');

        return collect($groupLabels)
            ->map(function ($label, $key) use ($grouped) {
                return [
                    'key' => $key,
                    'label' => $label,
                    'permissions' => $grouped->get($key, collect())->sortBy('label')->values(),
                ];
            })
            ->filter(fn ($group) => $group['permissions']->isNotEmpty())
            ->values();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::all();

            return Datatables::of($roles)
                ->editColumn("name", function ($role) {
                    return $role->name;
                })
                ->addColumn("permissions", function ($role) {
                    $permissions = $role->permissions
                        ->pluck('name')
                        ->map(fn($permission) => $this->permissionLabel($permission))
                        ->implode(" - ");



                    return '<span style="
                        display: inline-block;max-width: 330px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;padding: 5px;transition: max-width 0.3s ease-in-out;position: relative;cursor: pointer;"onmouseover="this.style.maxWidth=\'100%\'; this.style.whiteSpace=\'normal\'; this.style.overflow=\'visible\';
                        this.style.background=\'rgba(0, 0, 0, 0.7)\'; this.style.color=\'white\'; this.style.padding=\'8px\';
                        this.style.borderRadius=\'5px\'; this.style.position=\'absolute\'; this.style.zIndex=\'1000\';"

                        onmouseout="this.style.maxWidth=\'330px\'; this.style.whiteSpace=\'nowrap\'; this.style.overflow=\'hidden\';
                        this.style.background=\'transparent\'; this.style.color=\'inherit\'; this.style.padding=\'5px\';
                        this.style.borderRadius=\'0px\'; this.style.position=\'relative\'; this.style.zIndex=\'auto\';"

                        title="' . e($permissions) . '">'

                        . (!empty($permissions) ? e($permissions) : "-") .

                        '</span>';
                })
                ->addColumn('action', function ($role) {
                    $buttons = '';

                    if (auth()->guard('admin')->user()->can('roles.edit')) {
                        $buttons .= '
                            <button type="button" data-id="' . e($role->id) . '"
                                    class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';
                    }

                    if (auth()->guard('admin')->user()->can('Role_delete')) {
                        $buttons .= '
                            <button class="btn btn-pill btn-danger-light"
                                    data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . e($role->id) . '"
                                    data-title="' . e($role->name) . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';
                    }

                    return $buttons ?: '-';
                })


                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin/Roles/Roles');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissionGroups = $this->groupedPermissions();
        return view('admin/Roles/parts/create' , ["permissionGroups" =>$permissionGroups]) ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Create the role
            $role = Role::create(['name' => $request->name , "guard_name" => 'admin']);

            if (!empty($request->permissions)) {
                $permissionNames = \Spatie\Permission\Models\Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();

                $role->givePermissionTo($permissionNames);
            }

            return response()->json([
                "status" => 200,
                "message" => "Role created successfully"
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating role: ' . $e->getMessage());

            return response()->json([
                "status" => 500,
                "message" => "Failed to create role",
                "error" => $e->getMessage()
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissionGroups = $this->groupedPermissions();
        return view('admin/Roles/parts/edit' , ["permissionGroups" =>$permissionGroups , "role" =>$role]) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {


            $role = Role::find($id);
            $role->name = $request->name;
            $role->save();
            $role->permissions()->sync($request->permissions);

            return response()->json([
                "status"=>200,
                "message"=>"<div class=\"alert alert-success\" role=\"alert\"> تم التعديل بنجاح </div>"
            ]);
        }catch (\Exception $e){
            return response()->json([
                "status"=>405,
                "message"=>"<div class=\"alert alert-danger\" role=\"alert\"> ".$e->getMessage()." </div>"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request)
    {
        try {
            Role::destroy($request->id);
            return redirect()->back();
            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        }
        catch (\Exception $ex){
            return response(['message'=>$ex->getMessage(),'status'=>400]);
        }

    }

}
