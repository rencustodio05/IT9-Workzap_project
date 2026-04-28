@extends('layouts.jobseeker')

@section('title', 'Job Details')
@section('subtitle', 'Review information and submit your application.')

@section('content')
@php
$job = $job ?? ($application->job ?? null);
$backUrl = url()->previous();
if ($backUrl === url()->current()) {
$backUrl = 'javascript:history.back()';
}
@endphp

<div class="admin-surface rounded-xl admin-fade-up p-6">
    <div class="flex items-start justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $job->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $job->employer->name ?? 'Employer' }} {{ $job->location }}
            </p>
        </div>
        <a href="{{ $backUrl }}" class="px-4 py-2 rounded border text-gray-700 hover:bg-gray-100">
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-50 rounded p-4">
            <div class="text-xs text-gray-500">Salary</div>
            <div class="font-semibold text-gray-900">
                {{ number_format($job->salary_min ?? 0) }} - {{ number_format($job->salary_max ?? 0) }}
            </div>
        </div>
        <div class="bg-gray-50 rounded p-4">
            <div class="text-xs text-gray-500">Type</div>
            <div class="font-semibold text-gray-900">{{ $job->type ? ucfirst(str_replace(',', ', ', $job->type)) : 'Not specified' }}</div>
        </div>
        <div class="bg-gray-50 rounded p-4">
            <div class="text-xs text-gray-500">Status</div>
            <div class="font-semibold text-gray-900">{{ ucfirst($job->status) }}</div>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="font-semibold text-gray-900 mb-2">Job Description</h2>
        <p class="text-gray-700 whitespace-pre-line">{{ $job->description }}</p>
    </div>

    <div>
        @if(isset($application))
        @if(in_array($application->status, ['cancelled', 'rejected']))
        <form method="POST" action="{{ route('jobseeker.apply', $job->id) }}">
            @csrf
            <button class="px-4 py-2 admin-button-primary text-white rounded">Apply Again</button>
        </form>
        @elseif($application->status === 'hired')
        <button disabled class="px-4 py-2 rounded bg-green-100 text-green-800">Currently Hired</button>
        @endif
        @else
        @if($alreadyApplied)
        <button disabled class="px-4 py-2 rounded bg-gray-300 text-gray-600">Already Applied</button>
        @elseif($applyBlockedByRule ?? false)
        <button disabled class="px-4 py-2 rounded bg-gray-300 text-gray-600 cursor-not-allowed">Apply for this job</button>
        <p class="text-sm text-red-600 mt-2">{{ $applyRestrictionMessage }}</p>
        @else
        <div class="flex flex-col sm:flex-row items-start gap-3">
            <a href="{{ route('jobseeker.profile') }}"
                class="inline-flex items-center justify-center px-4 py-2 rounded border border-slate-300 bg-white text-slate-900 hover:bg-slate-100 transition">
                View Your Profile
            </a>
            <form action="{{ route('jobseeker.apply', $job->id) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button class="w-full sm:w-auto px-4 py-2 rounded admin-button-primary text-white hover:brightness-95">Apply for this job</button>
            </form>
            @if(isset($application) && request()->input('from') === 'applications' && $application->status === 'interview' && $application->interview)
            <a href="#interview-details"
                class="inline-flex items-center justify-center px-4 py-2 rounded border border-slate-300 bg-white text-slate-900 hover:bg-slate-100 transition">
                Interview Details
            </a>
            @endif
        </div>
        @endif
        @endif

        @if(isset($application) && request()->input('from') === 'applications' && $application->status === 'interview' && $application->interview)
        <div id="interview-details" class="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Interview Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs uppercase tracking-[0.12em] text-gray-500 mb-1">Interview Date</div>
                    <div class="text-sm font-medium text-gray-900">{{ $application->interview->interview_date ? \Illuminate\Support\Carbon::parse($application->interview->interview_date)->format('M d, Y') : 'TBA' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.12em] text-gray-500 mb-1">Interview Time</div>
                    <div class="text-sm font-medium text-gray-900">{{ $application->interview->interview_time ? \Illuminate\Support\Carbon::parse($application->interview->interview_time)->format('g:i A') : 'TBA' }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-xs uppercase tracking-[0.12em] text-gray-500 mb-1">Interview Status</div>
                    <div class="inline-flex items-center px-3 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">{{ ucfirst($application->interview->status ?? 'scheduled') }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-xs uppercase tracking-[0.12em] text-gray-500 mb-1">Notes</div>
                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $application->interview->notes ?? 'No interview notes available.' }}</div>
                </div>
            </div>
        </div>
        @endif

        @if(isset($application) && in_array($application->status, ['pending', 'interview']))
        <div class="mt-6">
            <form method="POST" action="{{ route('jobseeker.applications.update', $application->id) }}">
                @csrf
                @method('PUT')
                <button class="px-4 py-2 bg-red-600 text-white rounded">Cancel Application</button>
            </form>
        </div>
        @endif
    </div>
</div>
</div>
@endsection