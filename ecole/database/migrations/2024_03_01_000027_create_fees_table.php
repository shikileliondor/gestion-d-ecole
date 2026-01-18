<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->comment('Defines school fees that can be assigned to classes.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('due_date')->nullable()->index();
            $table->string('fee_type', 50)->nullable()->index();
            $table->boolean('is_mandatory')->default(true)->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();

            $table->index(['school_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
