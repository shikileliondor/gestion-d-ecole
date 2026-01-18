<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_movements', function (Blueprint $table) {
            $table->comment('Tracks asset transfers and movements across locations.');
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->foreignId('moved_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->timestamp('moved_at')->nullable()->index();
            $table->string('condition', 50)->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_movements');
    }
};
