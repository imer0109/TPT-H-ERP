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
        Schema::create('client_reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('type_reclamation', [
                'produit_defectueux',
                'retard_livraison',
                'erreur_facturation',
                'service_client',
                'qualite_produit',
                'autre'
            ]);
            $table->text('description');
            $table->enum('statut', ['ouverte', 'en_cours', 'resolue'])->default('ouverte');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_resolution')->nullable();
            $table->text('solution')->nullable();
            $table->text('commentaires')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_reclamations');
    }
};