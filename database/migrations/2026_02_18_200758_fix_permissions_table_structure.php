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
        Schema::table('permissions', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (!Schema::hasColumn('permissions', 'slug')) {
                $table->string('slug')->unique()->after('nom');
            }
            
            if (!Schema::hasColumn('permissions', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            
            if (!Schema::hasColumn('permissions', 'resource')) {
                $table->string('resource')->nullable()->after('module');
            }
            
            if (!Schema::hasColumn('permissions', 'action')) {
                $table->string('action')->default('view')->after('resource');
            }
            
            if (!Schema::hasColumn('permissions', 'is_system')) {
                $table->boolean('is_system')->default(false)->after('description');
            }
            
            if (!Schema::hasColumn('permissions', 'requires_validation')) {
                $table->boolean('requires_validation')->default(false)->after('is_system');
            }
            
            if (!Schema::hasColumn('permissions', 'validation_level')) {
                $table->integer('validation_level')->default(1)->after('requires_validation');
            }
            
            // Ajouter des indexes pour améliorer les performances
            if (!Schema::hasTable('permissions') || !DB::select("SHOW INDEX FROM permissions WHERE Key_name = 'permissions_module_resource_action_index'")) {
                try {
                    $table->index(['module', 'resource', 'action']);
                } catch (Exception $e) {
                    // Index may already exist
                }
            }
            if (!Schema::hasTable('permissions') || !DB::select("SHOW INDEX FROM permissions WHERE Key_name = 'permissions_slug_index'")) {
                try {
                    $table->index(['slug']);
                } catch (Exception $e) {
                    // Index may already exist
                }
            }
        });
        
        // Mettre à jour les permissions existantes avec des slugs
        DB::table('permissions')->whereNull('slug')->update([
            'slug' => DB::raw("CONCAT(module, '.', COALESCE(resource, 'default'), '.', COALESCE(action, 'view'))")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['module', 'resource', 'action']);
            $table->dropIndex(['slug']);
            
            $table->dropColumn([
                'slug',
                'description',
                'resource',
                'action',
                'is_system',
                'requires_validation',
                'validation_level'
            ]);
        });
    }
};
