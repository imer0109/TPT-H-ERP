<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            // Add societe_id column if it doesn't exist
            if (!Schema::hasColumn('fournisseurs', 'societe_id')) {
                $table->unsignedBigInteger('societe_id')->nullable()->after('id');
                $table->foreign('societe_id')->references('id')->on('companies')->onDelete('cascade');
            }
            
            // Add missing columns that don't already exist
            if (!Schema::hasColumn('fournisseurs', 'type')) {
                $table->string('type')->nullable()->after('societe_id');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'activite')) {
                $table->string('activite')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'statut')) {
                $table->string('statut')->nullable()->default('actif')->after('activite');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'niu')) {
                $table->string('niu')->nullable()->after('statut');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'rccm')) {
                $table->string('rccm')->nullable()->after('niu');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'cnss')) {
                $table->string('cnss')->nullable()->after('rccm');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'pays')) {
                $table->string('pays')->nullable()->after('adresse');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'ville')) {
                $table->string('ville')->nullable()->after('pays');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('telephone');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'site_web')) {
                $table->string('site_web')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'contact_principal')) {
                $table->string('contact_principal')->nullable()->after('site_web');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'banque')) {
                $table->string('banque')->nullable()->after('contact_principal');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'numero_compte')) {
                $table->string('numero_compte')->nullable()->after('banque');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'iban')) {
                $table->string('iban')->nullable()->after('numero_compte');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'devise')) {
                $table->string('devise')->nullable()->after('iban');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'condition_reglement')) {
                $table->string('condition_reglement')->nullable()->after('devise');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'delai_paiement')) {
                $table->integer('delai_paiement')->nullable()->after('condition_reglement');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'plafond_credit')) {
                $table->decimal('plafond_credit', 15, 2)->nullable()->after('delai_paiement');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'date_debut_relation')) {
                $table->date('date_debut_relation')->nullable()->after('plafond_credit');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'date_fin_relation')) {
                $table->date('date_fin_relation')->nullable()->after('date_debut_relation');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'note_moyenne')) {
                $table->decimal('note_moyenne', 3, 2)->nullable()->after('date_fin_relation');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'nombre_evaluations')) {
                $table->integer('nombre_evaluations')->default(0)->after('note_moyenne');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'derniere_activite')) {
                $table->timestamp('derniere_activite')->nullable()->after('nombre_evaluations');
            }
            
            if (!Schema::hasColumn('fournisseurs', 'est_actif')) {
                $table->boolean('est_actif')->default(true)->after('derniere_activite');
            }
            
            // Add indexes for better performance
            if (!Schema::hasIndex('fournisseurs', 'fournisseurs_societe_id_statut_index')) {
                $table->index(['societe_id', 'statut']);
            }
            
            if (!Schema::hasIndex('fournisseurs', 'fournisseurs_activite_statut_index')) {
                $table->index(['activite', 'statut']);
            }
            
            if (!Schema::hasIndex('fournisseurs', 'fournisseurs_derniere_activite_index')) {
                $table->index(['derniere_activite']);
            }
        });
    }

    public function down()
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            // Drop foreign key first if it exists
            if (Schema::hasColumn('fournisseurs', 'societe_id')) {
                $table->dropForeign(['societe_id']);
            }
            
            // Drop indexes if they exist
            if (Schema::hasIndex('fournisseurs', 'fournisseurs_societe_id_statut_index')) {
                $table->dropIndex(['societe_id', 'statut']);
            }
            
            if (Schema::hasIndex('fournisseurs', 'fournisseurs_activite_statut_index')) {
                $table->dropIndex(['activite', 'statut']);
            }
            
            if (Schema::hasIndex('fournisseurs', 'fournisseurs_derniere_activite_index')) {
                $table->dropIndex(['derniere_activite']);
            }
            
            // Only drop columns that exist
            $columnsToDrop = [
                'societe_id',
                'type',
                'activite',
                'statut',
                'niu',
                'rccm',
                'cnss',
                'pays',
                'ville',
                'whatsapp',
                'site_web',
                'contact_principal',
                'banque',
                'numero_compte',
                'iban',
                'devise',
                'condition_reglement',
                'delai_paiement',
                'plafond_credit',
                'date_debut_relation',
                'date_fin_relation',
                'note_moyenne',
                'nombre_evaluations',
                'derniere_activite',
                'est_actif'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('fournisseurs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};