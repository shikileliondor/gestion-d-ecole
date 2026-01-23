<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frais_inscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('inscription_id')->constrained('inscriptions');
            $table->foreignId('frais_id')->constrained('frais');
            $table->decimal('montant_du', 12, 2);
            $table->enum('statut', ['IMPAYE', 'PARTIEL', 'SOLDE'])->default('IMPAYE');
            $table->timestamps();

            $table->unique(['inscription_id', 'frais_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frais_inscriptions');
    }
};
