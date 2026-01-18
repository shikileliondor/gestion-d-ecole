<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->comment('Catalog of subjects taught in the school.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('code', 50)->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('credit_hours')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();

            $table->unique(['school_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
