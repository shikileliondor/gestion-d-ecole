<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matricule_sequences', function (Blueprint $table) {
            $table->comment('Tracks per-entity matricule sequences scoped by academic year.');
            $table->id();
            $table->string('entity_type', 20);
            $table->string('academic_year_code', 20);
            $table->unsignedBigInteger('last_sequence')->default(0);
            $table->timestamps();

            $table->unique(['entity_type', 'academic_year_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matricule_sequences');
    }
};
