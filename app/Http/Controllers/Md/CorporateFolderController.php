<?php

namespace App\Http\Controllers\Md;

use App\Http\Controllers\Controller;
use App\Models\CorporateFolder;
use App\Models\CorporateFile;
use App\Models\Client;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CorporateFolderController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'client');
        $search = $request->get('search');

        $query = CorporateFolder::where('type', $type)->with(['client', 'organization', 'creator']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $folders = $query->latest()->get();

        return view('md.folders.index', compact('folders', 'type', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:client,contract,letter',
            'client_id' => 'nullable|exists:clients,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'year' => 'nullable|string',
        ]);

        CorporateFolder::create([
            'name' => $request->name,
            'type' => $request->type,
            'client_id' => $request->client_id,
            'organization_id' => $request->organization_id,
            'year' => $request->year ?? date('Y'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('md.folders.index', ['type' => $request->type])
            ->with('success', ucfirst($request->type) . ' folder created successfully.');
    }

    public function show(CorporateFolder $folder)
    {
        $folder->load(['files.uploader', 'client', 'organization']);
        
        $availableFiles = [];
        
        foreach(\App\Models\Ebm::all() as $ebm) {
            $availableFiles[] = [
                'id' => 'ebm_' . $ebm->id,
                'name' => 'EBM: ' . $ebm->company_name . ' (' . $ebm->tin_number . ')',
                'path' => $ebm->file_path,
                'source' => 'Sales / EBM Registry'
            ];
        }

        foreach(\App\Models\TaxCompliance::all() as $tax) {
            if ($tax->declaration_file) {
                $availableFiles[] = [
                    'id' => 'tax_decl_' . $tax->id,
                    'name' => ucfirst($tax->type) . ' Tax Declaration (' . $tax->declaration_number . ')',
                    'path' => $tax->declaration_file,
                    'source' => 'Sales / Tax Hub'
                ];
            }
            if ($tax->receipt_file) {
                $availableFiles[] = [
                    'id' => 'tax_rect_' . $tax->id,
                    'name' => ucfirst($tax->type) . ' Tax Receipt (' . $tax->declaration_number . ')',
                    'path' => $tax->receipt_file,
                    'source' => 'Sales / Tax Hub'
                ];
            }
        }
        
        foreach(\App\Models\RraFile::all() as $rra) {
            $availableFiles[] = [
                'id' => 'rra_' . $rra->id,
                'name' => 'RRA File: ' . ucfirst($rra->type) . ' ' . $rra->month . '/' . $rra->year,
                'path' => $rra->file_path,
                'source' => 'Sales / RRA Vault'
            ];
        }

        foreach(\App\Models\Ticket::all() as $ticket) {
            $availableFiles[] = [
                'id' => 'ticket_' . $ticket->id,
                'name' => 'Ticket: ' . $ticket->ticket_number . ' for ' . $ticket->client_names,
                'path' => $ticket->file_path,
                'source' => 'Operations / Tickets'
            ];
        }

        $availableFiles = collect($availableFiles)->filter(function($f) {
            if (empty($f['path'])) return false;
            return Storage::disk('local')->exists($f['path']) || Storage::disk('public')->exists($f['path']);
        })->sortByDesc('id')->values();

        return view('md.folders.show', compact('folder', 'availableFiles'));
    }

    public function upload(Request $request, CorporateFolder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:20480',
            'file_type' => 'required|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('md_vault/' . $folder->type . '/' . $folder->id, 'local');

        CorporateFile::create([
            'folder_id' => $folder->id,
            'name' => $request->name,
            'file_path' => $path,
            'file_type' => $request->file_type,
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'File uploaded to folder.');
    }

    public function import(Request $request, CorporateFolder $folder)
    {
        $request->validate([
            'files' => 'nullable|array',
            'files.*' => 'file|max:20480',
            'cross_portal_files' => 'nullable|array',
        ]);

        $count = 0;
        
        // Handle physical file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $path = $file->store('md_vault/' . $folder->type . '/' . $folder->id, 'local');

                CorporateFile::create([
                    'folder_id' => $folder->id,
                    'name' => $name,
                    'file_path' => $path,
                    'file_type' => 'imported',
                    'uploaded_by' => auth()->id(),
                ]);
                $count++;
            }
        }

        // Handle cross-portal file imports
        if ($request->filled('cross_portal_files')) {
            foreach ($request->cross_portal_files as $cpFile) {
                // $cpFile comes in as "source||name||path"
                $parts = explode('||', $cpFile);
                if (count($parts) === 3) {
                    $source = $parts[0];
                    $name = $parts[1];
                    $originalPath = $parts[2];

                    $disk = Storage::disk('local')->exists($originalPath) ? 'local' : 'public';
                    if (Storage::disk($disk)->exists($originalPath)) {
                        $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
                        $newPath = 'md_vault/' . $folder->type . '/' . $folder->id . '/' . uniqid() . '.' . $extension;
                        
                        $fileContent = Storage::disk($disk)->get($originalPath);
                        Storage::disk('local')->put($newPath, $fileContent);

                        CorporateFile::create([
                            'folder_id' => $folder->id,
                            'name' => $name . ' (Imported)',
                            'file_path' => $newPath,
                            'file_type' => 'cross_portal_import',
                            'uploaded_by' => auth()->id(),
                        ]);
                        $count++;
                    }
                }
            }
        }

        return back()->with('success', "Successfully imported {$count} file(s) to folder.");
    }

    public function destroy(CorporateFolder $folder)
    {
        // Protect foundational or non-empty folders if needed, or allow recursive deletion
        $type = $folder->type;
        $folderName = $folder->name;
        
        // Ensure files are deleted from storage
        foreach ($folder->files as $file) {
            $disk = Storage::disk('local')->exists($file->file_path) ? 'local' : 'public';
            Storage::disk($disk)->delete($file->file_path);
            $file->delete();
        }

        $folder->delete();

        return redirect()->route('md.folders.index', ['type' => $type])
            ->with('success', "Folder '{$folderName}' was successfully permanently deleted.");
    }
}
