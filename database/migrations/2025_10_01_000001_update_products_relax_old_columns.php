<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'functionality')) {
                    $table->text('functionality')->nullable()->default(null)->change();
                }
                if (Schema::hasColumn('products', 'published')) {
                    $table->boolean('published')->default(false)->nullable()->change();
                }
                if (Schema::hasColumn('products', 'portfolio')) {
                    $table->boolean('portfolio')->default(false)->nullable()->change();
                }
                if (Schema::hasColumn('products', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->change();
                }
                if (Schema::hasColumn('products', 'site')) {
                    $table->string('site')->nullable()->default(null)->change();
                }
            });
        }
    }

    public function down(): void
    {
        // No-op: we won't revert relaxations to avoid breaking existing data.
    }
};


