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
        Schema::create('client_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('integration_type'); // crm, marketing, erp, etc.
            $table->string('external_id')->nullable(); // ID in the external system
            $table->string('external_system'); // name of the external system (Mailchimp, WhatsApp Business, etc.)
            $table->string('sync_status')->default('pending'); // pending, synced, failed
            $table->timestamp('last_sync_at')->nullable();
            $table->text('sync_error_message')->nullable();
            $table->json('mapping_data')->nullable(); // JSON field for field mappings
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_integrations');
    }
};
