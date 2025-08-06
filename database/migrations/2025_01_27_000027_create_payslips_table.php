<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('company_id')->constrained();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('base_salary', 12, 2);
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('net_salary', 12, 2);
            $table->json('earnings'); // {"item_id": amount, ...}
            $table->json('deductions'); // {"item_id": amount, ...}
            $table->enum('payment_method', ['bank_transfer', 'cash', 'mobile_money']);
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['draft', 'validated', 'paid'])->default('draft');
            $table->string('pdf_file')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payslips');
    }
};