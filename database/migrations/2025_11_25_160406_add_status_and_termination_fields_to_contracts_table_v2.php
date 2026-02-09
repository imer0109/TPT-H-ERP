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
            // Check if columns don't already exist before adding them
            if (!Schema::hasColumn('contracts', 'status')) {
                $table->string('status')->default('draft')->after('supporting_documents');
            }
            if (!Schema::hasColumn('contracts', 'termination_reason')) {
                $table->text('termination_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('contracts', 'terminated_at')) {
                $table->date('terminated_at')->nullable()->after('termination_reason');
            }
            if (!Schema::hasColumn('contracts', 'terminated_by')) {
                $table->unsignedBigInteger('terminated_by')->nullable()->after('terminated_at');
                $table->foreign('terminated_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'terminated_by')) {
                $table->dropForeign(['terminated_by']);
                $table->dropColumn(['status', 'termination_reason', 'terminated_at', 'terminated_by']);
            }
        });
    }
};