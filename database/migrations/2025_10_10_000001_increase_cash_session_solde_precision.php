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
        Schema::table('cash_sessions', function (Blueprint $table) {
            // Increase precision for solde_initial and solde_final to match cash_registers table
            $table->decimal('solde_initial', 15, 2)->default(0)->change();
            $table->decimal('solde_final', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_sessions', function (Blueprint $table) {
            // Revert to original precision
            $table->decimal('solde_initial', 10, 2)->default(0)->change();
            $table->decimal('solde_final', 10, 2)->nullable()->change();
        });
    }
};