<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPermissionController extends Controller
{
    private $foundationPermissions = [
        'Operations Portal' => ['client_management', 'billing_access', 'passport_handling', 'operation_reports'],
        'Sales Portal' => ['ebm_management', 'invoice_tracking', 'payment_processing', 'tax_auditing'],
        'Executive Suite' => ['view_financials', 'strategic_docs', 'team_oversight'],
        'Admin System' => ['manage_users', 'manage_roles', 'manage_security', 'view_logs']
    ];

    public function index(Request $request)
    {
        // Auto-seed foundational permissions if they don't exist
        foreach ($this->foundationPermissions as $group => $perms) {
            foreach ($perms as $perm) {
                Permission::firstOrCreate(['name' => $perm]);
            }
        }

        $roles = Role::all();
        $activeRole = null;
        
        if ($request->has('role_id')) {
            $activeRole = Role::find($request->role_id);
        } elseif ($roles->count() > 0) {
            $activeRole = $roles->first();
        }

        $permissionsByGroup = $this->foundationPermissions;

        return view('admin.permissions.index', compact('roles', 'activeRole', 'permissionsByGroup'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::findOrFail($request->role_id);
        
        // Sync given permissions, if null it removes all
        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', "Permissions updated successfully for {$role->name}.");
    }
}
