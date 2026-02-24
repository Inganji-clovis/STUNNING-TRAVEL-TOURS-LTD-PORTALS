<?php

namespace App\Http\Controllers\Ops;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TravelDocumentController extends Controller
{
    public function create(Request $request)
    {
        $clientId = $request->get('client_id');
        $clients = \App\Models\Client::all();
        return view('ops.documents.create', compact('clients', 'clientId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:passport,visa',
            'file' => 'required|file|mimes:pdf,jpg,png,jpeg|max:10240',
            'expiry_date' => 'nullable|date',
        ]);

        $file = $request->file('file');
        $path = $file->store('private/clients/' . ($request->type === 'passport' ? 'passports' : 'visas'));

        \App\Models\TravelDocument::create([
            'client_id' => $request->client_id,
            'type' => $request->type,
            'file_path' => $path,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('ops.clients.show', $request->client_id)->with('success', 'Document uploaded successfully.');
    }}
