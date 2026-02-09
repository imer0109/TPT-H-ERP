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
        Schema::table('permissions', function (Blueprint $table) {
            // Ajouter une valeur par défaut pour le champ action si elle n'existe pas
            if (!Schema::hasColumn('permissions', 'action')) {
                $table->string('action')->default('view')->after('module');
            } else {
                // Modifier la colonne existante pour accepter NULL ou avoir une valeur par défaut
                $table->string('action')->default('view')->nullable()->change();
            }
            
            // Ajouter d'autres colonnes si elles manquent
            if (!Schema::hasColumn('permissions', 'resource')) {
                $table->string('resource')->nullable()->after('action');
            }
            
            if (!Schema::hasColumn('permissions', 'is_system')) {
                $table->boolean('is_system')->default(false)->after('description');
            }
            
            if (!Schema::hasColumn('permissions', 'requires_validation')) {
                $table->boolean('requires_validation')->default(false)->after('is_system');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
};