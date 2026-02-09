<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->enum('nature_achat', ['Bien', 'Service']);
            $table->string('designation');
            $table->text('justification')->nullable();
            $table->date('date_demande');
            $table->date('date_echeance_souhaitee')->nullable();
            $table->enum('statut', ['Brouillon', 'En attente', 'Validée', 'Refusée', 'Convertie en BOC', 'Annulée'])->default('Brouillon');
            $table->decimal('prix_estime_total', 15, 2)->default(0);
            $table->foreignId('fournisseur_suggere_id')->nullable()->constrained('fournisseurs')->onDelete('set null');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->text('validation_comments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('code');
            $table->index('statut');
            $table->index(['company_id', 'agency_id']);
            $table->index(['requested_by', 'date_demande']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_requests');
    }
};