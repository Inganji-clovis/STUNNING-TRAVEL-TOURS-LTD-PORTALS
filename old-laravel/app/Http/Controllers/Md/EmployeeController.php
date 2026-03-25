<?php

namespace App\Http\Controllers\Md;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['sales', 'operations']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('portal')) {
            $query->where('role', strtolower($request->portal));
        }

        $employees = $query->orderBy('name')->get();
        return view('md.employees.index', compact('employees'));
    }
}
