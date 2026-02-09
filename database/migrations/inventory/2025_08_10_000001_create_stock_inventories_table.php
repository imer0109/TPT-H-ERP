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
        Schema::create('stock_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['en_cours', 'valide', 'annule'])->default('en_cours');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('stock_inventories')->onDelete('cascade');
            // $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('theoretical_quantity', 10, 2)->default(0);
            $table->decimal('actual_quantity', 10, 2)->nullable();
            $table->decimal('difference', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_inventory_items');
        Schema::dropIfExists('stock_inventories');
    }
};