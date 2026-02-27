<?php

namespace App\Http\Controllers\Ops;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\TravelDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PassportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'individual');
        $search = $request->get('search');

        $query = TravelDocument::where('type', 'passport')->with('client');

        if ($type === 'individual') {
            $query->whereHas('client', function($q) {
                $q->whereNull('organization_id');
            });
        } else {
            $query->whereHas('client', function($q) {
                $q->whereNotNull('organization_id');
            });
        }

        if ($search) {
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('passport_number', 'like', "%{$search}%")
                  ->orWhere('nationality', 'like', "%{$search}%")
                  ->orWhereHas('client', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $documents = $query->latest()->get();

        return view('ops.passport.index', compact('documents', 'type', 'search'));
    }

    public function createIndividual(Request $request)
    {
        $client_name = $request->get('client_name');
        return view('ops.passport.create_individual', compact('client_name'));
    }

    public function createOrganization()
    {
        $organizations = \App\Models\Organization::all();
        return view('ops.passport.create_organization', compact('organizations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_names' => 'required|string|max:255',
            'passport_number' => 'required|string|max:50',
            'country_code' => 'required|string|max:10',
            'nationality' => 'required|string|max:100',
            'dob' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'date_of_expiry' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'passport_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:5120',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        // Find or Create Client
        $client = Client::firstOrCreate(
            ['name' => $request->full_names],
            [
                'organization_id' => $request->organization_id,
                'registered_by' => auth()->id(),
            ]
        );

        $filePath = $request->file('passport_file')->store('passports', 'local');

        TravelDocument::create([
            'client_id' => $client->id,
            'type' => 'passport',
            'passport_number' => $request->passport_number,
            'country_code' => $request->country_code,
            'nationality' => $request->nationality,
            'dob' => $request->dob,
            'place_of_birth' => $request->place_of_birth,
            'expiry_date' => $request->date_of_expiry,
            'gender' => $request->gender,
            'file_path' => $filePath,
        ]);

        \App\Services\ActivityLogger::log('Operations', 'Passport Records', 'Create', $request->passport_number, "Stored passport for {$request->full_names}");

        return redirect()->route('ops.passport.index', ['type' => $request->organization_id ? 'organization' : 'individual'])
            ->with('success', 'Passport document recorded successfully.');
    }

    public function edit(TravelDocument $document)
    {
        return view('ops.passport.edit', compact('document'));
    }

    public function update(Request $request, TravelDocument $document)
    {
        $request->validate([
            'full_names' => 'required|string|max:255',
            'passport_number' => 'required|string|max:50',
            'country_code' => 'required|string|max:10',
            'nationality' => 'required|string|max:100',
            'dob' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'date_of_expiry' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'passport_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:5120',
        ]);

        // Update Client Name
        $document->client->update(['name' => $request->full_names]);

        // Handle Optional File Replacement
        if ($request->hasFile('passport_file')) {
            if ($document->file_path) {
                Storage::disk('local')->delete($document->file_path);
            }
            $document->file_path = $request->file('passport_file')->store('passports', 'local');
        }

        $document->update([
            'passport_number' => $request->passport_number,
            'country_code' => $request->country_code,
            'nationality' => $request->nationality,
            'dob' => $request->dob,
            'place_of_birth' => $request->place_of_birth,
            'expiry_date' => $request->date_of_expiry,
            'gender' => $request->gender,
        ]);

        \App\Services\ActivityLogger::log('Operations', 'Passport Records', 'Update', $request->passport_number, "Modified passport credentials for {$request->full_names}");

        return redirect()->route('ops.passport.index', ['type' => $document->client->organization_id ? 'organization' : 'individual'])
            ->with('success', 'Passport credentials updated successfully.');
    }

    public function destroy(TravelDocument $document)
    {
        $passportNumber = $document->passport_number;
        if ($document->file_path) {
            Storage::disk('local')->delete($document->file_path);
        }
        $document->delete();

        \App\Services\ActivityLogger::log('Operations', 'Passport Records', 'Delete', $passportNumber, "Deleted passport document");

        return back()->with('success', 'Passport document deleted.');
    }
}
