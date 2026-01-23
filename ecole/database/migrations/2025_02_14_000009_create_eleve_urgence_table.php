<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleve_urgence', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('eleve_id')->unique()->constrained('eleves');
            $table->string('nom_complet', 180);
            $table->enum('lien', ['PERE', 'MERE', 'TUTEUR', 'FRERE_SOEUR', 'AUTRE'])->nullable();
            $table->string('telephone', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleve_urgence');
    }
};
