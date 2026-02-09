<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamation_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained();
            $table->foreignId('commande_id')->nullable();
            $table->foreignId('livraison_id')->nullable();
            $table->string('type');
            $table->string('objet');
            $table->text('description');
            $table->date('date_reclamation');
            $table->string('statut')->default('ouverte');
            $table->string('priorite')->default('moyenne');
            $table->date('date_resolution')->nullable();
            $table->text('resolution')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('responsable_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamation_fournisseurs');
    }
};