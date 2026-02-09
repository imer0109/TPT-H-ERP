<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_alerts') && ! Schema::hasColumn('stock_alerts', 'product_id')) {
            Schema::table('stock_alerts', function (Blueprint $table) {
                // products.id est un UUID string; on aligne le type et la collation
                $table->uuid('product_id')->nullable()->after('id');
                // Skip the foreign key constraint for now
                // We'll add it later when we're sure the data is consistent
                // $table->foreign('product_id', 'stock_alerts_product_id_fk')
                //     ->references('id')->on('products')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stock_alerts') && Schema::hasColumn('stock_alerts', 'product_id')) {
            Schema::table('stock_alerts', function (Blueprint $table) {
                // $table->dropForeign('stock_alerts_product_id_fk');
                $table->dropColumn('product_id');
            });
        }
    }
};
