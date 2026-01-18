<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_contracts', function (Blueprint $table) {
            $table->comment('Stores staff contracts and their attached documents.');
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->enum('contract_type', ['cdi', 'cdd', 'vacation'])->index();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active')->index();
            $table->timestamps();

            $table->index(['staff_id', 'contract_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_contracts');
    }
};
