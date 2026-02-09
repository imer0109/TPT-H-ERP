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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('biometric_id')->nullable()->after('id');
            $table->time('schedule_start')->nullable()->after('status');
            $table->time('schedule_end')->nullable()->after('schedule_start');
            
            // Add index for better performance
            $table->index('biometric_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['biometric_id']);
            $table->dropColumn(['biometric_id', 'schedule_start', 'schedule_end']);
        });
    }
};