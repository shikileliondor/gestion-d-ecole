<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->enum('type', ['INTERRO', 'DEVOIR', 'COMPOSITION', 'ORAL', 'PRATIQUE']);
            $table->string('titre', 120)->nullable();
            $table->date('date_evaluation');
            $table->decimal('note_sur', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
