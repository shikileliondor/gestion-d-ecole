<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifs_cantine', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires');
            $table->decimal('montant', 12, 2);
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifs_cantine');
    }
};
