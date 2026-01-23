<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('matricule', 32)->unique();
            $table->string('nom', 100);
            $table->string('prenoms', 150);
            $table->enum('sexe', ['M', 'F', 'AUTRE'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance', 120)->nullable();
            $table->string('nationalite', 80)->nullable();
            $table->text('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
