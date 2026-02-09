<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->integer('quantite')->default(0);
            $table->decimal('prix_unitaire', 15, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

