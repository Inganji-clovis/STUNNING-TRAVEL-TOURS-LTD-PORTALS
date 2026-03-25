<?php

namespace App\Http\Controllers\Ops;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('pnr', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('client_names', 'like', "%{$search}%")
                  ->orWhere('airline_name', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->get();
        return view('ops.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('ops.tickets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pnr' => 'required|string|max:10',
            'ticket_number' => 'required|string|max:50',
            'client_names' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'flight_number' => 'required|string|max:50',
            'airline_name' => 'required|string|max:100',
            'ticket_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:5120',
        ]);

        $filePath = $request->file('ticket_file')->store('tickets', 'local');

        $ticket = \App\Models\Ticket::create([
            'pnr' => $request->pnr,
            'ticket_number' => $request->ticket_number,
            'client_names' => $request->client_names,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date,
            'flight_number' => $request->flight_number,
            'airline_name' => $request->airline_name,
            'file_path' => $filePath,
            'created_by' => auth()->id(),
        ]);

        \App\Services\ActivityLogger::log('Operations', 'Flight Tickets', 'Create', $ticket->pnr, "Recorded new flight ticket for {$ticket->client_names}");

        return redirect()->route('ops.tickets.index')->with('success', 'Flight ticket logged successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $pnr = $ticket->pnr;
        if ($ticket->file_path && Storage::disk('local')->exists($ticket->file_path)) {
            Storage::disk('local')->delete($ticket->file_path);
        }
        $ticket->delete();

        \App\Services\ActivityLogger::log('Operations', 'Flight Tickets', 'Delete', $pnr, "Deleted flight ticket record");

        return back()->with('success', 'Ticket record deleted permanently.');
    }
}
