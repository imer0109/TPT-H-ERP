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
        Schema::create('api_data_mappings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('connector_id', 36);
            $table->enum('entity_type', [
                'accounting', 'customer', 'supplier', 'product', 'employee',
                'sale', 'purchase', 'payment', 'payroll', 'stock', 'cash'
            ]);
            $table->string('external_field'); // Field name in external system
            $table->string('internal_field'); // Field name in ERP
            $table->enum('field_type', [
                'string', 'integer', 'float', 'boolean', 'date', 'datetime',
                'email', 'phone', 'currency', 'percentage', 'json', 'array'
            ])->default('string');
            $table->json('transformation_rules')->nullable(); // Rules for data transformation
            $table->json('validation_rules')->nullable(); // Rules for data validation
            $table->boolean('is_required')->default(false);
            $table->text('default_value')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('connector_id')->references('id')->on('api_connectors')->onDelete('cascade');
            
            $table->index(['connector_id', 'entity_type']);
            $table->index(['connector_id', 'is_active']);
            $table->unique(['connector_id', 'entity_type', 'external_field'], 'unique_connector_entity_field');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_data_mappings');
    }
};