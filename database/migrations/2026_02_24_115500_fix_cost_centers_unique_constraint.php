<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Supprimer l'index unique existant sur code
        Schema::table('cost_centers', function (Blueprint $table) {
            $table->dropUnique(['code']);
        });
        
        // Créer un index unique sur la combinaison (company_id, code)
        Schema::table('cost_centers', function (Blueprint $table) {
            $table->unique(['company_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer l'index unique sur (company_id, code)
        Schema::table('cost_centers', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'code']);
        });
        
        // Recréer l'index unique sur code seul
        Schema::table('cost_centers', function (Blueprint $table) {
            $table->unique(['code']);
        });
    }
};