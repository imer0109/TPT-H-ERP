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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('code_client')->unique();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nom_raison_sociale');
            $table->string('name');
            $table->string('comment');
            $table->string('site')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
