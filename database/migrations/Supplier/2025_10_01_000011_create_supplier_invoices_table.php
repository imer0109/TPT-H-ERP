<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if the table doesn't already exist
        if (!Schema::hasTable('supplier_invoices')) {
            Schema::create('supplier_invoices', function (Blueprint $table) {
                $table->uuid('id')->primary();
                // Use regular uuid columns instead of foreignUuid
                $table->uuid('fournisseur_id');
                $table->uuid('supplier_order_id')->nullable();
                // Skip the foreign key constraints for now
                // $table->foreign('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
                // $table->foreign('supplier_order_id')->nullable()->constrained('supplier_orders')->onDelete('set null');
                $table->string('numero_facture')->unique();
                $table->date('date_facture');
                $table->date('date_echeance');
                $table->decimal('montant_total', 15, 2);
                $table->decimal('montant_paye', 15, 2)->default(0.00);
                $table->string('devise', 3)->default('XAF');
                $table->string('statut')->default('pending'); // pending, partially_paid, paid, overdue
                $table->string('fichier_facture')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('supplier_invoices');
    }
};
