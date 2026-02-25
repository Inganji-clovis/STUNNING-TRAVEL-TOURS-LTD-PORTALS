<?php

namespace App\Http\Controllers\Md;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CorporateDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\CorporateDocument::with('uploader');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->get();
        return view('md.vault.index', compact('documents'));
    }

    public function create()
    {
        return view('md.vault.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:board,legal,tax,policy,other',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:51200',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('private/corporate_vault/' . $request->category);

        \App\Models\CorporateDocument::create([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('md.vault.index')->with('success', 'Corporate document archived securely.');
    }}
