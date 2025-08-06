<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->morphs('entity');
            $table->enum('type', ['principale', 'secondaire']);
            $table->decimal('solde_actuel', 15, 2)->default(0);
            $table->boolean('est_ouverte')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // (le reste du code est inchangé)
    }

    public function down()
    {
        Schema::dropIfExists('transaction_natures');
        Schema::dropIfExists('cash_transactions');
        Schema::dropIfExists('cash_sessions');
        Schema::dropIfExists('cash_registers');
    }
};
