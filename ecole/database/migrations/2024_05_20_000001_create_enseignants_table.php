<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->comment('Fiche enseignant avec volet RH léger.');
            $table->id();
            $table->string('code_enseignant', 50)->unique();
            $table->string('nom');
            $table->string('prenoms');
            $table->enum('sexe', ['M', 'F', 'Autre'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('photo_url')->nullable();

            $table->string('telephone_1', 30);
            $table->string('telephone_2', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();

            $table->string('specialite');
            $table->enum('niveau_enseignement', ['COLLEGE', 'LYCEE', 'COLLEGE_LYCEE'])->nullable();
            $table->string('qualification')->nullable();

            $table->enum('type_enseignant', ['PERMANENT', 'VACATAIRE', 'STAGIAIRE']);
            $table->date('date_debut_service');
            $table->date('date_fin_service')->nullable();
            $table->enum('statut', ['ACTIF', 'SUSPENDU', 'PARTI']);
            $table->string('num_cni')->nullable();
            $table->date('date_expiration_cni')->nullable();

            $table->string('contact_urgence_nom')->nullable();
            $table->enum('contact_urgence_lien', ['PERE', 'MERE', 'CONJOINT', 'FRERE_SOEUR', 'TUTEUR', 'AUTRE'])->nullable();
            $table->string('contact_urgence_tel', 30)->nullable();

            $table->enum('mode_paiement', ['MOBILE_MONEY', 'VIREMENT', 'CASH'])->nullable();
            $table->string('numero_paiement')->nullable();
            $table->decimal('salaire_base', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
