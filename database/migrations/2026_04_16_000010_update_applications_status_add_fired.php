<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend enum to include fired so employer fire action can persist.
        DB::statement("ALTER TABLE applications MODIFY status ENUM('pending','interview','hired','rejected','fired') NOT NULL DEFAULT 'pending'");

        if (!Schema::hasColumn('applications', 'fired_at')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->timestamp('fired_at')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('applications', 'fired_at')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('fired_at');
            });
        }

        // Ensure rollback does not fail when fired records exist.
        DB::table('applications')->where('status', 'fired')->update(['status' => 'rejected']);

        DB::statement("ALTER TABLE applications MODIFY status ENUM('pending','interview','hired','rejected') NOT NULL DEFAULT 'pending'");
    }
};
