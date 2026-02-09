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
        // Check if manager_id column exists, if not create it
        if (!Schema::hasColumn('users', 'manager_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('company_id');
            });
        }
        
        // Add foreign key constraint
        Schema::table('users', function (Blueprint $table) {
            // Check if foreign key already exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'users'
                AND COLUMN_NAME = 'manager_id'
                AND REFERENCED_TABLE_NAME = 'users'
            ");
            
            if (empty($foreignKeys)) {
                $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['manager_id']);
        });
    }
};