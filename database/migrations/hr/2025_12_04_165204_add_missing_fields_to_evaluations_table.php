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
        Schema::table('evaluations', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('evaluation_type');
            $table->text('objectives')->nullable()->after('due_date');
            $table->text('achievements')->nullable()->after('objectives');
            $table->text('areas_improvement')->nullable()->after('achievements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'objectives', 'achievements', 'areas_improvement']);
        });
    }
};