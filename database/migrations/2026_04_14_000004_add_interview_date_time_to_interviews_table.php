<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            if (!Schema::hasColumn('interviews', 'interview_date')) {
                $table->date('interview_date')->nullable()->after('job_id');
            }

            if (!Schema::hasColumn('interviews', 'interview_time')) {
                $table->time('interview_time')->nullable()->after('interview_date');
            }
        });

        DB::statement("UPDATE interviews SET interview_date = DATE(scheduled_at) WHERE scheduled_at IS NOT NULL AND interview_date IS NULL");
        DB::statement("UPDATE interviews SET interview_time = TIME(scheduled_at) WHERE scheduled_at IS NOT NULL AND interview_time IS NULL");
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            if (Schema::hasColumn('interviews', 'interview_time')) {
                $table->dropColumn('interview_time');
            }

            if (Schema::hasColumn('interviews', 'interview_date')) {
                $table->dropColumn('interview_date');
            }
        });
    }
};
