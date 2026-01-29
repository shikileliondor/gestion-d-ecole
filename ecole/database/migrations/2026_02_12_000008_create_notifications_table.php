<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('eleve_id')->constrained('eleves');
            $table->enum('canal', ['sms', 'whatsapp', 'email']);
            $table->string('destinataire', 120)->nullable();
            $table->text('message');
            $table->enum('statut', ['brouillon', 'envoyee', 'echec'])->default('brouillon');
            $table->timestamp('envoye_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
