<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annees_scolaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libelle', 20)->unique();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['ACTIVE', 'CLOTUREE', 'ARCHIVEE']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annees_scolaires');
    }
};
