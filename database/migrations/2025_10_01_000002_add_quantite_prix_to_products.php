<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'quantite')) {
                    $table->integer('quantite')->default(0);
                }
                if (! Schema::hasColumn('products', 'prix_unitaire')) {
                    $table->decimal('prix_unitaire', 15, 2)->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'quantite')) {
                    $table->dropColumn('quantite');
                }
                if (Schema::hasColumn('products', 'prix_unitaire')) {
                    $table->dropColumn('prix_unitaire');
                }
            });
        }
    }
};


