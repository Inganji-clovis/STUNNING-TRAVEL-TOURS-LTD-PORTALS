<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AdminFileController extends Controller
{
    public function index(Request $request)
    {
        $allFiles = Storage::disk('public')->allFiles();
        
        $files = [];
        foreach ($allFiles as $filePath) {
            $files[] = [
                'name' => basename($filePath),
                'path' => $filePath,
                'directory' => dirname($filePath),
                'size' => Storage::disk('public')->size($filePath),
                'last_modified' => Storage::disk('public')->lastModified($filePath),
            ];
        }

        if ($search = $request->input('search')) {
            $files = array_filter($files, function($file) use ($search) {
                return stripos($file['name'], $search) !== false || stripos($file['path'], $search) !== false;
            });
        }

        $files = collect($files)->sortByDesc('last_modified');
        
        $perPage = 30;
        $currentPage = Paginator::resolveCurrentPage();
        $currentItems = $files->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedFiles = new LengthAwarePaginator($currentItems, count($files), $perPage, $currentPage, ['path' => Paginator::resolveCurrentPath()]);

        return view('admin.files.index', ['files' => $paginatedFiles]);
    }

    public function destroy(Request $request)
    {
        $path = $request->input('path');
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted_system_file',
                'description' => 'Deleted file: ' . basename($path),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return back()->with('success', 'File permanently removed from system.');
        }

        return back()->with('error', 'File not found or access restricted.');
    }
}
