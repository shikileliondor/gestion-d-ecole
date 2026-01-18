<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->comment('Stores generated reports for analytics and compliance.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('type', 50)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('period_start')->nullable()->index();
            $table->date('period_end')->nullable()->index();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'ready', 'archived'])->default('draft')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
