<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->comment('Tracks payments made by students for specific fees.');
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('fee_id')->constrained('fees')->cascadeOnDelete();
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);
            $table->date('payment_date')->nullable()->index();
            $table->string('method', 50)->nullable()->index();
            $table->string('reference', 100)->nullable()->index();
            $table->enum('status', ['pending', 'partial', 'paid', 'cancelled'])->default('pending')->index();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['student_id', 'fee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
