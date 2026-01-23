<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->string('matricule', 50)->unique()->after('code_enseignant');
        });
    }

    public function down(): void
    {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->dropUnique(['matricule']);
            $table->dropColumn('matricule');
        });
    }
};
