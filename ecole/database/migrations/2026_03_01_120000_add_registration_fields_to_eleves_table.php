<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->string('matricule_national', 50)->nullable()->after('matricule');
            $table->string('etablissement_origine', 150)->nullable()->after('nationalite');
            $table->date('date_arrivee')->nullable()->after('etablissement_origine');
            $table->string('classe_precedente', 100)->nullable()->after('date_arrivee');
        });
    }

    public function down(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->dropColumn([
                'matricule_national',
                'etablissement_origine',
                'date_arrivee',
                'classe_precedente',
            ]);
        });
    }
};
