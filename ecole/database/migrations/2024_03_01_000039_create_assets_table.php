<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->comment('Stores tangible assets owned by the school.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('asset_category_id')->constrained('asset_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->string('serial_number')->nullable()->index();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->decimal('current_value', 12, 2)->nullable();
            $table->string('location')->nullable()->index();
            $table->string('condition', 50)->nullable();
            $table->enum('status', ['active', 'maintenance', 'retired'])->default('active')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('staff')->nullOnDelete();
            $table->date('warranty_end_date')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'asset_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
