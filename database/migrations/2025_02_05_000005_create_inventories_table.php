<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        // Table des inventaires
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('numero_inventaire')->unique();
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'valide', 'annule'])->default('en_cours');
            $table->text('notes')->nullable();
            $table->string('pv_inventaire')->nullable(); // Chemin vers le PDF
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Table des items d'inventaire
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            // $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('stock_theorique', 10, 2);
            $table->decimal('stock_physique', 10, 2);
            $table->decimal('ecart', 10, 2);
            $table->decimal('prix_unitaire', 15, 2);
            $table->decimal('valeur_ecart', 15, 2);
            $table->string('justification_ecart')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventories');
    }
}
