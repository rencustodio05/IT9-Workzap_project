@extends('layouts.employer')

@section('title', 'Subscription')
@section('subtitle', 'Select a plan and wait for admin approval to unlock employer access.')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @if($activeSubscription)
    <div class="admin-surface rounded-xl p-5 border border-emerald-200 bg-emerald-50">
        <div class="text-sm text-emerald-800 font-semibold">Active Subscription</div>
        <div class="mt-1 text-sm text-emerald-700">
            Plan: {{ ucfirst($activeSubscription->plan_type) }}
            | Valid: {{ optional($activeSubscription->start_date)->format('M d, Y') }} to {{ optional($activeSubscription->end_date)->format('M d, Y') }}
        </div>
    </div>
    @elseif($pendingSubscription || $pendingPayment)
    <div class="admin-surface rounded-xl p-5 border border-amber-200 bg-amber-50">
        <div class="text-sm text-amber-800 font-semibold">Pending Approval</div>
        <div class="mt-1 text-sm text-amber-700">
            Your {{ ucfirst($pendingSubscription->plan_type ?? $pendingPayment->subscription_plan) }} plan request is waiting for admin approval.
        </div>
    </div>
    @elseif($latestSubscription && $latestSubscription->status === 'expired')
    <div class="admin-surface rounded-xl p-5 border border-red-200 bg-red-50">
        <div class="text-sm text-red-800 font-semibold">Subscription Expired</div>
        <div class="mt-1 text-sm text-red-700">
            Renew now to reactivate job posting and applicant access.
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($plans as $planKey => $plan)
        <div class="admin-surface rounded-xl p-5 border border-slate-200">
            <div class="text-xs uppercase tracking-wide text-slate-500">{{ ucfirst($planKey) }} Plan</div>
            <div class="mt-2 text-2xl font-black text-slate-900">{{ $plan['price'] }}</div>
            <div class="mt-1 text-sm text-slate-600">{{ $plan['months'] }} {{ $plan['months'] > 1 ? 'Months' : 'Month' }} full access</div>

            <ul class="mt-4 text-sm text-slate-700 space-y-1">
                <li>Unlimited job postings while active</li>
                <li>Can receive applicants</li>
                <li>Jobs remain visible to applicants</li>
            </ul>

            <form class="mt-5">
                <button type="button"
                    class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg admin-button-primary text-white font-semibold disabled:opacity-60 disabled:cursor-not-allowed"
                    data-subscription-open-modal="true"
                    data-selected-plan="{{ $planKey }}"
                    @disabled($activeSubscription || $pendingSubscription || $pendingPayment || $canPostJobs)>
                    @if($activeSubscription || $canPostJobs)
                    Already Active
                    @elseif($pendingSubscription || $pendingPayment)
                    Request Pending
                    @else
                    Subscribe
                    @endif
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>

@if(!$activeSubscription && !$canPostJobs)
@include('employer.subscription.partials.payment-modal')
@endif
@endsection