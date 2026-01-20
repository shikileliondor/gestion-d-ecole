<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('series', 50)->nullable()->after('level')->index();
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->string('series', 50)->nullable()->after('level')->index();
        });

        Schema::table('class_subjects', function (Blueprint $table) {
            $table->string('color', 7)->nullable()->after('coefficient');
        });

        Schema::create('class_subject_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_subject_id')->constrained('class_subjects')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['class_subject_id', 'staff_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subject_staff');

        Schema::table('class_subjects', function (Blueprint $table) {
            $table->dropColumn('color');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('series');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('series');
        });
    }
};
