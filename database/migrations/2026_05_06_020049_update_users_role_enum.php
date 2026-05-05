<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the role enum to ensure it has the correct values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('applicant', 'employer', 'admin') NOT NULL DEFAULT 'applicant'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for fixing the enum, so down doesn't need to change it back
        // as the original migration already has the correct enum
    }
};
