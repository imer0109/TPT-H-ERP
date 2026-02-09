<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_movements') && ! Schema::hasColumn('stock_movements', 'product_id')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->uuid('product_id')->nullable()->after('warehouse_id');
                // Skip the foreign key constraint for now
                // We'll add it later when we're sure the data is consistent
                // $table->foreign('product_id', 'stock_movements_product_id_fk')
                //     ->references('id')->on('products')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stock_movements') && Schema::hasColumn('stock_movements', 'product_id')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                // $table->dropForeign('stock_movements_product_id_fk');
                $table->dropColumn('product_id');
            });
        }
    }
};
