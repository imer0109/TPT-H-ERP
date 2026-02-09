<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('validated_by')->constrained('users')->onDelete('cascade');
            $table->integer('validation_level')->default(1);
            $table->enum('statut', ['En attente', 'Approuvée', 'Rejetée'])->default('En attente');
            $table->text('commentaires')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->decimal('montant_limite', 15, 2)->nullable();
            $table->enum('type_validation', ['Montant', 'Famille', 'Profil'])->default('Montant');
            $table->timestamps();

            // Indexes
            $table->index(['purchase_request_id', 'validation_level']);
            $table->index(['validated_by', 'statut']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_validations');
    }
};