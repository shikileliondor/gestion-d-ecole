<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignant_urgence', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('enseignant_id')->unique()->constrained('enseignants');
            $table->string('nom_complet', 180)->nullable();
            $table->enum('lien', ['PERE', 'MERE', 'CONJOINT', 'FRERE_SOEUR', 'AUTRE'])->nullable();
            $table->string('telephone', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignant_urgence');
    }
};
