<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_matieres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('niveau_id')->constrained('niveaux');
            $table->foreignId('serie_id')->nullable()->constrained('series');
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->decimal('coefficient', 5, 2);
            $table->boolean('obligatoire');
            $table->boolean('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_matieres');
    }
};
