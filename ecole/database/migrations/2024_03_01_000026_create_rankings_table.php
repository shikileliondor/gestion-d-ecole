<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->comment('Stores class rankings per term and academic year.');
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('term', 50)->index();
            $table->unsignedInteger('rank')->index();
            $table->decimal('average_score', 6, 2)->nullable();
            $table->timestamps();

            $table->unique(['class_id', 'academic_year_id', 'term', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
