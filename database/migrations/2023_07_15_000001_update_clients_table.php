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
        Schema::table('clients', function (Blueprint $table) {
            // Vérifier si les colonnes existent déjà avant de les ajouter
            if (!Schema::hasColumn('clients', 'code_client')) {
                $table->string('code_client')->unique()->after('id');
            }
            
            if (!Schema::hasColumn('clients', 'company_id')) {
                $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null')->after('code_client');
            }
            
            if (!Schema::hasColumn('clients', 'agency_id')) {
                $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null')->after('company_id');
            }
            
            if (!Schema::hasColumn('clients', 'nom_raison_sociale')) {
                $table->string('nom_raison_sociale')->after('agency_id');
            }
            
            if (!Schema::hasColumn('clients', 'type_client')) {
                $table->enum('type_client', ['particulier', 'entreprise', 'administration', 'distributeur'])->default('particulier')->after('nom_raison_sociale');
            }
            
            if (!Schema::hasColumn('clients', 'telephone')) {
                $table->string('telephone')->nullable()->after('type_client');
            }
            
            if (!Schema::hasColumn('clients', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('telephone');
            }
            
            if (!Schema::hasColumn('clients', 'email')) {
                $table->string('email')->nullable()->after('whatsapp');
            }
            
            if (!Schema::hasColumn('clients', 'adresse')) {
                $table->text('adresse')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('clients', 'contact_principal')) {
                $table->string('contact_principal')->nullable()->after('adresse');
            }
            
            if (!Schema::hasColumn('clients', 'canal_acquisition')) {
                $table->string('canal_acquisition')->nullable()->after('contact_principal');
            }
            
            if (!Schema::hasColumn('clients', 'referent_commercial_id')) {
                $table->foreignId('referent_commercial_id')->nullable()->constrained('users')->onDelete('set null')->after('canal_acquisition');
            }
            
            if (!Schema::hasColumn('clients', 'type_relation')) {
                $table->enum('type_relation', ['comptant', 'credit', 'vip'])->default('comptant')->after('referent_commercial_id');
            }
            
            if (!Schema::hasColumn('clients', 'delai_paiement')) {
                $table->integer('delai_paiement')->default(0)->after('type_relation');
            }
            
            if (!Schema::hasColumn('clients', 'plafond_credit')) {
                $table->decimal('plafond_credit', 15, 2)->default(0)->after('delai_paiement');
            }
            
            if (!Schema::hasColumn('clients', 'mode_paiement_prefere')) {
                $table->string('mode_paiement_prefere')->nullable()->after('plafond_credit');
            }
            
            if (!Schema::hasColumn('clients', 'statut')) {
                $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif')->after('mode_paiement_prefere');
            }
            
            if (!Schema::hasColumn('clients', 'categorie')) {
                $table->enum('categorie', ['or', 'argent', 'bronze'])->default('bronze')->after('statut');
            }
            
            // Ajouter les timestamps et soft deletes si nécessaire
            if (!Schema::hasColumn('clients', 'created_at')) {
                $table->timestamps();
            }
            
            if (!Schema::hasColumn('clients', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropForeign(['company_id']);
            $table->dropForeign(['agency_id']);
            $table->dropForeign(['referent_commercial_id']);
            
            $table->dropColumn([
                'code_client',
                'company_id',
                'agency_id',
                'nom_raison_sociale',
                'type_client',
                'telephone',
                'whatsapp',
                'email',
                'adresse',
                'contact_principal',
                'canal_acquisition',
                'referent_commercial_id',
                'type_relation',
                'delai_paiement',
                'plafond_credit',
                'mode_paiement_prefere',
                'statut',
                'categorie',
                'deleted_at'
            ]);
        });
    }
};