<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_frais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libelle', 80);
            $table->boolean('obligatoire');
            $table->boolean('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_frais');
    }
};
