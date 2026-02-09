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
        Schema::table('supplier_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_orders', 'nature_achat')) {
                $table->string('nature_achat')->default('Bien')->after('statut'); // 'Bien' ou 'Service'
            }
            if (!Schema::hasColumn('supplier_orders', 'adresse_livraison')) {
                $table->string('adresse_livraison')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('supplier_orders', 'delai_contractuel')) {
                $table->date('delai_contractuel')->nullable()->after('adresse_livraison');
            }
            if (!Schema::hasColumn('supplier_orders', 'conditions_paiement')) {
                $table->string('conditions_paiement')->nullable()->after('delai_contractuel');
            }
            if (!Schema::hasColumn('supplier_orders', 'tva_percentage')) {
                $table->decimal('tva_percentage', 5, 2)->default(18.00)->after('montant_ttc');
            }
            if (!Schema::hasColumn('supplier_orders', 'devise')) {
                $table->string('devise')->default('XAF')->after('tva_percentage');
            }
            if (!Schema::hasColumn('supplier_orders', 'notes')) {
                $table->text('notes')->nullable()->after('code');
            }
            if (!Schema::hasColumn('supplier_orders', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_orders', function (Blueprint $table) {
            $table->dropColumn([
                'nature_achat',
                'adresse_livraison',
                'delai_contractuel',
                'conditions_paiement',
                'tva_percentage',
                'devise',
                'created_by'
            ]);
        });
    }
};
