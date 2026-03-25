<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name'
        ]);

        Role::create($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        // View users assigned to each role
        $users = $role->users()->get();
        return view('admin.roles.show', compact('role', 'users'));
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id
        ]);

        $role->update($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Protect foundational roles
        if (in_array($role->name, ['Super Admin', 'System Architect'])) {
            return back()->with('error', 'Cannot delete this foundational system role.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    public function clone(Role $role)
    {
        $newRole = Role::create([
            'name' => $role->name . ' (Clone)'
        ]);

        // If using Spatie permissions, copy them
        if ($role->permissions()->count() > 0) {
            $newRole->syncPermissions($role->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role cloned successfully.');
    }
}
