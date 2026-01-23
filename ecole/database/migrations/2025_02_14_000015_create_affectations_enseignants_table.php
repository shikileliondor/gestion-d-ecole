<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectations_enseignants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('enseignant_id')->constrained('enseignants');
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectations_enseignants');
    }
};
