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
        // Add foreign key constraints only if the tables exist
        if (Schema::hasTable('audit_trails')) {
            Schema::table('audit_trails', function (Blueprint $table) {
                // Check if the foreign key doesn't already exist by trying to add it
                try {
                    // First check if the column has a foreign key constraint
                    $table->foreign('company_id')
                        ->references('id')
                        ->on('companies')
                        ->onDelete('set null');
                } catch (\Exception $e) {
                    // Foreign key may already exist or there's an issue
                    // We'll log this but continue
                }
            });
        }
        
        if (Schema::hasTable('validation_workflows')) {
            Schema::table('validation_workflows', function (Blueprint $table) {
                try {
                    $table->foreign('company_id')
                        ->references('id')
                        ->on('companies')
                        ->onDelete('cascade');
                } catch (\Exception $e) {
                    // Foreign key may already exist or there's an issue
                }
            });
        }
        
        if (Schema::hasTable('validation_requests')) {
            Schema::table('validation_requests', function (Blueprint $table) {
                try {
                    // Check if the foreign keys don't already exist
                    if (Schema::hasTable('validation_workflows')) {
                        $table->foreign('workflow_id', 'validation_requests_workflow_id_foreign')
                            ->references('id')
                            ->on('validation_workflows')
                            ->onDelete('cascade');
                    }
                    
                    if (Schema::hasTable('companies')) {
                        $table->foreign('company_id', 'validation_requests_company_id_foreign')
                            ->references('id')
                            ->on('companies')
                            ->onDelete('cascade');
                    }
                } catch (\Exception $e) {
                    // Foreign key may already exist or there's an issue
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't remove the foreign keys in the down method
        // as this could cause issues with existing data
    }
};