<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('niveau_id')->constrained('niveaux');
            $table->foreignId('type_frais_id')->constrained('types_frais');
            $table->enum('periodicite', ['UNIQUE', 'MENSUEL', 'TRIMESTRIEL', 'ANNUEL']);
            $table->decimal('montant', 12, 2);
            $table->boolean('actif');
            $table->timestamps();

            $table->unique(['annee_scolaire_id', 'niveau_id', 'type_frais_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frais');
    }
};
