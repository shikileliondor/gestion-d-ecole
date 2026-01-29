<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes_eleves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('eleve_id')->constrained('eleves');
            $table->date('date_note')->nullable();
            $table->text('notes')->nullable();
            $table->text('commentaires')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes_eleves');
    }
};
