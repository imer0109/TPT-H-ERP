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
        Schema::table('employee_leave_balances', function (Blueprint $table) {
            // Check if indexes don't already exist before adding them
            if (!Schema::hasIndex('employee_leave_balances', 'employee_leave_balances_employee_id_leave_type_id_unique')) {
                $table->unique(['employee_id', 'leave_type_id']);
            }
            
            if (!Schema::hasIndex('employee_leave_balances', 'emp_leave_balances_idx')) {
                $table->index(['employee_id', 'leave_type_id', 'expiry_date'], 'emp_leave_balances_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_leave_balances', function (Blueprint $table) {
            $table->dropUnique(['employee_id', 'leave_type_id']);
            $table->dropIndex('emp_leave_balances_idx');
        });
    }
};