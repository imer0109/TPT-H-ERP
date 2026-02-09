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
        Schema::create('supplier_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fournisseur_id');
            $table->string('contract_number')->unique();
            $table->string('contract_type')->nullable(); // cadre, spécifique, etc.
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('renewal_date')->nullable(); // Date de renouvellement automatique
            $table->boolean('auto_renewal')->default(false); // Renouvellement automatique
            $table->decimal('value', 15, 2)->nullable(); // Valeur du contrat
            $table->string('currency')->default('XOF'); // Devise
            $table->string('status')->default('active'); // active, expired, terminated
            $table->text('terms')->nullable(); // Conditions générales
            $table->text('special_conditions')->nullable(); // Conditions spéciales
            $table->unsignedBigInteger('responsible_id')->nullable(); // Responsable du contrat
            $table->date('last_review_date')->nullable(); // Date de dernière révision
            $table->text('notes')->nullable(); // Notes internes
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('cascade');
            $table->foreign('responsible_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_contracts');
    }
};