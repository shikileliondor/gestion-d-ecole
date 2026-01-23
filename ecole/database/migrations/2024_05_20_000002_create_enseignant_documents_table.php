<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignant_documents', function (Blueprint $table) {
            $table->comment('Documents uploadés librement pour les enseignants.');
            $table->id();
            $table->foreignId('enseignant_id')->constrained('enseignants')->cascadeOnDelete();
            $table->enum('type_document', ['CNI', 'DIPLOME', 'CONTRAT', 'CV', 'COURS', 'AUTRE']);
            $table->string('libelle');
            $table->string('description')->nullable();
            $table->string('fichier_url');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('taille')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignant_documents');
    }
};
