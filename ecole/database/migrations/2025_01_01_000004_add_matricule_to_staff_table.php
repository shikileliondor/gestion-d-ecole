<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('matricule', 50)->unique()->after('code_personnel');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropUnique(['matricule']);
            $table->dropColumn('matricule');
        });
    }
};
