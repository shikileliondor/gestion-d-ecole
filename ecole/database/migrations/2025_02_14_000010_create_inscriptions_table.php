<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->foreignId('eleve_id')->constrained('eleves');
            $table->foreignId('classe_id')->constrained('classes');
            $table->date('date_inscription');
            $table->enum('statut', ['INSCRIT', 'REDOUBLANT', 'TRANSFERE', 'ABANDON', 'EXCLU'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
