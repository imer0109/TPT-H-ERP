<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('raison_sociale');
            $table->enum('type', ['holding', 'filiale']);
            $table->string('niu')->nullable();
            $table->string('rccm')->nullable();
            $table->string('regime_fiscal')->nullable();
            $table->string('secteur_activite');
            $table->string('devise');
            $table->string('pays');
            $table->string('ville');
            $table->text('siege_social');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('site_web')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('companies');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};