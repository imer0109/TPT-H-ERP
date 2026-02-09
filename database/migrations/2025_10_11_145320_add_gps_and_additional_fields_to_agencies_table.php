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
        Schema::table('agencies', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('agencies', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('zone_geographique');
            }
            
            if (!Schema::hasColumn('agencies', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (Schema::hasColumn('agencies', 'latitude')) {
                $table->dropColumn('latitude');
            }
            
            if (Schema::hasColumn('agencies', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};