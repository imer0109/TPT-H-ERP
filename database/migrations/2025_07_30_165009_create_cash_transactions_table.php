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
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_id')->constrained()->onDelete('cascade');
            // $table->foreignId('cash_session_id')->constrained('sessions')->onDelete('cascade');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero_transaction')->unique();
            $table->enum('type', ['encaissement', 'decaissement']);
            $table->decimal('montant', 15, 2);
            $table->string('libelle');
            $table->string('nature_operation')->nullable();
            $table->string('mode_paiement')->nullable();
            $table->string('justificatif')->nullable();
            $table->string('projet')->nullable();
            $table->json('champs_personnalises')->nullable();
 
            $table->foreignId('validateur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_validation')->nullable();

            // morphs for `tiers` and `operation`
            $table->nullableMorphs('tiers');
            $table->nullableMorphs('operation');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
