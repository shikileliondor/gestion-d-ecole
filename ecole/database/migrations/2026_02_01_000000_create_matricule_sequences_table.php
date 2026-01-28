<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matricule_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 50);
            $table->string('year', 4);
            $table->unsignedInteger('last_sequence')->default(0);
            $table->timestamps();

            $table->unique(['entity_type', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matricule_sequences');
    }
};
