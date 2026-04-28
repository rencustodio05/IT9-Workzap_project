<?php

namespace App\Services;

use App\Models\EmployerSubscription;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployerSubscriptionService
{
    /**
     * @return array<string, array{label:string, price:string, amount:float, months:int}>
     */
    public function plans(): array
    {
        return [
            EmployerSubscription::PLAN_MONTHLY => [
                'label' => '$20 - 1 Month',
                'price' => '$20',
                'amount' => 20.00,
                'months' => 1,
            ],
            EmployerSubscription::PLAN_QUARTERLY => [
                'label' => '$50 - 3 Months',
                'price' => '$50',
                'amount' => 50.00,
                'months' => 3,
            ],
            EmployerSubscription::PLAN_YEARLY => [
                'label' => '$100 - 1 Year',
                'price' => '$100',
                'amount' => 100.00,
                'months' => 12,
            ],
        ];
    }

    /**
     * @return array{label:string, price:string, amount:float, months:int}
     */
    public function resolvePlan(string $planType): array
    {
        $plans = $this->plans();

        if (!isset($plans[$planType])) {
            abort(422, 'Invalid subscription plan selected.');
        }

        return $plans[$planType];
    }

    public function hasActiveSubscription(int $employerId): bool
    {
        $today = Carbon::today(config('app.timezone'));

        EmployerSubscription::query()
            ->where('employer_id', $employerId)
            ->where('status', EmployerSubscription::STATUS_ACTIVE)
            ->whereDate('end_date', '<', $today->toDateString())
            ->update(['status' => EmployerSubscription::STATUS_EXPIRED]);

        return EmployerSubscription::query()
            ->where('employer_id', $employerId)
            ->where('status', EmployerSubscription::STATUS_ACTIVE)
            ->whereDate('start_date', '<=', $today->toDateString())
            ->whereDate('end_date', '>=', $today->toDateString())
            ->exists();
    }

    public function enforceEmployerAccessState(int $employerId): bool
    {
        $isActive = $this->hasActiveSubscription($employerId);

        if (!$isActive) {
            $this->closeEmployerJobs($employerId);
        }

        return $isActive;
    }

    public function closeEmployerJobs(int $employerId): int
    {
        return Job::query()
            ->where('user_id', $employerId)
            ->where('status', 'active')
            ->update(['status' => 'inactive']);
    }

    public function activateSubscription(EmployerSubscription $subscription, int $adminId): void
    {
        $plan = $this->resolvePlan($subscription->plan_type);
        $start = Carbon::today(config('app.timezone'));
        $end = (clone $start)->addMonthsNoOverflow($plan['months'])->subDay();

        DB::transaction(function () use ($subscription, $adminId, $start, $end): void {
            EmployerSubscription::query()
                ->where('employer_id', $subscription->employer_id)
                ->where('status', EmployerSubscription::STATUS_ACTIVE)
                ->update(['status' => EmployerSubscription::STATUS_EXPIRED]);

            $subscription->update([
                'status' => EmployerSubscription::STATUS_ACTIVE,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'admin_approved_by' => $adminId,
                'payment_status' => $subscription->payment_status ?? 'paid',
            ]);
        });
    }

    public function expireOverdueSubscriptions(): int
    {
        $today = Carbon::today(config('app.timezone'));

        $expired = EmployerSubscription::query()
            ->where('status', EmployerSubscription::STATUS_ACTIVE)
            ->whereDate('end_date', '<', $today->toDateString())
            ->get(['id', 'employer_id']);

        if ($expired->isEmpty()) {
            return 0;
        }

        $count = 0;

        DB::transaction(function () use ($expired, &$count): void {
            foreach ($expired as $subscription) {
                EmployerSubscription::query()
                    ->whereKey($subscription->id)
                    ->update(['status' => EmployerSubscription::STATUS_EXPIRED]);

                $this->closeEmployerJobs((int) $subscription->employer_id);
                $count++;
            }

            // Safety net: any employer without an active subscription should not keep active job posts.
            $activeEmployerIds = EmployerSubscription::query()
                ->where('status', EmployerSubscription::STATUS_ACTIVE)
                ->whereDate('start_date', '<=', Carbon::today(config('app.timezone'))->toDateString())
                ->whereDate('end_date', '>=', Carbon::today(config('app.timezone'))->toDateString())
                ->pluck('employer_id');

            $employerIds = User::query()
                ->where('role', 'employer')
                ->pluck('id');

            $inactiveEmployerIds = $employerIds->diff($activeEmployerIds);

            if ($inactiveEmployerIds->isNotEmpty()) {
                Job::query()
                    ->where('status', 'active')
                    ->whereIn('user_id', $inactiveEmployerIds->all())
                    ->update(['status' => 'inactive']);
            }
        });

        return $count;
    }
}
