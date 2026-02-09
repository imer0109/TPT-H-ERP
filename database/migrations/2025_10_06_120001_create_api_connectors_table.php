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
        Schema::create('api_connectors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', [
                'sage', 'ebp', 'google_sheets', 'excel', 'pos', 
                'payroll', 'crm', 'custom'
            ]);
            $table->text('description')->nullable();
            $table->json('configuration')->nullable(); // API URLs, credentials, etc.
            $table->json('mapping_rules')->nullable(); // Field mapping configuration
            $table->integer('sync_frequency')->default(1440); // in minutes
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamp('next_sync_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'error', 'syncing'])->default('inactive');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['company_id', 'type']);
            $table->index(['status', 'is_active']);
            $table->index('next_sync_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_connectors');
    }
};