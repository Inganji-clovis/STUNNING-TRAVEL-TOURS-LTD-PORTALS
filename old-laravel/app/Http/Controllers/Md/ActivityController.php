<?php

namespace App\Http\Controllers\Md;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('portal')) {
            $query->where('properties->portal', $request->portal);
        }

        if ($request->filled('module')) {
            $query->where('properties->module', $request->module);
        }

        $dateStart = $request->input('date_start', today()->format('Y-m-d'));
        if ($dateStart) {
            $query->whereDate('created_at', '>=', $dateStart);
        }

        $dateEnd = $request->input('date_end', today()->format('Y-m-d'));
        if ($dateEnd) {
            $query->whereDate('created_at', '<=', $dateEnd);
        }

        $logs = $query->latest()->paginate(50);
        return view('md.activity.index', compact('logs'));
    }
}
