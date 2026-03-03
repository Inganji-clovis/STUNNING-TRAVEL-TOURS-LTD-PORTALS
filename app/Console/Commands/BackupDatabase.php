<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a database backup and cleanup old backups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');

        $date = Carbon::now()->format('Y_m_d_H_i_s');
        $filename = "backup__{$database}__{$date}.sql";
        $folderPath = storage_path('app' . DIRECTORY_SEPARATOR . 'backups');
        
        // Ensure the directory exists
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $path = $folderPath . DIRECTORY_SEPARATOR . $filename;
        
        // Construct the mysqldump command using full path
        $passwordCmd = empty($password) ? '' : "-p\"{$password}\"";
        
        $mysqldumpPath = '"C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe"';
        $command = "{$mysqldumpPath} -h {$host} -P {$port} -u {$username} {$passwordCmd} {$database} > \"{$path}\"";

        // Run the command
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Database backup failed!');
            return Command::FAILURE;
        }

        $this->info("Backup created successfully: {$filename}");

        // Cleanup old backups (older than 7 days)
        $this->info('Cleaning up old backups...');
        $files = glob($folderPath . DIRECTORY_SEPARATOR . 'backup__*.sql');

        foreach ($files as $file) {
            $lastModified = filemtime($file);
            if (Carbon::createFromTimestamp($lastModified)->isBefore(Carbon::now()->subDays(7))) {
                unlink($file);
                $this->info("Deleted old backup: " . basename($file));
            }
        }

        $this->info('Backup process completed.');
        return Command::SUCCESS;
    }
}
