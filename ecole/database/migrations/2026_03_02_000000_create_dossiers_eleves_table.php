<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossiers_eleves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->string('statut', 30)->default('OUVERT');
            $table->date('date_ouverture');
            $table->date('date_derniere_reouverture')->nullable();
            $table->timestamps();

            $table->unique('eleve_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossiers_eleves');
    }
};
