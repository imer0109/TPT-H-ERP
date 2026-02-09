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
            // Remove unused fields from the original migration
            $table->dropColumn(['name', 'comment', 'site', 'published']);
            
            // Add missing fields based on requirements
            $table->string('telephone')->after('nom_raison_sociale');
            $table->string('whatsapp')->nullable()->after('telephone');
            $table->string('email')->nullable()->after('whatsapp');
            $table->string('adresse')->nullable()->after('email');
            $table->string('ville')->nullable()->after('adresse');
            $table->string('contact_principal')->nullable()->after('ville');
            $table->string('canal_acquisition')->nullable()->after('contact_principal');
            $table->foreignId('referent_commercial_id')->nullable()->constrained('users')->onDelete('set null')->after('canal_acquisition');
            $table->string('type_relation')->nullable()->after('referent_commercial_id');
            $table->integer('delai_paiement')->nullable()->after('type_relation');
            $table->decimal('plafond_credit', 15, 2)->nullable()->after('delai_paiement');
            $table->string('mode_paiement_prefere')->nullable()->after('plafond_credit');
            $table->string('statut')->default('actif')->after('mode_paiement_prefere');
            $table->string('categorie')->nullable()->after('statut');
            $table->string('site_web')->nullable()->after('categorie');
            $table->text('notes')->nullable()->after('site_web');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Restore the original fields if needed
            $table->string('name')->after('nom_raison_sociale');
            $table->string('comment')->after('name');
            $table->string('site')->nullable()->after('comment');
            $table->boolean('published')->default(false)->after('site');
            
            // Drop the added fields
            $table->dropColumn([
                'telephone',
                'whatsapp',
                'email',
                'adresse',
                'ville',
                'contact_principal',
                'canal_acquisition',
                'referent_commercial_id',
                'type_relation',
                'delai_paiement',
                'plafond_credit',
                'mode_paiement_prefere',
                'statut',
                'categorie',
                'site_web',
                'notes'
            ]);
        });
    }
};