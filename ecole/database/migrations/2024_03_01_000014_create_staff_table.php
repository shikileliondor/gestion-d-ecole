<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->comment('Stores staff profiles, roles, and employment details.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('staff_number', 50)->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->index();
            $table->date('date_of_birth')->nullable();
            $table->string('national_id', 100)->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('position')->nullable()->index();
            $table->string('department')->nullable()->index();
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->decimal('base_salary', 12, 2)->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
