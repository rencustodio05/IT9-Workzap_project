<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\EmployerSubscription;
use App\Models\SubscriptionPayment;
use App\Services\EmployerSubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(private readonly EmployerSubscriptionService $subscriptionService) {}

    public function index(): View
    {
        $employerId = (int) Auth::id();

        $latestSubscription = EmployerSubscription::query()
            ->where('employer_id', $employerId)
            ->latest()
            ->first();

        $activeSubscription = EmployerSubscription::query()
            ->where('employer_id', $employerId)
            ->where('status', EmployerSubscription::STATUS_ACTIVE)
            ->whereNotIn('status', [
                EmployerSubscription::STATUS_CANCELLED,
                EmployerSubscription::STATUS_EXPIRED
            ])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->first();

        $pendingSubscription = EmployerSubscription::query()
            ->where('employer_id', $employerId)
            ->where('status', EmployerSubscription::STATUS_PENDING)
            ->latest()
            ->first();

        $pendingPayment = SubscriptionPayment::query()
            ->where('employer_id', $employerId)
            ->where('status', SubscriptionPayment::STATUS_PENDING)
            ->latest()
            ->first();

        $canPostJobs = $this->subscriptionService->hasActiveSubscription($employerId)
            || $this->subscriptionService->hasActiveSubscription($employerId);

        return view('employer.subscription.index', [
            'plans' => $this->subscriptionService->plans(),
            'latestSubscription' => $latestSubscription,
            'activeSubscription' => $activeSubscription,
            'pendingSubscription' => $pendingSubscription,
            'pendingPayment' => $pendingPayment,
            'canPostJobs' => $canPostJobs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan_type' => ['required', 'in:monthly,quarterly,yearly'],
        ]);

        $employerId = (int) Auth::id();

        if ($this->subscriptionService->hasActiveSubscription($employerId)) {
            return back()->with('error', 'You already have an active subscription.');
        }

        $hasPending = EmployerSubscription::query()
            ->where('employer_id', $employerId)
            ->where('status', EmployerSubscription::STATUS_PENDING)
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'You already have a pending subscription request.');
        }

        $plan = $this->subscriptionService->resolvePlan($validated['plan_type']);

        EmployerSubscription::create([
            'employer_id' => $employerId,
            'plan_type' => $validated['plan_type'],
            'price' => $plan['amount'],
            'status' => EmployerSubscription::STATUS_PENDING,
            'payment_status' => 'pending',
        ]);

        return redirect()
            ->route('employer.subscription.index')
            ->with('success', 'Subscription request submitted. Waiting for admin approval.');
    }
}
