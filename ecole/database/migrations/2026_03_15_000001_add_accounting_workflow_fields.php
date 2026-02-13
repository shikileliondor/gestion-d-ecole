<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('ADMIN')->after('password');
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->string('profil_facturable', 20)->default('ELEVE')->after('inscription_id');
            $table->string('type_facture', 30)->default('SCOLARITE')->after('profil_facturable');
            $table->date('date_validation')->nullable()->after('date_emission');
            $table->text('motif_annulation')->nullable()->after('commentaire');
        });

        Schema::table('facture_lignes', function (Blueprint $table) {
            $table->string('libelle')->nullable()->after('type_frais_id');
            $table->unsignedInteger('quantite')->default(1)->after('libelle');
            $table->decimal('prix_unitaire', 12, 2)->default(0)->after('quantite');
            $table->decimal('remise', 12, 2)->default(0)->after('prix_unitaire');
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->foreignId('facture_id')->nullable()->after('inscription_id')->constrained('factures');
            $table->string('mode_paiement_libre', 50)->nullable()->after('mode_paiement');
            $table->text('motif_annulation')->nullable()->after('reference');
            $table->timestamp('annule_le')->nullable()->after('motif_annulation');
            $table->foreignId('annule_par')->nullable()->after('annule_le')->constrained('users');
        });

        Schema::table('recus', function (Blueprint $table) {
            $table->text('motif_annulation')->nullable()->after('montant');
            $table->timestamp('annule_le')->nullable()->after('motif_annulation');
            $table->foreignId('annule_par')->nullable()->after('annule_le')->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('recus', function (Blueprint $table) {
            $table->dropConstrainedForeignId('annule_par');
            $table->dropColumn(['mode_paiement_libre', 'motif_annulation', 'annule_le']);
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('annule_par');
            $table->dropConstrainedForeignId('facture_id');
            $table->dropColumn(['mode_paiement_libre', 'motif_annulation', 'annule_le']);
        });

        Schema::table('facture_lignes', function (Blueprint $table) {
            $table->dropColumn(['libelle', 'quantite', 'prix_unitaire', 'remise']);
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn(['profil_facturable', 'type_facture', 'date_validation', 'motif_annulation']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
