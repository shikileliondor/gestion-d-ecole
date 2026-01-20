<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->comment('Stores academic terms for each academic year.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->unsignedTinyInteger('sequence');
            $table->string('name', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();

            $table->unique(['academic_year_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
