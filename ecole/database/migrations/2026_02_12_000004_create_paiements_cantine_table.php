<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements_cantine', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('facture_cantine_id')->constrained('factures_cantine');
            $table->decimal('montant', 12, 2);
            $table->string('mode_paiement', 60)->nullable();
            $table->date('date_paiement')->nullable();
            $table->string('reference', 100)->nullable();
            $table->string('encaisse_par', 120)->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements_cantine');
    }
};
