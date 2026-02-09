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
        if (!Schema::hasTable('validation_workflows')) {
            Schema::create('validation_workflows', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('module'); // accounting, purchases, inventory, etc.
                $table->string('entity_type'); // Model class name
                $table->unsignedBigInteger('company_id'); // Remove foreign key constraint for now
                $table->json('conditions')->nullable(); // Conditions to trigger workflow
                $table->json('steps'); // Validation steps configuration
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->index(['module', 'entity_type']);
                $table->index(['company_id', 'is_active']);
                
                // Add foreign key constraint later when companies table exists
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_workflows');
    }
};