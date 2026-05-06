@extends('layouts.applicant')

@section('title', 'Application Details')
@section('subtitle', 'Review your application and hired job history.')

@section('content')
@php
$backUrl = url()->previous();
if ($backUrl === url()->current()) {
$backUrl = route('applicant.applications.index');
}
@endphp

<div class="admin-surface rounded-xl admin-fade-up p-6">
    <div class="flex items-center justify-between gap-3 mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Current Jobs (Hired)</h2>
        <span class="text-sm font-medium text-gray-500">Total: {{ ($hiredApplications ?? collect())->count() }}</span>
    </div>

    @if(($hiredApplications ?? collect())->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($hiredApplications as $hiredApplication)
        <div class="admin-surface rounded-xl admin-fade-up p-5">
            <div class="space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Job Title</div>
                        <div class="text-base font-semibold text-gray-900 truncate">{{ $hiredApplication->job->title ?? 'N/A' }}</div>
                    </div>
                    <a href="{{ route('applicant.jobs.show', $hiredApplication->job_id) . '?from=applications&application_id=' . $hiredApplication->id }}" class="shrink-0 inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 border border-blue-200 rounded hover:bg-blue-50">View</a>
                </div>

                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Status</div>
                    <div class="text-sm font-medium text-green-700">{{ ucfirst($hiredApplication->status) }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Applied Date</div>
                    <div class="text-sm font-medium text-gray-900">{{ $hiredApplication->created_at->format('M d, Y h:i A') }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Employer Name</div>
                    <div class="text-sm font-medium text-gray-900">{{ $hiredApplication->job->employer->name ?? 'N/A' }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Employer Email</div>
                    <a href="mailto:{{ $hiredApplication->job->employer->email ?? '' }}" class="text-sm font-medium text-blue-600 hover:underline">
                        {{ $hiredApplication->job->employer->email ?? 'N/A' }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-sm text-gray-500">No hired applications found.</div>
    @endif

    <div class="mt-6 flex gap-2">
        <a href="{{ $backUrl }}" class="px-4 py-2 bg-gray-200 rounded">Back</a>
        <a href="{{ route('applicant.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Dashboard</a>
    </div>
</div>
@endsection