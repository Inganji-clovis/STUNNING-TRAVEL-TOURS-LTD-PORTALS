<?php

namespace App\Http\Controllers\Md;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));

        $query = \App\Models\ActivityLog::with('user')
            ->whereDate('created_at', $date);

        if ($request->filled('portal')) {
            $query->where('properties->portal', $request->portal);
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('action_type')) {
            $query->where('action', 'like', "%{$request->action_type}%");
        }

        $activity_log = $query->latest()->limit(50)->get();
        
        $employees = \App\Models\User::whereIn('role', ['sales', 'operations', 'admin'])->get();

        return view('md.dashboard', compact('activity_log', 'date', 'employees'));
    }
}
