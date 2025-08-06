<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAlertsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            
            // $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id');
            
            $table->decimal('seuil_minimum', 10, 2);
            $table->decimal('seuil_securite', 10, 2);
            $table->boolean('alerte_active')->default(true);
            $table->string('email_notification')->nullable();
            
            $table->unsignedBigInteger('created_by');
            
            $table->timestamps();
            $table->softDeletes();

            // Relations
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_alerts');
    }
}
