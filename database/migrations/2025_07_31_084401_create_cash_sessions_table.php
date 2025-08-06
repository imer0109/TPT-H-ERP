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
        Schema::create('cash_sessions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('cash_register_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('solde_initial', 10, 2)->default(0);
            $table->decimal('solde_final', 10, 2)->nullable();

            $table->dateTime('date_ouverture')->nullable();
            $table->dateTime('date_fermeture')->nullable();

            $table->string('justificatif_fermeture')->nullable();
            $table->text('commentaire')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_sessions');
    }
};
