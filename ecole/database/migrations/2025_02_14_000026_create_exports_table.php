<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('type_export', [
                'BULLETIN',
                'RELEVE_NOTES',
                'FACTURE',
                'RECU',
                'LISTE_CLASSE',
                'RAPPORT_SCOLARITE',
                'RAPPORT_FINANCIER',
            ]);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('format', ['PDF', 'EXCEL']);
            $table->text('file_path');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
