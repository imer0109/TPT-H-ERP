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
        Schema::table('teams', function (Blueprint $table) {
            // Add department_id column if it doesn't exist
            if (!Schema::hasColumn('teams', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('leader_id');
            }
        });
        
        // Add foreign key constraint
        Schema::table('teams', function (Blueprint $table) {
            // Check if foreign key already exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'teams'
                AND COLUMN_NAME = 'department_id'
                AND REFERENCED_TABLE_NAME = 'departments'
            ");
            
            if (empty($foreignKeys)) {
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};