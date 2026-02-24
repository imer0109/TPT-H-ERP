<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('numero_mouvement')->unique();
            $table->foreignId('warehouse_id')->constrained();
            // $table->foreignId('product_id')->constrained();
            $table->enum('type', ['entree', 'sortie', 'transfert']);
            $table->enum('source', ['achat', 'production', 'don', 'vente', 'consommation', 'perte', 'transfert']);
            $table->decimal('quantite', 10, 2);
            $table->string('unite');
            $table->decimal('prix_unitaire', 15, 2);
            $table->decimal('montant_total', 15, 2);

            // Pour fournisseur ou autre source
            $table->nullableMorphs('source_entity', 'src_entity_idx'); 

            // Pour client ou autre destination
            $table->nullableMorphs('destination_entity', 'dest_entity_idx'); 

            $table->string('justificatif')->nullable();
            $table->text('motif')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
