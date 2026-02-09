<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['earning', 'deduction']);
            $table->enum('calculation_type', ['fixed', 'percentage', 'formula']);
            $table->string('calculation_value'); // Montant fixe, pourcentage ou formule
            $table->boolean('is_taxable')->default(true);
            $table->boolean('affects_gross')->default(true);
            $table->boolean('is_mandatory')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_items');
    }
};