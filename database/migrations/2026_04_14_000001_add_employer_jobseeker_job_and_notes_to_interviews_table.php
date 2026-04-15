<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->foreignId('employer_id')->nullable()->after('application_id')->constrained('users')->nullOnDelete();
            $table->foreignId('jobseeker_id')->nullable()->after('employer_id')->constrained('users')->nullOnDelete();
            $table->foreignId('job_id')->nullable()->after('jobseeker_id')->constrained()->nullOnDelete();
            $table->text('notes')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('job_id');
            $table->dropConstrainedForeignId('jobseeker_id');
            $table->dropConstrainedForeignId('employer_id');
            $table->dropColumn('notes');
        });
    }
};
