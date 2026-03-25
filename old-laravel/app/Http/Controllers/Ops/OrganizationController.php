<?php

namespace App\Http\Controllers\Ops;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Organization::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $organizations = $query->withCount('clients')->latest()->get();
        return view('ops.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('ops.organizations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:organizations',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $org = \App\Models\Organization::create($validated);

        \App\Services\ActivityLogger::log('Operations', 'Organizations', 'Create', $org->name, "Registered new organization");

        return redirect()->route('ops.organizations.index')->with('success', 'Organization created successfully.');
    }
}
