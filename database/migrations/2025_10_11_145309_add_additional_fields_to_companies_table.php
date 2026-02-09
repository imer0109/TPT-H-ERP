<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('companies', 'visuel')) {
                $table->string('visuel')->nullable()->after('logo');
            }
            
            // The 'active' column might already exist, so we'll skip it if it does
            if (!Schema::hasColumn('companies', 'active')) {
                $table->boolean('active')->default(true)->after('logo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'visuel')) {
                $table->dropColumn('visuel');
            }
            
            if (Schema::hasColumn('companies', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};