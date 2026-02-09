<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs');
            $table->date('date_paiement');
            $table->enum('mode', ['espece','virement','cheque','carte','autre'])->default('virement');
            $table->decimal('montant', 15, 2);
            $table->string('devise', 10)->default('XOF');
            $table->string('justificatif')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};


