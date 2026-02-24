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
            $table->string('code_fournisseur')->unique()->nullable();
            $table->unsignedBigInteger('societe_id')->nullable();
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->string('type')->nullable();
            $table->string('activite')->nullable();
            $table->string('statut')->default('actif');
            $table->string('niu')->nullable();
            $table->string('rccm')->nullable();
            $table->string('cnss')->nullable();
            $table->string('pays')->nullable();
            $table->string('ville')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('site_web')->nullable();
            $table->string('contact_principal')->nullable();
            $table->string('banque')->nullable();
            $table->string('iban')->nullable();
            $table->string('numero_compte')->nullable();
            $table->string('devise')->default('EUR');
            $table->string('condition_reglement')->default('comptant');
            $table->integer('delai_paiement')->default(0);
            $table->decimal('plafond_credit', 15, 2)->default(0);
            $table->date('date_debut_relation')->nullable();
            $table->date('date_fin_relation')->nullable();
            $table->decimal('note_moyenne', 3, 2)->nullable();
            $table->integer('nombre_evaluations')->default(0);
            $table->timestamp('derniere_activite')->nullable();
            $table->boolean('est_actif')->default(true);
            
            // Clés étrangères
            $table->foreign('societe_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->dropForeign(['societe_id']);
            $table->dropForeign(['agency_id']);
            $table->dropColumn([
                'code_fournisseur',
                'societe_id',
                'agency_id',
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
                'iban',
                'numero_compte',
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
            ]);
        });
    }
};
