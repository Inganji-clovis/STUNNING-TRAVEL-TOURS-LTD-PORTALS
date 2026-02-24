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
            $query->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $logs = $query->paginate(30);
        return view('admin.logs.index', compact('logs'));
    }
}
