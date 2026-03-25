<?php

namespace App\Http\Controllers\Md;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MdDispatch;
use App\Models\Client;

class OpsDispatchController extends Controller
{
    public function sendFile(Request $request)
    {
        $request->validate([
            'type' => 'required|string', // e.g. Ticket, Invoice
            'file' => 'required|file|max:10240', // 10MB max
            'file_name' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $path = $request->file('file')->store('ops_dispatches', 'public');

        MdDispatch::create([
            'sender_id' => auth()->id(),
            'type' => $request->type,
            'file_path' => $path,
            'file_name' => $request->file_name,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'File dispatched to Operations safely.');
    }

    public function storeClient(Request $request)
    {
        $request->validate([
            'type' => 'required|in:individual,organization',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'passport_number' => 'nullable|string|max:100',
        ]);

        $data = $request->all();
        $data['registered_by'] = auth()->id();

        Client::create($data);

        return redirect()->back()->with('success', 'Client created successfully for the Operations team.');
    }
}
