<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_documents', function (Blueprint $table) {
            $table->comment('Stores uploaded documents for staff members.');
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->enum('type_document', ['CNI', 'CONTRAT', 'DIPLOME', 'CV', 'ATTESTATION', 'AUTRE']);
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->string('fichier_url');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('taille')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_documents');
    }
};
