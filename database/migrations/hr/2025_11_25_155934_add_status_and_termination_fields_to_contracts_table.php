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
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('supporting_documents');
            $table->text('termination_reason')->nullable()->after('status');
            $table->date('terminated_at')->nullable()->after('termination_reason');
            $table->unsignedBigInteger('terminated_by')->nullable()->after('terminated_at');
            
            // Check if foreign key constraint doesn't already exist before adding it
            $table->foreign('terminated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Check if foreign key constraint exists before dropping it
            if (Schema::hasColumn('contracts', 'terminated_by')) {
                $table->dropForeign(['terminated_by']);
                $table->dropColumn(['status', 'termination_reason', 'terminated_at', 'terminated_by']);
            }
        });
    }
};