<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->comment('Stores student scores for each assessment.');
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('score', 6, 2)->nullable();
            $table->string('remark')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamps();

            $table->unique(['assessment_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
