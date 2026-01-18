<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_assignments', function (Blueprint $table) {
            $table->comment('Tracks teaching assignments for staff over time.');
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->dateTime('assigned_at')->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();

            $table->index(['staff_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_assignments');
    }
};
