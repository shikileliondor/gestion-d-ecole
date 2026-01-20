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
            $table->dropUnique('student_classes_student_id_class_id_academic_year_id_unique');
            $table->unique(['student_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::table('student_classes', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'academic_year_id']);
            $table->unique(['student_id', 'class_id', 'academic_year_id']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('manual_headcount');
        });
    }
};
