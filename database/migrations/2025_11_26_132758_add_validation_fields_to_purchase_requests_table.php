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
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('validation_workflow_id')->nullable()->after('notes');
            $table->unsignedBigInteger('validation_request_id')->nullable()->after('validation_workflow_id');
            
            // Add foreign key constraints
            $table->foreign('validation_workflow_id')->references('id')->on('validation_workflows')->onDelete('set null');
            $table->foreign('validation_request_id')->references('id')->on('validation_requests')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropForeign(['validation_workflow_id']);
            $table->dropForeign(['validation_request_id']);
            
            $table->dropColumn(['validation_workflow_id', 'validation_request_id']);
        });
    }
};