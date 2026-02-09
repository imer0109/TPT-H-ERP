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
        // Vérifier si les colonnes existent avant de les supprimer
        $columnsToCheck = ['name', 'comment', 'site', 'published'];
        $tableColumns = Schema::getColumnListing('clients');
        
        // Supprimer les contraintes étrangères existantes de manière sécurisée
        try {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
            });
        } catch (\Exception $e) {
            // Si la clé étrangère n'existe pas, on continue
        }
        
        try {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropForeign(['agency_id']);
            });
        } catch (\Exception $e) {
            // Si la clé étrangère n'existe pas, on continue
        }
        
        // Modifier la structure de la table pour correspondre au modèle
        Schema::table('clients', function (Blueprint $table) use ($columnsToCheck, $tableColumns) {
            // Supprimer seulement les colonnes qui existent
            $columnsToDelete = [];
            foreach ($columnsToCheck as $column) {
                if (in_array($column, $tableColumns)) {
                    $columnsToDelete[] = $column;
                }
            }
            
            if (!empty($columnsToDelete)) {
                $table->dropColumn($columnsToDelete);
            }
            
            if (!Schema::hasColumn('clients', 'type_client')) {
                $table->string('type_client')->after('nom_raison_sociale');
            }
            if (!Schema::hasColumn('clients', 'telephone')) {
                $table->string('telephone')->after('type_client');
            }
            if (!Schema::hasColumn('clients', 'whatsapp')) {
                $table->string('whatsapp')->after('telephone');
            }
            if (!Schema::hasColumn('clients', 'email')) {
                $table->string('email')->after('whatsapp');
            }
            if (!Schema::hasColumn('clients', 'adresse')) {
                $table->string('adresse')->after('email');
            }
            if (!Schema::hasColumn('clients', 'ville')) {
                $table->string('ville')->after('adresse');
            }
            if (!Schema::hasColumn('clients', 'contact_principal')) {
                $table->string('contact_principal')->after('ville');
            }
            if (!Schema::hasColumn('clients', 'canal_acquisition')) {
                $table->string('canal_acquisition')->after('contact_principal');
            }
            if (!Schema::hasColumn('clients', 'referent_commercial_id')) {
                $table->unsignedBigInteger('referent_commercial_id')->nullable()->after('canal_acquisition');
            }
            if (!Schema::hasColumn('clients', 'type_relation')) {
                $table->string('type_relation')->after('referent_commercial_id');
            }
            if (!Schema::hasColumn('clients', 'delai_paiement')) {
                $table->integer('delai_paiement')->after('type_relation');
            }
            if (!Schema::hasColumn('clients', 'plafond_credit')) {
                $table->decimal('plafond_credit', 15, 2)->after('delai_paiement');
            }
            if (!Schema::hasColumn('clients', 'mode_paiement_prefere')) {
                $table->string('mode_paiement_prefere')->after('plafond_credit');
            }
            if (!Schema::hasColumn('clients', 'statut')) {
                $table->string('statut')->after('mode_paiement_prefere');
            }
            if (!Schema::hasColumn('clients', 'categorie')) {
                $table->string('categorie')->after('statut');
            }
            if (!Schema::hasColumn('clients', 'site_web')) {
                $table->string('site_web')->nullable()->after('categorie');
            }
            if (!Schema::hasColumn('clients', 'notes')) {
                $table->text('notes')->nullable()->after('site_web');
            }
            
            // Rétablir les contraintes étrangères seulement si elles n'existent pas
            try {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            } catch (\Exception $e) {
                // La contrainte existe peut-être déjà
            }
            try {
                $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
            } catch (\Exception $e) {
                // La contrainte existe peut-être déjà
            }
            try {
                $table->foreign('referent_commercial_id')->references('id')->on('users')->onDelete('set null');
            } catch (\Exception $e) {
                // La contrainte existe peut-être déjà
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les contraintes étrangères d'abord
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['agency_id']);
            $table->dropForeign(['referent_commercial_id']);
        });
        
        // Rétablir la structure originale
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'type_client', 'telephone', 'whatsapp', 'email', 'adresse', 
                'ville', 'contact_principal', 'canal_acquisition', 'referent_commercial_id',
                'type_relation', 'delai_paiement', 'plafond_credit', 'mode_paiement_prefere',
                'statut', 'categorie', 'site_web', 'notes'
            ]);
            
            $table->string('name');
            $table->string('comment');
            $table->string('site')->nullable();
            $table->boolean('published')->default(false);
            
            // Rétablir les contraintes étrangères
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
        });
    }
};
