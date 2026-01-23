<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignant_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('enseignant_id')->constrained('enseignants');
            $table->enum('type_document', ['CNI', 'DIPLOME', 'CONTRAT', 'CV', 'AUTRE']);
            $table->string('libelle', 120)->nullable();
            $table->text('file_path')->nullable();
            $table->string('original_name', 255)->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignant_documents');
    }
};
