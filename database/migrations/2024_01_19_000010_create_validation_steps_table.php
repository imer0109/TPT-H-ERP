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
        Schema::create('validation_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('validation_requests')->onDelete('cascade');
            $table->integer('step_number');
            $table->unsignedBigInteger('validator_id');
            $table->enum('action', ['approved', 'rejected', 'delegated', 'escalated']);
            $table->text('notes')->nullable();
            $table->timestamp('validated_at');
            $table->timestamps();

            $table->index(['request_id', 'step_number']);
            $table->index(['validator_id']);
            $table->index(['action']);
            $table->index(['validated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_steps');
    }
};