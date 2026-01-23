<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('teachers');
    }

    public function down(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->comment('Legacy table previously storing pedagogical profiles.');
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete()->unique();
            $table->string('teacher_code', 50)->unique();
            $table->string('grade')->nullable();
            $table->string('speciality')->nullable();
            $table->string('qualification')->nullable();
            $table->unsignedSmallInteger('teaching_load_hours')->nullable();
            $table->string('pedagogical_responsibility')->nullable();
            $table->date('start_teaching_date')->nullable();
            $table->unsignedSmallInteger('teaching_experience_years')->nullable();
            $table->text('research_interests')->nullable();
            $table->text('professional_development')->nullable();
            $table->string('teacher_evaluation')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'teacher_code']);
        });
    }
};
