<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accounting_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->enum('journal_type', ['caisse', 'banque', 'achat', 'vente', 'salaire', 'general', 'od', 'immobilisation', 'amortissement', 'transfert']);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_validation')->default(true);
            $table->boolean('auto_numbering')->default(true);
            $table->string('number_prefix', 10)->nullable();
            $table->foreignId('default_debit_account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
            $table->foreignId('default_credit_account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['company_id', 'journal_type']);
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounting_journals');
    }
};