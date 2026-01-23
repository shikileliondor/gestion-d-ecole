<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('echeanciers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('frais_inscription_id')->constrained('frais_inscriptions');
            $table->decimal('montant_prevu', 12, 2);
            $table->date('date_echeance');
            $table->enum('statut', ['A_PAYER', 'PAYE', 'RETARD']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('echeanciers');
    }
};
