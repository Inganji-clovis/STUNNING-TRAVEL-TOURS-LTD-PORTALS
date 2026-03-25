<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PurgeSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:purge {--force}';
    protected $description = 'Permanently delete all transactional and master test data';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('This will PERMANENTLY delete all test data. Do you wish to continue?', false)) {
            $this->error('Aborted.');
            return;
        }

        $this->info('Starting system purge...');

        $tables = [
            'activity_logs',
            'invoice_items',
            'billing_documents',
            'client_payments',
            'ebms',
            'tax_compliances',
            'rra_files',
            'md_dispatches',
            'tickets',
            'travel_documents',
            'clients',
            'organizations',
            'corporate_files',
            'corporate_folders',
            'cloud_syncs',
            'cash_transactions',
            'cash_days',
            'cash_categories',
        ];

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                $count = \Illuminate\Support\Facades\DB::table($table)->count();
                \Illuminate\Support\Facades\DB::table($table)->truncate();
                $this->line("<info>Purged:</info> {$table} ({$count} records)");
            }
        }

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('System purge completed successfully.');
    }
}
