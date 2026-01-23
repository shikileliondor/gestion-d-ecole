<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('inscription_id')->constrained('inscriptions');
            $table->foreignId('frais_inscription_id')->constrained('frais_inscriptions');
            $table->decimal('montant_paye', 12, 2);
            $table->date('date_paiement');
            $table->enum('mode_paiement', ['CASH', 'DEPOT', 'VIREMENT_INTERNE']);
            $table->string('reference', 80)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
