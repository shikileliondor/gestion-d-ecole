<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulletins', function (Blueprint $table) {
            $table->comment('Summarizes student performance for a term or period.');
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('term', 50)->index();
            $table->decimal('average_score', 6, 2)->nullable();
            $table->unsignedInteger('rank')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id', 'term']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
