<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('remises', function (Blueprint $table) {
            $table->foreignId('facture_id')->nullable()->after('inscription_id')->constrained('factures');
            $table->foreignId('periode_id')->nullable()->after('facture_id')->constrained('periodes');
            $table->enum('type_calcul', ['MONTANT', 'POURCENTAGE'])->default('MONTANT')->after('type_remise');
            $table->decimal('valeur', 12, 2)->default(0)->after('type_calcul');
            $table->decimal('montant_applique', 12, 2)->default(0)->after('valeur');
            $table->string('description', 255)->nullable()->after('motif');
            $table->foreignId('accordee_par')->nullable()->after('description')->constrained('users');
            $table->foreignId('validee_par')->nullable()->after('accordee_par')->constrained('users');
            $table->timestamp('validee_le')->nullable()->after('validee_par');
            $table->text('motif_annulation')->nullable()->after('validee_le');
            $table->timestamp('annule_le')->nullable()->after('motif_annulation');
            $table->foreignId('annule_par')->nullable()->after('annule_le')->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('remises', function (Blueprint $table) {
            $table->dropConstrainedForeignId('annule_par');
            $table->dropConstrainedForeignId('validee_par');
            $table->dropConstrainedForeignId('accordee_par');
            $table->dropConstrainedForeignId('periode_id');
            $table->dropConstrainedForeignId('facture_id');
            $table->dropColumn([
                'type_calcul',
                'valeur',
                'montant_applique',
                'description',
                'validee_le',
                'motif_annulation',
                'annule_le',
            ]);
        });
    }
};
