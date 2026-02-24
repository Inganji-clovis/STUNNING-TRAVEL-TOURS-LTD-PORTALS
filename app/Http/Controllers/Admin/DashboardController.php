<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Setting;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'Total Active Staff' => User::where('is_active', true)->count(),
            'Suspended Staff' => User::where('is_active', false)->count(),
            'Available Roles' => class_exists(Role::class) ? Role::count() : User::distinct('role')->count('role'),
            'Active Portals' => 4, // Default to 4 main portals
        ];

        $recentActivities = ActivityLog::with('user')->latest()->take(5)->get();
        $securityNotices = ActivityLog::with('user')
            ->whereIn('action', ['failed_login', 'account_suspended', 'force_logout'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentActivities', 'securityNotices'));
    }
}
