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
        // Enhance roles table with security features
        Schema::table('roles', function (Blueprint $table) {
            // Check if columns don't already exist before adding them
            if (!Schema::hasColumn('roles', 'slug')) {
                $table->string('slug')->unique()->after('nom');
            }
            if (!Schema::hasColumn('roles', 'color')) {
                $table->string('color', 7)->default('#6366f1')->after('description');
            }
            if (!Schema::hasColumn('roles', 'is_system')) {
                $table->boolean('is_system')->default(false)->after('color');
            }
            if (!Schema::hasColumn('roles', 'is_temporary')) {
                $table->boolean('is_temporary')->default(false)->after('is_system');
            }
            if (!Schema::hasColumn('roles', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('is_temporary');
            }
            // Only add the company_id column if the companies table exists
            if (!Schema::hasColumn('roles', 'company_id') && Schema::hasTable('companies')) {
                $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade')->after('expires_at');
            } else if (!Schema::hasColumn('roles', 'company_id')) {
                // If companies table doesn't exist yet, just add the column without the foreign key
                $table->unsignedBigInteger('company_id')->nullable()->after('expires_at');
            }
            if (!Schema::hasColumn('roles', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('company_id');
            }
            if (!Schema::hasColumn('roles', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('roles', 'deleted_at')) {
                $table->softDeletes();
            }

            // Add indexes (these will fail silently if they already exist)
            try {
                $table->index(['company_id', 'is_system']);
            } catch (\Exception $e) {
                // Index may already exist
            }
            try {
                $table->index(['is_temporary', 'expires_at']);
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // Enhance permissions table with security features
        Schema::table('permissions', function (Blueprint $table) {
            // Check if columns don't already exist before adding them
            if (!Schema::hasColumn('permissions', 'slug')) {
                $table->string('slug')->unique()->after('nom');
            }
            if (!Schema::hasColumn('permissions', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('permissions', 'resource')) {
                $table->string('resource')->after('module'); // entries, requests, products, etc.
            }
            if (!Schema::hasColumn('permissions', 'is_system')) {
                $table->boolean('is_system')->default(true)->after('action');
            }
            if (!Schema::hasColumn('permissions', 'requires_validation')) {
                $table->boolean('requires_validation')->default(false)->after('is_system');
            }
            if (!Schema::hasColumn('permissions', 'validation_level')) {
                $table->integer('validation_level')->default(1)->after('requires_validation'); // 1 = simple, 2 = complex
            }

            // Add indexes (these will fail silently if they already exist)
            try {
                $table->index(['module', 'resource', 'action']);
            } catch (\Exception $e) {
                // Index may already exist
            }
            try {
                $table->index(['requires_validation', 'validation_level']);
            } catch (\Exception $e) {
                // Index may already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'color', 'is_system', 'is_temporary', 'expires_at',
                'company_id', 'created_by', 'updated_by'
            ]);
            $table->dropSoftDeletes();
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'description', 'resource', 'is_system',
                'requires_validation', 'validation_level'
            ]);
        });
    }
};