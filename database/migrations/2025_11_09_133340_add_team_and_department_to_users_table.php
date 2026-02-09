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
        // Check if columns exist, if not create them
        if (!Schema::hasColumn('users', 'team_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('team_id')->nullable()->after('manager_id');
            });
        }
        
        if (!Schema::hasColumn('users', 'department_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('department_id')->nullable()->after('team_id');
            });
        }
        
        // Add foreign key constraints
        Schema::table('users', function (Blueprint $table) {
            // Check if foreign keys already exist
            $teamForeignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'users'
                AND COLUMN_NAME = 'team_id'
                AND REFERENCED_TABLE_NAME = 'teams'
            ");
            
            if (empty($teamForeignKeys)) {
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
            }
            
            $departmentForeignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'users'
                AND COLUMN_NAME = 'department_id'
                AND REFERENCED_TABLE_NAME = 'departments'
            ");
            
            if (empty($departmentForeignKeys)) {
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['team_id', 'department_id']);
        });
    }
};