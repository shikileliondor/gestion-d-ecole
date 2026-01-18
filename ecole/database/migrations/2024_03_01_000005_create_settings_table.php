<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->comment('Stores configurable key-value settings for the school.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('group', 50)->index();
            $table->string('key', 100)->index();
            $table->text('value')->nullable();
            $table->string('type', 30)->default('string');
            $table->boolean('is_public')->default(false)->index();
            $table->timestamps();

            $table->unique(['school_id', 'group', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
