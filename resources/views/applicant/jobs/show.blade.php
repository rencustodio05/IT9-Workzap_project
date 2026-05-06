@extends('layouts.applicant')

@section('title', 'Job Details')
@section('subtitle', 'Review the job details before applying.')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">
    @if(session('success'))
    <div class="p-3 rounded-lg border border-green-200 bg-green-50 text-green-800 text-sm">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="p-3 rounded-lg border border-red-200 bg-red-50 text-red-800 text-sm">{{ session('error') }}</div>
    @endif

    <div class="admin-surface rounded-xl admin-fade-up p-6 space-y-6">
        <div class="flex items-start justify-between gap-4 border-b border-gray-200 pb-4">
            <div class="min-w-0">
                <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $job->title }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $job->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($job->status) }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ ucfirst($job->type ?? 'full-time') }}</span>
                </div>
            </div>
        </div>

        @if($alreadyApplied)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            You already have an active application for this job.
        </div>
        @endif

        @if($applyBlockedByRule)
        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            {{ $applyRestrictionMessage }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Location</div>
                <div class="font-semibold text-gray-900">{{ $job->location ?? 'Not specified' }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Salary Range</div>
                <div class="font-semibold text-gray-900">
                    {{ ($job->salary_min || $job->salary_max)
                        ? number_format($job->salary_min ?? 0, 2) . ' - ' . number_format($job->salary_max ?? 0, 2)
                        : 'Not specified' }}
                </div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Posted By</div>
                <div class="font-semibold text-gray-900">
                    {{ trim(($job->employer->first_name ?? '') . ' ' . ($job->employer->last_name ?? '')) ?: 'Employer' }}
                </div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Posted Date</div>
                <div class="font-semibold text-gray-900">{{ optional($job->created_at)->format('F d, Y') }}</div>
            </div>
        </div>

        <div>
            <div class="text-xs uppercase tracking-wide text-gray-500 mb-2">Job Description</div>
            <div class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">{{ $job->description }}</div>
        </div>

        <div class="pt-2 flex flex-wrap items-center gap-3 border-t border-gray-100">
            @if(!($alreadyApplied ?? false) && !($applyBlockedByRule ?? false))
            <form action="{{ route('applicant.apply', $job->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-lg admin-button-primary text-white font-semibold hover:brightness-95 transition">
                    Apply Now
                </button>
            </form>
            @endif

            @if($alreadyApplied)
            <a href="{{ route('applicant.applications.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50 transition">
                View Applications
            </a>
            @endif

            <a href="{{ route('applicant.jobs.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50 transition">
                Browse More Jobs
            </a>
        </div>
    </div>
</div>
@endsection