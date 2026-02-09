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
        // Add 2FA and security related fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('two_factor_enabled')->default(false)->after('password');
            $table->timestamp('last_login_at')->nullable()->after('two_factor_enabled');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->text('login_restrictions')->nullable()->after('last_login_ip'); // JSON for time/device restrictions
            $table->unsignedBigInteger('company_id')->nullable()->after('login_restrictions');
            
            $table->index(['company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_enabled',
                'last_login_at',
                'last_login_ip',
                'login_restrictions',
                'company_id'
            ]);
        });
    }
};