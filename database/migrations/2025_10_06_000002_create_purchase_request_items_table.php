<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->string('product_id', 36)->nullable();
            $table->string('designation');
            $table->text('description')->nullable();
            $table->integer('quantite');
            $table->string('unite', 50)->default('unité');
            $table->decimal('prix_unitaire_estime', 15, 2)->default(0);
            $table->decimal('montant_total_estime', 15, 2)->default(0);
            $table->foreignId('fournisseur_suggere_id')->nullable()->constrained('fournisseurs')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('purchase_request_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_request_items');
    }
};