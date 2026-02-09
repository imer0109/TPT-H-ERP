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
        Schema::table('supplier_order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_order_items', 'designation')) {
                $table->string('designation')->after('product_id');
            }
            if (!Schema::hasColumn('supplier_order_items', 'description')) {
                $table->text('description')->nullable()->after('designation');
            }
            if (!Schema::hasColumn('supplier_order_items', 'unite')) {
                $table->string('unite')->nullable()->after('quantite');
            }
            if (!Schema::hasColumn('supplier_order_items', 'tva_rate')) {
                $table->decimal('tva_rate', 5, 2)->default(18.00)->after('prix_unitaire');
            }
            if (!Schema::hasColumn('supplier_order_items', 'tva_amount')) {
                $table->decimal('tva_amount', 10, 2)->default(0.00)->after('tva_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_order_items', function (Blueprint $table) {
            $table->dropColumn([
                'designation',
                'description',
                'unite',
                'tva_rate',
                'tva_amount'
            ]);
        });
    }
};
