<?php

namespace App\Http\Controllers\Ops;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Client::with('organization', 'registrar');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%")
                  ->orWhere('airline_name', 'like', "%{$search}%")
                  ->orWhereHas('organization', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $clients = $query->latest()->get();
        return view('ops.clients.index', compact('clients'));
    }

    public function create()
    {
        $organizations = \App\Models\Organization::all();
        return view('ops.clients.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'organization_id' => 'nullable|exists:organizations,id',
            'membership_type' => 'required|in:standard,corporate,premium',
            'membership_number' => 'nullable|string|max:50',
            'airline_name' => 'nullable|string|max:100',
        ]);

        $data['registered_by'] = auth()->id();

        $client = \App\Models\Client::create($data);

        \App\Services\ActivityLogger::log('Operations', 'Client Registry', 'Create', $client->name, "Enrolled new client");

        return redirect()->route('ops.clients.index')->with('success', 'Client registered successfully.');
    }

    public function show(\App\Models\Client $client)
    {
        $client->load('organization', 'travelDocuments', 'billingDocuments');
        return view('ops.clients.show', compact('client'));
    }}
