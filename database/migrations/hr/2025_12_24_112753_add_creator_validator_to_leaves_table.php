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
        Schema::table('leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('validated_by')->nullable()->after('created_by');
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Supprimer les clés étrangères en utilisant leurs noms corrects
            $table->dropForeign(['leaves_created_by_foreign']);
            $table->dropForeign(['leaves_validated_by_foreign']);
            $table->dropColumn(['created_by', 'validated_by']);
        });
    }
};
