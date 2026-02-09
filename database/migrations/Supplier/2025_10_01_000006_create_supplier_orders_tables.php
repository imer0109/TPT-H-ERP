<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs');
            $table->foreignId('agency_id')->nullable()->constrained('agencies');
            $table->string('code')->unique();
            $table->date('date_commande');
            $table->enum('statut', ['commande', 'livre_partiel', 'livre_total', 'annule'])->default('commande');
            $table->decimal('montant_ht', 15, 2)->default(0);
            $table->decimal('montant_tva', 15, 2)->default(0);
            $table->decimal('montant_ttc', 15, 2)->default(0);
            $table->string('devise', 10)->default('XOF');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('supplier_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_order_id')->constrained('supplier_orders')->onDelete('cascade');
            $table->uuid('product_id')->nullable();
            $table->string('designation');
            $table->decimal('quantite', 15, 3);
            $table->string('unite')->default('U');
            $table->decimal('prix_unitaire', 15, 2);
            $table->decimal('montant_total', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_order_items');
        Schema::dropIfExists('supplier_orders');
    }
};


