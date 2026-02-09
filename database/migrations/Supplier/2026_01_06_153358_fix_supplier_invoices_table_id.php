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
        // Recréer la table supplier_invoices avec la bonne structure
        Schema::dropIfExists('supplier_invoices');
        
        Schema::create('supplier_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->foreignId('supplier_order_id')->nullable()->constrained('supplier_orders')->onDelete('set null');
            $table->string('numero_facture')->unique();
            $table->date('date_facture');
            $table->date('date_echeance');
            $table->decimal('montant_total', 15, 2);
            $table->decimal('montant_paye', 15, 2)->default(0.00);
            $table->string('devise', 3)->default('XAF');
            $table->string('statut')->default('pending');
            $table->string('fichier_facture')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_invoices', function (Blueprint $table) {
            //
        });
    }
};
