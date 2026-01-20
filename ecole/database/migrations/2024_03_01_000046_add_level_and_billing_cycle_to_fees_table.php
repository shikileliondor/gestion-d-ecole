<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->string('level', 50)->nullable()->after('academic_year_id')->index();
            $table->string('billing_cycle', 50)->nullable()->after('fee_type');
            $table->text('payment_terms')->nullable()->after('billing_cycle');
        });
    }

    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn(['level', 'billing_cycle', 'payment_terms']);
        });
    }
};
