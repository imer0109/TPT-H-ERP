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
        Schema::create('client_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type_interaction', [
                'appel_telephonique',
                'visite_commerciale',
                'email',
                'message_whatsapp',
                'reunion',
                'autre'
            ]);
            $table->text('description');
            $table->timestamp('date_interaction');
            $table->text('resultat')->nullable();
            $table->boolean('suivi_necessaire')->default(false);
            $table->timestamp('date_suivi')->nullable();
            $table->foreignId('campagne_id')->nullable(); // Référence à une future table campagnes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_interactions');
    }
};