@extends('layouts.applicant')

@section('title', 'Application Details')
@section('subtitle', 'Review your application, interview details, and employment status.')

@section('content')
@php
$backUrl = url()->previous();
if ($backUrl === url()->current()) {
$backUrl = route('applicant.applications.index');
}
$interview = $application->interview;
@endphp

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
                <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $application->job->title ?? 'Application Details' }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">{{ ucfirst($application->status) }}</span>
                    @if($application->status === 'hired')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        Hired {{ $application->hired_at ? '• ' . $application->hired_at->format('M d, Y') : '' }}
                    </span>
                    @elseif($application->status === 'fired')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                        Fired {{ $application->fired_at ? '• ' . $application->fired_at->format('M d, Y') : '' }}
                    </span>
                    @elseif($application->status === 'interview')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Interview Scheduled</span>
                    @endif
                </div>
            </div>

            <a href="{{ $backUrl }}" class="shrink-0 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Back</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Applied At</div>
                <div class="font-semibold text-gray-900">{{ optional($application->created_at)->format('F d, Y h:i A') }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Updated At</div>
                <div class="font-semibold text-gray-900">{{ optional($application->updated_at)->format('F d, Y h:i A') }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Job Location</div>
                <div class="font-semibold text-gray-900">{{ $application->job->location ?? 'Not specified' }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Salary Range</div>
                <div class="font-semibold text-gray-900">
                    {{ ($application->job->salary_min || $application->job->salary_max)
                        ? number_format($application->job->salary_min ?? 0, 2) . ' - ' . number_format($application->job->salary_max ?? 0, 2)
                        : 'Not specified' }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="border border-gray-200 bg-white shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Applicant Details</h3>
                </div>
                <div class="p-5 grid grid-cols-1 gap-y-5 text-sm">
                    <div>
                        <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Full Name</div>
                        <div class="text-sm font-medium text-gray-900">{{ trim(($application->applicant->first_name ?? '') . ' ' . ($application->applicant->last_name ?? '')) ?: 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Email Address</div>
                        <div class="text-sm font-medium text-gray-900">{{ $application->applicant->email ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Contact Number</div>
                        <div class="text-sm font-medium text-gray-900">{{ $application->applicant->contact_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Desired Job Title</div>
                        <div class="text-sm font-medium text-gray-900">{{ $application->applicant->desired_job_title ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 bg-white shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Employer / Interview Details</h3>
                </div>
                <div class="p-5 grid grid-cols-1 gap-y-5 text-sm">
                    <div>
                        <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Employer Name</div>
                        <div class="text-sm font-medium text-gray-900">{{ trim(($application->job->employer->first_name ?? '') . ' ' . ($application->job->employer->last_name ?? '')) ?: 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Employer Email</div>
                        <div class="text-sm font-medium text-gray-900">{{ $application->job->employer->email ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Interview Schedule</div>
                        <div class="text-sm font-medium text-gray-900">{{ $interview?->scheduled_at ? $interview->scheduled_at->format('M d, Y h:i A') : 'Not scheduled' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Interview Status</div>
                        <div class="text-sm font-medium text-gray-900">{{ $interview?->status ? ucfirst($interview->status) : 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border border-gray-200 bg-white shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Interview Notes</h3>
            </div>
            <div class="p-5 text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                {{ $interview?->notes ?? 'No interview notes available.' }}
            </div>
        </div>

        <div class="border border-gray-200 bg-white shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Job Description</h3>
            </div>
            <div class="p-5 text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                {{ $application->job->description ?? 'No job description available.' }}
            </div>
        </div>

        @if($application->status === 'hired' && $application->hired_at)
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            This application was hired on {{ $application->hired_at->format('F d, Y h:i A') }}.
        </div>
        @elseif($application->status === 'fired' && $application->fired_at)
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            This application was fired on {{ $application->fired_at->format('F d, Y h:i A') }}.
        </div>
        @elseif($application->status === 'interview' && $interview?->scheduled_at)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            Interview is scheduled for {{ $interview->scheduled_at->format('F d, Y h:i A') }}.
        </div>
        @endif

        <div class="pt-2 flex flex-wrap items-center gap-3 border-t border-gray-100">
            <a href="{{ route('applicant.jobs.show', $application->job_id) }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50 transition">
                View Job Details
            </a>
            <a href="{{ route('applicant.applications.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50 transition">
                Back to Applications
            </a>
        </div>
    </div>
</div>
@endsection