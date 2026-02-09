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
        if (!Schema::hasTable('validation_requests')) {
            Schema::create('validation_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('workflow_id'); // Remove foreign key constraint for now
                $table->string('entity_type'); // Model class name
                $table->unsignedBigInteger('entity_id'); // Model ID
                $table->unsignedBigInteger('company_id'); // Remove foreign key constraint for now
                $table->unsignedBigInteger('requested_by');
                $table->integer('current_step')->default(0);
                $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
                $table->text('reason')->nullable();
                $table->json('data_snapshot')->nullable(); // Snapshot of entity data at request time
                $table->json('validation_notes')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['entity_type', 'entity_id']);
                $table->index(['workflow_id', 'status']);
                $table->index(['company_id', 'status']);
                $table->index(['requested_by']);
                $table->index(['current_step']);
                
                // Add foreign key constraints later when the referenced tables exist
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_requests');
    }
};