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
            $table->text('description')->nullable()->after('designation');
            $table->decimal('tva_rate', 5, 2)->default(18.00)->after('montant_total');
            $table->decimal('tva_amount', 10, 2)->default(0.00)->after('tva_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_order_items', function (Blueprint $table) {
            $table->dropColumn(['description', 'tva_rate', 'tva_amount']);
        });
    }
};