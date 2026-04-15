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
            DB::statement("ALTER TABLE applications MODIFY status ENUM('pending','interview','hired','rejected','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("UPDATE applications SET status = 'rejected' WHERE status = 'cancelled'");
            DB::statement("ALTER TABLE applications MODIFY status ENUM('pending','interview','hired','rejected') NOT NULL DEFAULT 'pending'");
        }
    }
};
