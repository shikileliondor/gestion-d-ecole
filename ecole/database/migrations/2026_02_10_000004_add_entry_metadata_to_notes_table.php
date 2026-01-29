<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->after('inscription_id')->constrained('periodes');
            $table->date('date_saisie')->nullable()->after('periode_id');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('periode_id');
            $table->dropColumn('date_saisie');
        });
    }
};
