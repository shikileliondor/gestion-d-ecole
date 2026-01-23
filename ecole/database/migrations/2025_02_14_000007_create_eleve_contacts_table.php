<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleve_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('eleve_id')->unique()->constrained('eleves');
            $table->string('telephone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->string('commune', 120)->nullable();
            $table->string('ville', 120)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleve_contacts');
    }
};
