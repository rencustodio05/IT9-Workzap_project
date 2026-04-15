<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE applications MODIFY status ENUM('pending','interview','hired','rejected','cancelled','fired') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("UPDATE applications SET status = 'cancelled' WHERE status = 'fired'");
        DB::statement("ALTER TABLE applications MODIFY status ENUM('pending','interview','hired','rejected','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
