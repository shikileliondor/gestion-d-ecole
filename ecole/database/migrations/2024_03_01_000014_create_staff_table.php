<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->comment('Stores non-teaching staff profiles and employment details.');
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('code_personnel', 50)->unique();
            $table->string('nom');
            $table->string('prenoms');
            $table->enum('sexe', ['M', 'F', 'AUTRE'])->nullable()->index();
            $table->date('date_naissance')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('telephone_1', 30);
            $table->string('telephone_2', 30)->nullable();
            $table->string('email')->nullable()->index();
            $table->string('adresse')->nullable();
            $table->string('commune')->nullable();
            $table->enum('categorie_personnel', [
                'ADMINISTRATION',
                'SURVEILLANCE',
                'INTENDANCE',
                'COMPTABILITE',
                'TECHNIQUE',
                'SERVICE',
            ])->index();
            $table->string('poste');
            $table->enum('type_contrat', ['CDI', 'CDD', 'VACATAIRE', 'STAGE'])->index();
            $table->date('date_debut_service');
            $table->date('date_fin_service')->nullable();
            $table->enum('statut', ['ACTIF', 'SUSPENDU', 'PARTI'])->index();
            $table->string('num_cni', 100)->nullable()->index();
            $table->date('date_expiration_cni')->nullable();
            $table->string('contact_urgence_nom')->nullable();
            $table->enum('contact_urgence_lien', [
                'PERE',
                'MERE',
                'CONJOINT',
                'FRERE_SOEUR',
                'TUTEUR',
                'AUTRE',
            ])->nullable();
            $table->string('contact_urgence_tel', 30)->nullable();
            $table->enum('mode_paiement', ['MOBILE_MONEY', 'VIREMENT', 'CASH'])->nullable();
            $table->string('numero_paiement')->nullable();
            $table->decimal('salaire_base', 12, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
