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
        Schema::create('stock_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->char('product_id', 36);
            $table->decimal('theoretical_quantity', 15, 2);
            $table->decimal('actual_quantity', 15, 2)->nullable();
            $table->decimal('difference', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('inventory_id');
            $table->index('product_id');
            
            // Foreign keys
            $table->foreign('inventory_id')->references('id')->on('stock_inventories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_inventory_items');
    }
};
