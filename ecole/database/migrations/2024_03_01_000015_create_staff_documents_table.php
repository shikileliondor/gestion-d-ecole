<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_documents', function (Blueprint $table) {
            $table->comment('Associates staff members with their uploaded documents.');
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->boolean('is_required')->default(false)->index();
            $table->enum('status', ['pending', 'received', 'verified'])->default('pending')->index();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['staff_id', 'document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_documents');
    }
};
