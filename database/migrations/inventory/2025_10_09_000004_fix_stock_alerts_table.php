<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_alerts')) {
            // Check if there are any records in the table
            $recordCount = DB::table('stock_alerts')->count();
            
            if ($recordCount == 0) {
                // If there are no records, we can safely drop and recreate the table
                Schema::dropIfExists('stock_alerts');
                
                Schema::create('stock_alerts', function (Blueprint $table) {
                    $table->id();
                    $table->uuid('product_id');
                    $table->unsignedBigInteger('warehouse_id');
                    $table->decimal('seuil_minimum', 10, 2);
                    $table->decimal('seuil_securite', 10, 2);
                    $table->boolean('alerte_active')->default(true);
                    $table->string('email_notification')->nullable();
                    $table->unsignedBigInteger('created_by');
                    $table->timestamps();
                    $table->softDeletes();

                    // Relations
                    $table->foreign('product_id', 'stock_alerts_product_id_fk')
                        ->references('id')
                        ->on('products')
                        ->onDelete('cascade');
                    $table->foreign('warehouse_id')
                        ->references('id')
                        ->on('warehouses')
                        ->onDelete('cascade');
                    $table->foreign('created_by')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                });
            } else {
                // If there are records, we need to be more careful
                Schema::table('stock_alerts', function (Blueprint $table) {
                    // Check if product_id column exists
                    if (!Schema::hasColumn('stock_alerts', 'product_id')) {
                        // Add the product_id column with the correct type to match products.id (UUID)
                        $table->uuid('product_id')->nullable()->after('id');
                    }
                    
                    // Try to add the foreign key constraint
                    try {
                        // First check if the foreign key already exists
                        $table->foreign('product_id', 'stock_alerts_product_id_fk')
                            ->references('id')
                            ->on('products')
                            ->onDelete('cascade');
                    } catch (\Exception $e) {
                        // The foreign key might already exist or there might be data issues
                        \Log::info('Could not add foreign key to stock_alerts.product_id: ' . $e->getMessage());
                    }
                });
            }
        } else {
            // If the table doesn't exist, create it
            Schema::create('stock_alerts', function (Blueprint $table) {
                $table->id();
                $table->uuid('product_id');
                $table->unsignedBigInteger('warehouse_id');
                $table->decimal('seuil_minimum', 10, 2);
                $table->decimal('seuil_securite', 10, 2);
                $table->boolean('alerte_active')->default(true);
                $table->string('email_notification')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->timestamps();
                $table->softDeletes();

                // Relations
                $table->foreign('product_id', 'stock_alerts_product_id_fk')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
                $table->foreign('warehouse_id')
                    ->references('id')
                    ->on('warehouses')
                    ->onDelete('cascade');
                $table->foreign('created_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};