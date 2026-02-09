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
        Schema::create('api_sync_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('connector_id', 36);
            $table->enum('type', ['scheduled', 'manual', 'webhook', 'event'])->default('scheduled');
            $table->enum('direction', ['inbound', 'outbound']);
            $table->enum('status', ['running', 'success', 'partial', 'failed', 'cancelled'])->default('running');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('records_processed')->default(0);
            $table->integer('records_successful')->default(0);
            $table->integer('records_failed')->default(0);
            $table->text('error_message')->nullable();
            $table->json('error_details')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->decimal('execution_time', 8, 3)->nullable(); // in seconds
            $table->unsignedBigInteger('triggered_by')->nullable();
            $table->timestamps();

            $table->foreign('connector_id')->references('id')->on('api_connectors')->onDelete('cascade');
            
            $table->index(['connector_id', 'started_at']);
            $table->index(['status', 'started_at']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_sync_logs');
    }
};