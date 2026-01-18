<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->comment('Stores parent or guardian profiles linked to students.');
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->index();
            $table->string('relationship', 50)->nullable();
            $table->string('phone', 30)->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->string('national_id', 100)->nullable()->index();
            $table->boolean('is_primary')->default(false)->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
