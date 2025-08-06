<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('evaluator_id')->constrained('employees');
            $table->string('period'); // ex: "2024-Q1"
            $table->date('evaluation_date');
            $table->json('criteria_scores'); // {"criteria_id": score, ...}
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->decimal('overall_score', 4, 2);
            $table->enum('status', ['draft', 'submitted', 'acknowledged', 'disputed'])->default('draft');
            $table->text('employee_comments')->nullable();
            $table->string('pdf_report')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};