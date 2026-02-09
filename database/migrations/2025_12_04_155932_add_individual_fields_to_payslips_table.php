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
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('deductions');
            $table->decimal('overtime_rate', 12, 2)->default(0)->after('overtime_hours');
            $table->decimal('transport_allowance', 12, 2)->default(0)->after('overtime_rate');
            $table->decimal('housing_allowance', 12, 2)->default(0)->after('transport_allowance');
            $table->decimal('meal_allowance', 12, 2)->default(0)->after('housing_allowance');
            $table->decimal('performance_bonus', 12, 2)->default(0)->after('meal_allowance');
            $table->decimal('social_security', 12, 2)->default(0)->after('performance_bonus');
            $table->decimal('income_tax', 12, 2)->default(0)->after('social_security');
            $table->decimal('total_deductions', 12, 2)->default(0)->after('income_tax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn([
                'overtime_hours',
                'overtime_rate',
                'transport_allowance',
                'housing_allowance',
                'meal_allowance',
                'performance_bonus',
                'social_security',
                'income_tax',
                'total_deductions'
            ]);
        });
    }
};