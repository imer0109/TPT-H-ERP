<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->enum('type', ['CDI', 'CDD', 'Stage', 'Prestation']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('trial_period_start')->nullable();
            $table->date('trial_period_end')->nullable();
            $table->decimal('base_salary', 12, 2);
            $table->json('benefits')->nullable(); // {"housing": 50000, "transport": 25000, ...}
            $table->string('contract_file')->nullable(); // Chemin vers le PDF du contrat
            $table->string('hiring_form')->nullable(); // Chemin vers la fiche d'embauche
            $table->json('supporting_documents')->nullable(); // ["path1.pdf", "path2.pdf", ...]
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};