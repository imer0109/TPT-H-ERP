<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('code', 50);
            $table->string('label');
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->onDelete('cascade');
            $table->integer('level')->default(1);
            $table->enum('account_type', ['classe', 'sous_classe', 'compte', 'sous_compte']);
            $table->enum('account_nature', ['debit', 'credit']);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_auxiliary')->default(false);
            $table->enum('aux_type', ['client', 'fournisseur', 'employe', 'immobilisation', 'tva', 'charges_sociales', 'banque', 'caisse'])->nullable();
            $table->boolean('vat_applicable')->default(false);
            $table->text('description')->nullable();
            $table->string('syscohada_code', 20)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Unique constraint
            $table->unique(['company_id', 'code']);
            
            // Indexes
            $table->index(['company_id', 'account_type']);
            $table->index(['company_id', 'is_active']);
            $table->index(['company_id', 'is_auxiliary', 'aux_type']);
            $table->index('parent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};