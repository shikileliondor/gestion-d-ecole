<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remises_cantine', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('facture_cantine_id')->constrained('factures_cantine');
            $table->enum('type', ['fixe', 'pourcentage']);
            $table->decimal('valeur', 12, 2);
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remises_cantine');
    }
};
