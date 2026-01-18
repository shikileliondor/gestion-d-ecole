<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->comment('Stores comprehensive student profiles and enrollment status.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->string('admission_number', 50)->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->index();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('blood_type', 10)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('photo_path')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->string('previous_school')->nullable();
            $table->boolean('needs_special_care')->default(false)->index();
            $table->text('medical_notes')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 30)->nullable();
            $table->enum('status', ['active', 'suspended', 'transferred', 'graduated', 'inactive'])
                ->default('active')
                ->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'last_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
