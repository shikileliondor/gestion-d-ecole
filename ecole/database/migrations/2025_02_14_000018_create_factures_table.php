<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero_facture', 40)->unique();
            $table->foreignId('inscription_id')->constrained('inscriptions');
            $table->date('date_emission');
            $table->decimal('montant_total', 12, 2);
            $table->enum('statut', ['EMISE', 'PARTIELLE', 'PAYEE', 'ANNULEE']);
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
