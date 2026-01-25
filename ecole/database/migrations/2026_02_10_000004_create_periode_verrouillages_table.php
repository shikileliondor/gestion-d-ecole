<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_verrouillages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('periode_id')->constrained('periodes');
            $table->boolean('verrouille')->default(false);
            $table->timestamps();

            $table->unique(['annee_scolaire_id', 'classe_id', 'periode_id'], 'periode_verrouillage_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_verrouillages');
    }
};
