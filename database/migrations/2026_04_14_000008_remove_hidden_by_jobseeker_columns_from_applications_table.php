<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'hidden_by_jobseeker_at')) {
                $table->dropColumn('hidden_by_jobseeker_at');
            }

            if (Schema::hasColumn('applications', 'hidden_by_jobseeker')) {
                $table->dropColumn('hidden_by_jobseeker');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'hidden_by_jobseeker')) {
                $table->boolean('hidden_by_jobseeker')->default(false)->after('status');
            }

            if (!Schema::hasColumn('applications', 'hidden_by_jobseeker_at')) {
                $table->timestamp('hidden_by_jobseeker_at')->nullable()->after('hidden_by_jobseeker');
            }
        });
    }
};
