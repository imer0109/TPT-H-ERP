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
        Schema::create('supplier_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fournisseur_id');
            $table->unsignedBigInteger('evaluated_by')->nullable();
            $table->integer('quality_rating')->nullable(); // 1-5 stars for quality
            $table->integer('delivery_rating')->nullable(); // 1-5 stars for delivery time
            $table->integer('responsiveness_rating')->nullable(); // 1-5 stars for responsiveness
            $table->integer('pricing_rating')->nullable(); // 1-5 stars for pricing
            $table->text('comments')->nullable();
            $table->decimal('overall_score', 3, 2)->nullable(); // Overall score out of 5
            $table->date('evaluation_date')->default(now());
            $table->timestamps();
            
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('cascade');
            $table->foreign('evaluated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_ratings');
    }
};