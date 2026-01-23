<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('matricule', 32)->unique();
            $table->string('nom', 100)->nullable();
            $table->string('prenoms', 150)->nullable();
            $table->enum('sexe', ['M', 'F', 'AUTRE'])->nullable();
            $table->string('telephone_1', 30)->nullable();
            $table->string('telephone_2', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('specialite', 80)->nullable();
            $table->text('photo_path')->nullable();
            $table->enum('type_enseignant', ['PERMANENT', 'VACATAIRE', 'STAGIAIRE']);
            $table->date('date_debut_service');
            $table->date('date_fin_service')->nullable();
            $table->enum('statut', ['ACTIF', 'SUSPENDU', 'PARTI']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
