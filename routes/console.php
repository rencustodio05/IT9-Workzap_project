<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\EmployerSubscriptionService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('subscriptions:expire', function (EmployerSubscriptionService $subscriptionService) {
    $expired = $subscriptionService->expireOverdueSubscriptions();
    $this->info("Expired subscriptions processed: {$expired}");
})->purpose('Expire overdue employer subscriptions and close active jobs.');

Schedule::command('subscriptions:expire')->dailyAt('00:10');
