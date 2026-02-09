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
        Schema::create('entity_audit_trails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('entity_type'); // 'company' or 'agency'
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action'); // 'created', 'updated', 'deleted', 'archived', 'duplicated'
            $table->json('changes')->nullable(); // Store the changes made
            $table->text('description')->nullable(); // Additional description
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['entity_id', 'entity_type']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_audit_trails');
    }
};