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
                // Ajoute un index sur la colonne uuid 'id' si aucun index/PK n'existe
                try {
                    $table->index('id', 'products_id_index');
                } catch (Throwable $e) {
                    // ignore if already indexed
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                try {
                    $table->dropIndex('products_id_index');
                } catch (Throwable $e) {
                    // ignore
                }
            });
        }
    }
};


