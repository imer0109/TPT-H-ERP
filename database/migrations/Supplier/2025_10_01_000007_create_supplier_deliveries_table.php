<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_order_id')->nullable()->constrained('supplier_orders')->nullOnDelete();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses');
            $table->date('date_livraison');
            $table->enum('statut', ['partiel','total','annule'])->default('partiel');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_deliveries');
    }
};


