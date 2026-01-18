<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_classes', function (Blueprint $table) {
            $table->comment('Associates fees with classes they apply to.');
            $table->id();
            $table->foreignId('fee_id')->constrained('fees')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['fee_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_classes');
    }
};
