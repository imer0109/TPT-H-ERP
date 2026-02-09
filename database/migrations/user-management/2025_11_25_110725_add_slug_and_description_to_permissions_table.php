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
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'slug')) {
                $table->string('slug')->unique()->after('nom');
            }
            if (!Schema::hasColumn('permissions', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('permissions', 'resource')) {
                $table->string('resource')->nullable()->after('module');
            }
            if (!Schema::hasColumn('permissions', 'is_system')) {
                $table->boolean('is_system')->default(false)->after('action');
            }
            if (!Schema::hasColumn('permissions', 'requires_validation')) {
                $table->boolean('requires_validation')->default(false)->after('is_system');
            }
            if (!Schema::hasColumn('permissions', 'validation_level')) {
                $table->integer('validation_level')->default(1)->after('requires_validation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('permissions', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('permissions', 'resource')) {
                $table->dropColumn('resource');
            }
            if (Schema::hasColumn('permissions', 'is_system')) {
                $table->dropColumn('is_system');
            }
            if (Schema::hasColumn('permissions', 'requires_validation')) {
                $table->dropColumn('requires_validation');
            }
            if (Schema::hasColumn('permissions', 'validation_level')) {
                $table->dropColumn('validation_level');
            }
        });
    }
};