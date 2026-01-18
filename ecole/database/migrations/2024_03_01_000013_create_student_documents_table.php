<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_documents', function (Blueprint $table) {
            $table->comment('Associates required or submitted documents with students.');
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->boolean('is_required')->default(false)->index();
            $table->enum('status', ['pending', 'received', 'verified'])->default('pending')->index();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
