<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accounting_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('journal_id')->constrained('accounting_journals')->onDelete('cascade');
            $table->string('entry_number', 50)->unique();
            $table->date('entry_date');
            $table->enum('reference_type', ['caisse', 'achat', 'vente', 'salaire', 'transfert', 'amortissement', 'manuel', 'banque', 'immobilisation']);
            $table->string('reference_id', 50)->nullable();
            $table->string('reference_number', 100)->nullable();
            $table->text('description');
            $table->foreignId('debit_account_id')->constrained('chart_of_accounts')->onDelete('cascade');
            $table->foreignId('credit_account_id')->constrained('chart_of_accounts')->onDelete('cascade');
            $table->decimal('debit_amount', 15, 2)->default(0);
            $table->decimal('credit_amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('XOF');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->foreignId('cost_center_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(0);
            $table->enum('status', ['brouillon', 'validee', 'exportee', 'cloturee'])->default('brouillon');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('exported_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['company_id', 'entry_date']);
            $table->index(['journal_id', 'entry_date']);
            $table->index(['debit_account_id', 'entry_date']);
            $table->index(['credit_account_id', 'entry_date']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('status');
            $table->index(['cost_center_id', 'entry_date']);
            $table->index(['project_id', 'entry_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounting_entries');
    }
};