<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->string('admission_number')->unique();
            $table->string('matricule', 32)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->enum('gender', ['M', 'F', 'OTHER'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth', 120)->nullable();
            $table->string('nationality', 80)->nullable();
            $table->string('blood_type', 10)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->unique()->nullable();
            $table->date('enrollment_date')->nullable();
            $table->string('previous_school')->nullable();
            $table->boolean('needs_special_care')->default(false);
            $table->text('medical_notes')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated', 'transferred'])->default('active');
            $table->timestamps();

            $table->index(['school_id', 'academic_year_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
