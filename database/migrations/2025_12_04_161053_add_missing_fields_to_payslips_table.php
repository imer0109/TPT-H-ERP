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
        Schema::table('payslips', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('total_deductions');
            $table->decimal('other_allowances', 12, 2)->default(0)->after('notes');
            $table->string('allowances_description')->nullable()->after('other_allowances');
            $table->decimal('advance_deduction', 12, 2)->default(0)->after('allowances_description');
            $table->decimal('loan_deduction', 12, 2)->default(0)->after('advance_deduction');
            $table->decimal('other_deductions', 12, 2)->default(0)->after('loan_deduction');
            $table->string('deductions_description')->nullable()->after('other_deductions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn([
                'notes',
                'other_allowances',
                'allowances_description',
                'advance_deduction',
                'loan_deduction',
                'other_deductions',
                'deductions_description'
            ]);
        });
    }
};