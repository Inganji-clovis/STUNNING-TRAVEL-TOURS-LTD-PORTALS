<?php

namespace App\Http\Controllers\Ops;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => \App\Models\Client::count(),
            'total_organizations' => \App\Models\Organization::count(),
            'tickets_issued' => \App\Models\Ticket::count(),
            'recent_docs' => \App\Models\TravelDocument::count(),
        ];

        // Fetch recent pending dispatches from MD
        $dispatches = \App\Models\MdDispatch::with('sender')
                        ->where('status', 'pending')
                        ->latest()
                        ->get();

        return view('ops.dashboard', compact('stats', 'dispatches'));
    }}
