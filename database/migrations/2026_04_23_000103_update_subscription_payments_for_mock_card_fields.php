<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_payments', 'card_number')) {
                $table->string('card_number')->nullable()->after('amount');
            }

            if (!Schema::hasColumn('subscription_payments', 'expiry_date')) {
                $table->string('expiry_date', 5)->nullable()->after('card_number');
            }

            if (!Schema::hasColumn('subscription_payments', 'cvv')) {
                $table->string('cvv', 4)->nullable()->after('expiry_date');
            }
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_payments', 'payment_method')) {
                $table->dropColumn('payment_method');
            }

            if (Schema::hasColumn('subscription_payments', 'reference_number')) {
                $table->dropColumn('reference_number');
            }

            if (Schema::hasColumn('subscription_payments', 'proof_of_payment')) {
                $table->dropColumn('proof_of_payment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('amount');
            }

            if (!Schema::hasColumn('subscription_payments', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('subscription_payments', 'proof_of_payment')) {
                $table->string('proof_of_payment')->nullable()->after('reference_number');
            }
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_payments', 'cvv')) {
                $table->dropColumn('cvv');
            }

            if (Schema::hasColumn('subscription_payments', 'expiry_date')) {
                $table->dropColumn('expiry_date');
            }

            if (Schema::hasColumn('subscription_payments', 'card_number')) {
                $table->dropColumn('card_number');
            }
        });
    }
};
