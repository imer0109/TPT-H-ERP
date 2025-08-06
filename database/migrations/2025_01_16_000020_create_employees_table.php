<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['M', 'F']);
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('photo')->nullable();
            $table->string('nationality');
            $table->string('cnss_number')->nullable();
            $table->string('id_card_number');
            $table->string('nui_number')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->foreignId('current_company_id')->constrained('companies');
            $table->foreignId('current_agency_id')->constrained('agencies');
            $table->foreignId('current_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('current_position_id')->constrained('positions');
            $table->foreignId('supervisor_id')->nullable()->references('id')->on('employees');
            $table->enum('status', ['active', 'suspended', 'archived'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};