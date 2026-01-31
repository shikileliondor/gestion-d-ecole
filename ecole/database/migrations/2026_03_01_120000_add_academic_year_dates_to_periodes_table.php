<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodes', function (Blueprint $table) {
            $table->foreignId('annee_scolaire_id')
                ->nullable()
                ->after('id')
                ->constrained('annees_scolaires');
            $table->date('date_debut')->nullable()->after('ordre');
            $table->date('date_fin')->nullable()->after('date_debut');
        });
    }

    public function down(): void
    {
        Schema::table('periodes', function (Blueprint $table) {
            $table->dropForeign(['annee_scolaire_id']);
            $table->dropColumn(['annee_scolaire_id', 'date_debut', 'date_fin']);
        });
    }
};
