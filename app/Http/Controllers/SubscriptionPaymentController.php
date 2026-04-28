<?php

namespace App\Http\Controllers;

use App\Models\EmployerSubscription;
use App\Models\SubscriptionPayment;
use App\Models\User;
use App\Services\EmployerSubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SubscriptionPaymentController extends Controller
{
    public function __construct(private readonly EmployerSubscriptionService $subscriptionService) {}

    /**
     * @var array<string, float>
     */
    private array $planAmounts = [
        'monthly' => 20.00,
        'quarterly' => 50.00,
        'yearly' => 100.00,
    ];

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user || $user->role !== 'employer') {
            abort(403, 'Unauthorized');
        }

        if ((bool) $user->subscription_active) {
            return back()->with('error', 'Your subscription is already active.');
        }

        $hasPending = SubscriptionPayment::query()
            ->where('employer_id', (int) $user->id)
            ->where('status', SubscriptionPayment::STATUS_PENDING)
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'You already have a pending subscription payment.');
        }

        $validator = Validator::make($request->all(), [
            'subscription_plan' => ['required', 'in:monthly,quarterly,yearly'],
            'card_number' => ['required', 'regex:/^[0-9 ]+$/'],
            'expiry_date' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => ['required', 'digits:3'],
        ]);

        $validator->after(function ($validator) use ($request): void {
            $cardNumber = preg_replace('/\s+/', '', (string) $request->input('card_number'));

            if (!preg_match('/^\d{16}$/', (string) $cardNumber)) {
                $validator->errors()->add('card_number', 'The card number must be exactly 16 digits.');
            }

            $expiryDate = (string) $request->input('expiry_date');

            if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiryDate)) {
                return;
            }

            [$month, $year] = explode('/', $expiryDate);

            $expiryEndOfMonth = Carbon::createFromDate(
                (int) ('20' . $year),
                (int) $month,
                1,
                config('app.timezone')
            )->endOfMonth();

            if ($expiryEndOfMonth->lt(Carbon::now(config('app.timezone'))->startOfDay())) {
                $validator->errors()->add('expiry_date', 'The expiry date has already passed.');
            }
        });

        $validated = $validator->validate();

        SubscriptionPayment::create([
            'employer_id' => (int) $user->id,
            'subscription_plan' => $validated['subscription_plan'],
            'amount' => $this->planAmounts[$validated['subscription_plan']],
            'card_number' => preg_replace('/\s+/', '', (string) $validated['card_number']),
            'expiry_date' => $validated['expiry_date'],
            'cvv' => $validated['cvv'],
            'status' => SubscriptionPayment::STATUS_PENDING,
        ]);

        return back()->with('success', 'Payment details submitted. Please wait for admin review.');
    }

    public function index(Request $request): View
    {
        $status = (string) $request->query('status', '');

        $payments = SubscriptionPayment::query()
            ->with('employer:id,first_name,last_name,email')
            ->when(in_array($status, [
                SubscriptionPayment::STATUS_PENDING,
                SubscriptionPayment::STATUS_APPROVED,
                SubscriptionPayment::STATUS_REJECTED,
            ], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.subscription-payments.index', compact('payments', 'status'));
    }

    public function updateStatus(Request $request, SubscriptionPayment $subscriptionPayment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        if ($subscriptionPayment->status !== SubscriptionPayment::STATUS_PENDING) {
            return back()->with('error', 'Only pending payments can be updated.');
        }

        DB::transaction(function () use ($subscriptionPayment, $validated): void {
            $subscriptionPayment->update([
                'status' => $validated['status'],
            ]);

            if ($validated['status'] === SubscriptionPayment::STATUS_APPROVED) {
                $plan = $this->subscriptionService->resolvePlan($subscriptionPayment->subscription_plan);
                $start = Carbon::today(config('app.timezone'));
                $end = (clone $start)->addMonthsNoOverflow($plan['months'])->subDay();

                EmployerSubscription::query()
                    ->where('employer_id', $subscriptionPayment->employer_id)
                    ->where('status', EmployerSubscription::STATUS_ACTIVE)
                    ->update(['status' => EmployerSubscription::STATUS_EXPIRED]);

                $pendingSubscription = EmployerSubscription::query()
                    ->where('employer_id', $subscriptionPayment->employer_id)
                    ->where('status', EmployerSubscription::STATUS_PENDING)
                    ->latest()
                    ->first();

                if ($pendingSubscription) {
                    $pendingSubscription->update([
                        'plan_type' => $subscriptionPayment->subscription_plan,
                        'price' => (float) $subscriptionPayment->amount,
                        'status' => EmployerSubscription::STATUS_ACTIVE,
                        'start_date' => $start->toDateString(),
                        'end_date' => $end->toDateString(),
                        'payment_status' => 'paid',
                        'admin_approved_by' => (int) Auth::id(),
                    ]);
                } else {
                    EmployerSubscription::create([
                        'employer_id' => $subscriptionPayment->employer_id,
                        'plan_type' => $subscriptionPayment->subscription_plan,
                        'price' => (float) $subscriptionPayment->amount,
                        'status' => EmployerSubscription::STATUS_ACTIVE,
                        'start_date' => $start->toDateString(),
                        'end_date' => $end->toDateString(),
                        'payment_status' => 'paid',
                        'admin_approved_by' => (int) Auth::id(),
                    ]);
                }

                User::query()->whereKey($subscriptionPayment->employer_id)->update([
                    'subscription_active' => true,
                ]);

                return;
            }

            $hasApproved = SubscriptionPayment::query()
                ->where('employer_id', $subscriptionPayment->employer_id)
                ->where('status', SubscriptionPayment::STATUS_APPROVED)
                ->exists();

            $hasActiveSubscription = EmployerSubscription::query()
                ->where('employer_id', $subscriptionPayment->employer_id)
                ->where('status', EmployerSubscription::STATUS_ACTIVE)
                ->whereDate('start_date', '<=', Carbon::today(config('app.timezone'))->toDateString())
                ->whereDate('end_date', '>=', Carbon::today(config('app.timezone'))->toDateString())
                ->exists();

            if (!$hasApproved && !$hasActiveSubscription) {
                User::query()->whereKey($subscriptionPayment->employer_id)->update([
                    'subscription_active' => false,
                ]);
            }
        });

        return back()->with('success', 'Subscription payment status updated successfully.');
    }

    public function planAmount(string $plan): float
    {
        return $this->planAmounts[$plan] ?? 0;
    }
}
