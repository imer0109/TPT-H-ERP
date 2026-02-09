<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('numero_transfert')->unique();

            $table->unsignedBigInteger('warehouse_source_id');
            $table->unsignedBigInteger('warehouse_destination_id');
            // $table->unsignedBigInteger('product_id');

            $table->decimal('quantite', 10, 2);
            $table->string('unite');
            $table->enum('statut', ['en_attente', 'en_transit', 'receptionne', 'annule']);
            $table->string('justificatif')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();

            $table->timestamp('date_validation')->nullable();
            $table->timestamp('date_reception')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Clés étrangères
            $table->foreign('warehouse_source_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('warehouse_destination_id')->references('id')->on('warehouses')->onDelete('cascade');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('validated_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('received_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_transfers');
    }
}
