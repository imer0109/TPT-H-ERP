<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\ExcelServiceProvider as BaseExcelServiceProvider;

class LegacyExcelServiceProvider extends BaseExcelServiceProvider
{
    /**
     * Bootstrap the application events without calling deprecated package().
     *
     * @return void
     */
    public function boot()
    {
        // Skip $this->package('maatwebsite/excel');
        // $this->setAutoSizingSettings(); // Removed as this method doesn't exist in the parent class
        parent::boot(); // Call the parent boot method instead
    }
}