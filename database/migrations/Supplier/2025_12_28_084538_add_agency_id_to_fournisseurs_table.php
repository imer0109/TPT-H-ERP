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
        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->unsignedBigInteger('agency_id')->nullable()->after('societe_id');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->dropForeign(['fournisseurs_agency_id_foreign']);
            $table->dropColumn('agency_id');
        });
    }
};
