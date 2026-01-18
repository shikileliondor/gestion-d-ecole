<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appreciations', function (Blueprint $table) {
            $table->comment('Stores qualitative feedback tied to a bulletin.');
            $table->id();
            $table->foreignId('bulletin_id')->constrained('bulletins')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appreciations');
    }
};
