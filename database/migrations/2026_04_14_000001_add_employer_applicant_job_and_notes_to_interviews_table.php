<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            if (!Schema::hasColumn('interviews', 'employer_id')) {
                $table->foreignId('employer_id')->nullable()->after('application_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('interviews', 'applicant_id')) {
                $table->foreignId('applicant_id')->nullable()->after('employer_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('interviews', 'job_id')) {
                $table->foreignId('job_id')->nullable()->after('applicant_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('interviews', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            if (Schema::hasColumn('interviews', 'job_id')) {
                $table->dropConstrainedForeignId('job_id');
            }
            if (Schema::hasColumn('interviews', 'applicant_id')) {
                $table->dropConstrainedForeignId('applicant_id');
            }
            if (Schema::hasColumn('interviews', 'employer_id')) {
                $table->dropConstrainedForeignId('employer_id');
            }
            if (Schema::hasColumn('interviews', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
