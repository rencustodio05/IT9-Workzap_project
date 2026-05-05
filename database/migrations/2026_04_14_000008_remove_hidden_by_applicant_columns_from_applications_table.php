<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'hidden_by_applicant_at')) {
                $table->dropColumn('hidden_by_applicant_at');
            }

            if (Schema::hasColumn('applications', 'hidden_by_applicant')) {
                $table->dropColumn('hidden_by_applicant');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'hidden_by_applicant')) {
                $table->boolean('hidden_by_applicant')->default(false)->after('status');
            }

            if (!Schema::hasColumn('applications', 'hidden_by_applicant_at')) {
                $table->timestamp('hidden_by_applicant_at')->nullable()->after('hidden_by_applicant');
            }
        });
    }
};
