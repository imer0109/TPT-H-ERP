<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_alerts', function (Blueprint $table) {
            // Rename email_notification to email_notifications to match the model
            if (Schema::hasColumn('stock_alerts', 'email_notification')) {
                $table->renameColumn('email_notification', 'email_notifications');
            }
            
            // Make sure product_id column exists and is not nullable
            if (!Schema::hasColumn('stock_alerts', 'product_id')) {
                $table->uuid('product_id')->after('id');
                $table->foreign('product_id', 'stock_alerts_product_id_fk')
                    ->references('id')->on('products')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_alerts', function (Blueprint $table) {
            // Revert email_notifications to email_notification
            if (Schema::hasColumn('stock_alerts', 'email_notifications')) {
                $table->renameColumn('email_notifications', 'email_notification');
            }
        });
    }
};