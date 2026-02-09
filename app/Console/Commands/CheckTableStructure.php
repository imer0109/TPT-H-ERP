<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckTableStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-table-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check table structure for supplier invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $columns = Schema::getColumnListing('supplier_invoices');
        $this->info('Columns in supplier_invoices table:');
        foreach ($columns as $column) {
            $this->line('- ' . $column);
        }
        
        $this->info('\nChecking if supplier_orders table uses UUIDs or integers:');
        $orderColumns = Schema::getColumnListing('supplier_orders');
        foreach ($orderColumns as $column) {
            $this->line('- ' . $column);
        }
        
        $this->info('\nChecking if fournisseurs table uses UUIDs or integers:');
        $fournisseurColumns = Schema::getColumnListing('fournisseurs');
        foreach ($fournisseurColumns as $column) {
            $this->line('- ' . $column);
        }
    }
}
