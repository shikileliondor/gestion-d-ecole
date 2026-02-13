<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_connexions', function (Blueprint $table) {
            $table->string('statut', 20)->default('SUCCES')->after('date_connexion');
            $table->string('origine', 20)->default('WEB')->after('statut');
            $table->string('session_id', 120)->nullable()->after('origine');
            $table->string('email_tente', 150)->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('journal_connexions', function (Blueprint $table) {
            $table->dropColumn(['statut', 'origine', 'session_id', 'email_tente']);
        });
    }
};
