<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('supplier_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_request_id')->nullable()->after('id');
            $table->foreign('purchase_request_id')->references('id')->on('purchase_requests')->onDelete('set null');
            $table->string('nature_achat')->default('Bien')->after('statut'); // 'Bien' ou 'Service'
            $table->string('adresse_livraison')->nullable()->after('notes');
            $table->date('delai_contractuel')->nullable()->after('adresse_livraison');
            $table->string('conditions_paiement')->nullable()->after('delai_contractuel');
        });
    }

    public function down()
    {
        Schema::table('supplier_orders', function (Blueprint $table) {
            $table->dropForeign(['purchase_request_id']);
            $table->dropColumn(['purchase_request_id', 'nature_achat', 'adresse_livraison', 'delai_contractuel', 'conditions_paiement']);
        });
    }
};