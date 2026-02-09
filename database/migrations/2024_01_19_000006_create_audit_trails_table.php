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
        // Check if the table doesn't already exist before creating it
        if (!Schema::hasTable('audit_trails')) {
            Schema::create('audit_trails', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('company_id')->nullable(); // Remove the foreign key constraint for now
                $table->string('auditable_type'); // Model class name
                $table->unsignedBigInteger('auditable_id'); // Model ID
                $table->string('event'); // created, updated, deleted, etc.
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->text('url')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('session_id')->nullable();
                $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['auditable_type', 'auditable_id']);
                $table->index(['user_id', 'created_at']);
                $table->index(['company_id', 'created_at']);
                $table->index(['event', 'risk_level']);
                $table->index(['ip_address']);
                $table->index(['created_at']);
                
                // Add the foreign key constraint only if the companies table exists
                // This will be added in a later migration
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};