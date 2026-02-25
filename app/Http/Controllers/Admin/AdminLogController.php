<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ActivityLog;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();
        
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $date = $request->input('date', today()->format('Y-m-d'));
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        $logs = $query->paginate(30);
        return view('admin.logs.index', compact('logs'));
    }
}
