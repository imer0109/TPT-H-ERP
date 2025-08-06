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
        Schema::table('cash_transactions', function (Blueprint $table) {
            Schema::table('cash_transactions', function (Blueprint $table) {
            $table->foreignId('cash_session_id')
                  ->nullable()
                  ->constrained('cash_sessions') // ou 'sessions' selon le nom de ta table
                  ->onDelete('set null');
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropForeign(['cash_session_id']);
            $table->dropColumn('cash_session_id');
        });
        });
    }
};
