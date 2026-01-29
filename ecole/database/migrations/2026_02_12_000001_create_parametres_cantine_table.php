<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres_cantine', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('jour_limite_paiement')->default(5);
            $table->boolean('prorata_actif')->default(false);
            $table->boolean('remises_autorisees')->default(true);
            $table->boolean('statut_partiel_apres_date')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres_cantine');
    }
};
