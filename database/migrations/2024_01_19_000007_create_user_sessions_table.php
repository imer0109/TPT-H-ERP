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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('session_id')->unique();
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('device_type')->default('desktop'); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->json('location')->nullable(); // country, city, timezone
            $table->string('timezone')->nullable();
            $table->boolean('is_suspicious')->default(false);
            $table->timestamp('login_at')->useCurrent();
            $table->timestamp('last_activity')->useCurrent();
            $table->timestamp('logout_at')->nullable();
            $table->string('logout_type')->nullable(); // normal, timeout, forced, admin
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index(['session_id']);
            $table->index(['ip_address']);
            $table->index(['is_suspicious']);
            $table->index(['login_at']);
            $table->index(['last_activity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};