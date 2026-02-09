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
        Schema::create('tax_regulations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->string('tax_type'); // VAT, IS, CNSS, etc.
            $table->decimal('rate', 5, 4)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
            
            // Add indexes for better performance
            $table->index(['company_id', 'is_active']);
            $table->index(['agency_id', 'is_active']);
            $table->index('tax_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_regulations');
    }
};