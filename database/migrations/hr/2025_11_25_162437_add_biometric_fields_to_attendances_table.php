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
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('biometric_id')->nullable()->after('employee_id');
            $table->string('device_id')->nullable()->after('biometric_id');
            $table->string('device_name')->nullable()->after('device_id');
            $table->timestamp('biometric_timestamp')->nullable()->after('device_name');
            $table->enum('biometric_type', ['fingerprint', 'face', 'iris', 'card', 'pin'])->nullable()->after('biometric_timestamp');
            $table->json('biometric_data')->nullable()->after('biometric_type');
            
            // Add index for better performance
            $table->index(['biometric_id', 'date']);
            $table->index(['device_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['biometric_id', 'date']);
            $table->dropIndex(['device_id', 'date']);
            $table->dropColumn(['biometric_id', 'device_id', 'device_name', 'biometric_timestamp', 'biometric_type', 'biometric_data']);
        });
    }
};