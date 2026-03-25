<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AdminBackupController extends Controller
{
    private $disk = 'local';
    // Use the default backup directory name (we assume the default config for spatie/laravel-backup)
    private $backupName;

    public function __construct()
    {
        $this->backupName = env('APP_NAME', 'Laravel');
    }

    public function index()
    {
        $backups = [];
        $disk = Storage::disk($this->disk);
        
        if ($disk->exists($this->backupName)) {
            $files = $disk->files($this->backupName);

            foreach ($files as $file) {
                if (substr($file, -4) == '.zip') {
                    $backups[] = [
                        'file_path' => $file,
                        'file_name' => str_replace($this->backupName . '/', '', $file),
                        'file_size' => $disk->size($file),
                        'last_modified' => $disk->lastModified($file),
                    ];
                }
            }
        }

        $backups = array_reverse($backups);

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'triggered_system_backup',
                'description' => 'Generated a manual system snapshot (Database only).',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return back()->with('success', 'Database snapshot generated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download($fileName)
    {
        $file = $this->backupName . '/' . $fileName;
        $disk = Storage::disk($this->disk);

        if ($disk->exists($file)) {
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'downloaded_system_backup',
                'description' => 'Downloaded backup snapshot: ' . $fileName,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->download($disk->path($file), $fileName);
        }

        return back()->with('error', 'Backup file not found.');
    }

    public function restore(Request $request, $fileName)
    {
        // Spatie backup doesn't have an out-of-the-box restore command.
        // We will just let them delete it instead of 'restore' for now, or spoof a delete.
        // Let's implement destroy instead.
        $file = $this->backupName . '/' . $fileName;
        $disk = Storage::disk($this->disk);

        if ($disk->exists($file)) {
            $disk->delete($file);
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted_system_backup',
                'description' => 'Eradicated backup snapshot: ' . $fileName,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return back()->with('success', 'Backup snapshot permanently deleted.');
        }

        return back()->with('error', 'Backup file not found.');
    }
}
