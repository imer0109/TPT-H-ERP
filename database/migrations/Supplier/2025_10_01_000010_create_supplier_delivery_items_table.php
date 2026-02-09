<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if the table doesn't already exist
        if (!Schema::hasTable('supplier_delivery_items')) {
            Schema::create('supplier_delivery_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('supplier_delivery_id')->constrained('supplier_deliveries')->onDelete('cascade');
                // Use uuid for product_id to match the products table
                $table->uuid('product_id');
                // Skip the foreign key constraint for now
                // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->integer('quantite_livree');
                $table->integer('quantite_commandee')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('supplier_delivery_items');
    }
};
