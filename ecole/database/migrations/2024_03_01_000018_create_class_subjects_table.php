<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->comment('Maps subjects to classes and assigned teachers.');
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->unsignedTinyInteger('coefficient')->default(1);
            $table->boolean('is_optional')->default(false)->index();
            $table->timestamps();

            $table->unique(['class_id', 'subject_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subjects');
    }
};
