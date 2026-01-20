<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->unsignedInteger('manual_headcount')->nullable()->after('capacity');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->string('level', 50)->nullable()->after('name')->index();
        });

        Schema::table('student_classes', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['academic_year_id']);
            $table->dropUnique('student_classes_student_id_class_id_academic_year_id_unique');
            $table->unique(['student_id', 'academic_year_id']);
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student_classes', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['academic_year_id']);
            $table->dropUnique(['student_id', 'academic_year_id']);
            $table->unique(['student_id', 'class_id', 'academic_year_id']);
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->cascadeOnDelete();
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('manual_headcount');
        });
    }
};
