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
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'date_embauche')) {
                $table->date('date_embauche')->nullable();
            }
            if (!Schema::hasColumn('employees', 'salaire_base')) {
                $table->decimal('salaire_base', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('employees', 'current_company_id')) {
                $table->unsignedBigInteger('current_company_id')->nullable();
            }
            if (!Schema::hasColumn('employees', 'current_position_id')) {
                $table->unsignedBigInteger('current_position_id')->nullable();
            }
            if (!Schema::hasColumn('employees', 'supervisor_id')) {
                $table->unsignedBigInteger('supervisor_id')->nullable();
            }
            if (!Schema::hasColumn('employees', 'gender')) {
                $table->string('gender')->nullable();
            }
            if (!Schema::hasColumn('employees', 'nationality')) {
                $table->string('nationality')->nullable();
            }
            if (!Schema::hasColumn('employees', 'matricule')) {
                $table->string('matricule')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['date_embauche', 'salaire_base', 'status', 'current_company_id', 'current_position_id', 'supervisor_id', 'gender', 'nationality', 'matricule']);
        });
    }
};