<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('inscription_id')->constrained('inscriptions');
            $table->enum('type_remise', ['BOURSE', 'REDUCTION', 'GESTE']);
            $table->decimal('montant', 12, 2);
            $table->string('motif', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remises');
    }
};
