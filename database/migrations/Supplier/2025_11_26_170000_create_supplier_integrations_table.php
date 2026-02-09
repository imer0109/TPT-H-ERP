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
        Schema::create('supplier_integrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fournisseur_id');
            $table->string('integration_type');
            $table->string('external_system');
            $table->string('external_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('sync_status')->default('pending'); // synced, pending, failed
            $table->timestamp('last_sync_at')->nullable();
            $table->text('sync_error_message')->nullable();
            $table->json('configuration')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['fournisseur_id', 'is_active']);
            $table->index('integration_type');
            $table->index('sync_status');

            // Foreign keys
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_integrations');
    }
};