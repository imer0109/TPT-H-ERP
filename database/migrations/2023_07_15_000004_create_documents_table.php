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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('type_document', [
                'contrat',
                'bon_commande',
                'fiche_ouverture',
                'rccm',
                'niu',
                'piece_identite',
                'autre'
            ]);
            $table->string('chemin_fichier');
            $table->string('taille')->nullable();
            $table->string('format')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Champs pour la relation polymorphique
            $table->morphs('documentable');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};