<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,operations,sales,md',
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:8',
        ]);

        $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password'] ?? \Illuminate\Support\Str::random(10));

        \App\Models\User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function toggleStatus(\App\Models\User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'User status updated.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,operations,sales,md',
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function forceLogout(User $user)
    {
        // Invalidate session/remember token
        $user->forceFill(['remember_token' => null])->save();
        
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'force_logout',
            'description' => 'Forced logout for user: ' . $user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'User forcefully logged out.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(12);
        $user->password = Hash::make($newPassword);
        $user->save();
        
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'reset_password',
            'description' => 'Reset password for user: ' . $user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', "Password reset successfully. New password: {$newPassword}");
    }
}
