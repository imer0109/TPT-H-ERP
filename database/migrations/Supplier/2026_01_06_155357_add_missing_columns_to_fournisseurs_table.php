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
        Schema::table('fournisseurs', function (Blueprint $table) {
            if (!Schema::hasColumn('fournisseurs', 'code_fournisseur')) {
                $table->string('code_fournisseur')->unique()->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'societe_id')) {
                $table->unsignedBigInteger('societe_id')->nullable();
                $table->foreign('societe_id')->references('id')->on('companies');
            }
            if (!Schema::hasColumn('fournisseurs', 'agency_id')) {
                $table->unsignedBigInteger('agency_id')->nullable();
                $table->foreign('agency_id')->references('id')->on('agencies');
            }
            if (!Schema::hasColumn('fournisseurs', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'activite')) {
                $table->string('activite')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'statut')) {
                $table->string('statut')->default('actif');
            }
            if (!Schema::hasColumn('fournisseurs', 'niu')) {
                $table->string('niu')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'rccm')) {
                $table->string('rccm')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'cnss')) {
                $table->string('cnss')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'pays')) {
                $table->string('pays')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'ville')) {
                $table->string('ville')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'site_web')) {
                $table->string('site_web')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'contact_principal')) {
                $table->string('contact_principal')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'banque')) {
                $table->string('banque')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'numero_compte')) {
                $table->string('numero_compte')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'iban')) {
                $table->string('iban')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'devise')) {
                $table->string('devise')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'condition_reglement')) {
                $table->string('condition_reglement')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'delai_paiement')) {
                $table->integer('delai_paiement')->default(0);
            }
            if (!Schema::hasColumn('fournisseurs', 'plafond_credit')) {
                $table->decimal('plafond_credit', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('fournisseurs', 'date_debut_relation')) {
                $table->date('date_debut_relation')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'date_fin_relation')) {
                $table->date('date_fin_relation')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'note_moyenne')) {
                $table->decimal('note_moyenne', 3, 2)->default(0);
            }
            if (!Schema::hasColumn('fournisseurs', 'nombre_evaluations')) {
                $table->integer('nombre_evaluations')->default(0);
            }
            if (!Schema::hasColumn('fournisseurs', 'derniere_activite')) {
                $table->date('derniere_activite')->nullable();
            }
            if (!Schema::hasColumn('fournisseurs', 'est_actif')) {
                $table->boolean('est_actif')->default(true);
            }
            if (!Schema::hasColumn('fournisseurs', 'whatsapp')) {
                $table->string('whatsapp')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            //
        });
    }
};
