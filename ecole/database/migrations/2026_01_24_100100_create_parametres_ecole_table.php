<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres_ecole', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('logo_path')->nullable();
            $table->text('signature_path')->nullable();
            $table->text('cachet_path')->nullable();
            $table->string('facture_prefix', 50)->nullable();
            $table->string('recu_prefix', 50)->nullable();
            $table->string('matricule_prefix', 50)->nullable();
            $table->boolean('remises_actives')->default(false);
            $table->unsignedSmallInteger('plafond_remise')->nullable();
            $table->string('validation_remise', 120)->nullable();
            $table->string('politique_impayes', 60)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres_ecole');
    }
};
