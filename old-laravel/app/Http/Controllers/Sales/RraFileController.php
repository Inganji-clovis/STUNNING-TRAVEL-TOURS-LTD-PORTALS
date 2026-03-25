<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RraFileController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\RraFile::with('uploader');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('type_filter')) {
            $query->where('type', $request->type_filter);
        }

        $files = $query->latest()->get();
        return view('sales.rra.index', compact('files'));
    }

    public function create()
    {
        return view('sales.rra.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ebm,ibm,rra_monthly',
            'month' => 'required|string',
            'year' => 'required|integer|min:2020',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:20480',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('private/rra_vault/' . $request->year . '/' . $request->type);

        \App\Models\RraFile::create([
            'type' => $request->type,
            'month' => $request->month,
            'year' => $request->year,
            'description' => $request->description,
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);
        \App\Services\ActivityLogger::log('Sales', 'RRA Vault', 'Create', "{$request->type} {$request->month}/{$request->year}", "Archived {$request->type} for {$request->month}/{$request->year}");

        return redirect()->route('sales.rra.index')->with('success', 'RRA document archived successfully.');
    }

    public function destroy(Request $request, \App\Models\RraFile $rra)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Only administrators can delete RRA files.');
        }

        \App\Services\ActivityLogger::log('Sales', 'RRA Vault', 'Delete', "{$rra->type} {$rra->month}/{$rra->year}", "Deleted {$rra->type} for {$rra->month}/{$rra->year}");

        \Illuminate\Support\Facades\Storage::delete($rra->file_path);
        $rra->delete();

        return redirect()->route('sales.rra.index')->with('success', 'RRA document deleted securely.');
    }}
