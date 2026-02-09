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
        Schema::table('supplier_delivery_items', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_order_item_id')->nullable()->after('supplier_delivery_id');
            $table->integer('ecart')->default(0)->after('quantite_livree');
            $table->string('condition_emballage')->nullable()->after('ecart');
            $table->text('notes')->nullable()->after('condition_emballage');
            $table->text('compte_rendu')->nullable()->after('notes');
            $table->string('preuve_service')->nullable()->after('compte_rendu');
            $table->integer('satisfaction')->nullable()->after('preuve_service');
            
            // Add foreign key constraint
            $table->foreign('supplier_order_item_id')->references('id')->on('supplier_order_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_delivery_items', function (Blueprint $table) {
            $table->dropForeign(['supplier_order_item_id']);
            
            $table->dropColumn([
                'supplier_order_item_id',
                'ecart',
                'condition_emballage',
                'notes',
                'compte_rendu',
                'preuve_service',
                'satisfaction'
            ]);
        });
    }
};