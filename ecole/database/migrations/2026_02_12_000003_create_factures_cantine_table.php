<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures_cantine', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('eleve_id')->constrained('eleves');
            $table->unsignedTinyInteger('mois');
            $table->unsignedSmallInteger('annee');
            $table->decimal('montant_du', 12, 2)->default(0);
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->decimal('solde', 12, 2)->default(0);
            $table->decimal('remise_totale', 12, 2)->default(0);
            $table->date('date_limite')->nullable();
            $table->enum('statut', ['a_jour', 'partiel', 'retard', 'non_concerne'])->default('non_concerne');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures_cantine');
    }
};
