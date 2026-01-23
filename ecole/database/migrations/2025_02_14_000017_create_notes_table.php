<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('evaluation_id')->constrained('evaluations');
            $table->foreignId('inscription_id')->constrained('inscriptions');
            $table->decimal('valeur', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
