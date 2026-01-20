<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE academic_years MODIFY COLUMN status ENUM('planned', 'active', 'closed', 'archived') DEFAULT 'planned'");
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE academic_years DROP CONSTRAINT IF EXISTS academic_years_status_check");
            DB::statement("ALTER TABLE academic_years ADD CONSTRAINT academic_years_status_check CHECK (status IN ('planned', 'active', 'closed', 'archived'))");
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE academic_years MODIFY COLUMN status ENUM('planned', 'active', 'closed') DEFAULT 'planned'");
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE academic_years DROP CONSTRAINT IF EXISTS academic_years_status_check");
            DB::statement("ALTER TABLE academic_years ADD CONSTRAINT academic_years_status_check CHECK (status IN ('planned', 'active', 'closed'))");
        }
    }
};
