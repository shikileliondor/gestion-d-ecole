<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes_classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->unique(['annee_scolaire_id', 'classe_id', 'matiere_id'], 'programme_classe_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes_classes');
    }
};
