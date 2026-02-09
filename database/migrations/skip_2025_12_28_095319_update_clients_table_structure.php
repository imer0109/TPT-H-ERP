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
            
            $table->string('type_client')->after('nom_raison_sociale');
            $table->string('telephone')->after('type_client');
            $table->string('whatsapp')->after('telephone');
            $table->string('email')->after('whatsapp');
            $table->string('adresse')->after('email');
            $table->string('ville')->after('adresse');
            $table->string('contact_principal')->after('ville');
            $table->string('canal_acquisition')->after('contact_principal');
            $table->unsignedBigInteger('referent_commercial_id')->nullable()->after('canal_acquisition');
            $table->string('type_relation')->after('referent_commercial_id');
            $table->integer('delai_paiement')->after('type_relation');
            $table->decimal('plafond_credit', 15, 2)->after('delai_paiement');
            $table->string('mode_paiement_prefere')->after('plafond_credit');
            $table->string('statut')->after('mode_paiement_prefere');
            $table->string('categorie')->after('statut');
            $table->string('site_web')->nullable()->after('categorie');
            $table->text('notes')->nullable()->after('site_web');
            
            // Rétablir les contraintes étrangères
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
            $table->foreign('referent_commercial_id')->references('id')->on('users')->onDelete('set null');
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
