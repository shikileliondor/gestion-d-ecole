<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences_eleves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('eleve_id')->constrained('eleves');
            $table->date('date_absence');
            $table->boolean('justifiee')->default(false);
            $table->text('motif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences_eleves');
    }
};
