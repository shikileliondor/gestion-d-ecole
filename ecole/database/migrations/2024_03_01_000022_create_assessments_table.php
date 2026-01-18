<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->comment('Defines assessments such as exams, quizzes, or assignments.');
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('name');
            $table->string('type', 50)->nullable()->index();
            $table->decimal('max_score', 6, 2)->default(100);
            $table->decimal('weight', 5, 2)->default(1);
            $table->date('assessment_date')->nullable()->index();
            $table->timestamps();

            $table->index(['class_id', 'subject_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
