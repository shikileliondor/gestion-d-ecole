<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->comment('Defines academic years for organizing classes, fees, and results.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false)->index();
            $table->enum('status', ['planned', 'active', 'closed'])->default('planned')->index();
            $table->timestamps();

            $table->unique(['school_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
