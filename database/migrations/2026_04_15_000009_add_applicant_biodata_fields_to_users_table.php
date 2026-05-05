<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('contact_number');
            }

            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('address');
            }

            if (!Schema::hasColumn('users', 'desired_job_title')) {
                $table->string('desired_job_title')->nullable()->after('date_of_birth');
            }

            if (!Schema::hasColumn('users', 'skills')) {
                $table->text('skills')->nullable()->after('desired_job_title');
            }

            if (!Schema::hasColumn('users', 'work_experience')) {
                $table->text('work_experience')->nullable()->after('skills');
            }

            if (!Schema::hasColumn('users', 'education')) {
                $table->text('education')->nullable()->after('work_experience');
            }

            if (!Schema::hasColumn('users', 'resume_path')) {
                $table->string('resume_path')->nullable()->after('education');
            }

            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('resume_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }

            if (Schema::hasColumn('users', 'resume_path')) {
                $table->dropColumn('resume_path');
            }

            if (Schema::hasColumn('users', 'education')) {
                $table->dropColumn('education');
            }

            if (Schema::hasColumn('users', 'work_experience')) {
                $table->dropColumn('work_experience');
            }

            if (Schema::hasColumn('users', 'skills')) {
                $table->dropColumn('skills');
            }

            if (Schema::hasColumn('users', 'desired_job_title')) {
                $table->dropColumn('desired_job_title');
            }

            if (Schema::hasColumn('users', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }

            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('users', 'contact_number')) {
                $table->dropColumn('contact_number');
            }
        });
    }
};
