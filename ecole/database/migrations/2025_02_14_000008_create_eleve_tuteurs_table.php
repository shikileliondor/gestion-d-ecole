<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleve_tuteurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('eleve_id')->constrained('eleves');
            $table->enum('lien', ['PERE', 'MERE', 'TUTEUR', 'AUTRE']);
            $table->string('nom', 120);
            $table->string('prenoms', 150)->nullable();
            $table->string('telephone_1', 30);
            $table->string('telephone_2', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('profession', 120)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleve_tuteurs');
    }
};
