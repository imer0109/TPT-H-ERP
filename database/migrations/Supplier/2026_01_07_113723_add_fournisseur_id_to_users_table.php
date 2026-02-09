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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'fournisseur_id')) {
                $table->unsignedBigInteger('fournisseur_id')->nullable();
                $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'fournisseur_id')) {
                $table->dropForeign(['fournisseur_id']);
                $table->dropColumn('fournisseur_id');
            }
        });
    }
};