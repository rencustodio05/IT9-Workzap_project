@extends('layouts.applicant')

@section('title', 'Applicant Dashboard')
@section('subtitle', 'Track your applications, interviews, and saved opportunities.')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="admin-surface rounded-xl p-5 flex flex-col">
            <div class="text-sm text-gray-500 mb-2">Pending Applications</div>
            <div class="ml-auto text-right">
                <div class="text-2xl font-black text-gray-900 leading-tight">{{ $totalAppliedJobs ?? 0 }}</div>
                <div class="text-xs text-blue-600 font-semibold">Currently submitted</div>
            </div>
        </div>

        <div class="admin-surface rounded-xl p-5 flex flex-col">
            <div class="text-sm text-gray-500 mb-2">In Interview</div>
            <div class="ml-auto text-right">
                <div class="text-2xl font-black text-gray-900 leading-tight">{{ $progressApplications ?? 0 }}</div>
                <div class="text-xs text-yellow-600 font-semibold">Awaiting feedback</div>
            </div>
        </div>

        <div class="admin-surface rounded-xl p-5 flex flex-col">
            <div class="text-sm text-gray-500 mb-2">Hired Jobs</div>
            <div class="ml-auto text-right">
                <div class="text-2xl font-black text-gray-900 leading-tight">{{ $hiredJobsCount ?? 0 }}</div>
                <div class="text-xs text-green-600 font-semibold">Successful outcomes</div>
            </div>
        </div>

        <div class="admin-surface rounded-xl p-5 flex flex-col">
            <div class="text-sm text-gray-500 mb-2">Upcoming Interview</div>
            <div class="ml-auto text-right">
                <div class="text-sm font-semibold text-gray-900 leading-tight">{{ $nearestInterviewSchedule ?? 'No upcoming interview' }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">{{ $upcomingInterviewCount ?? 0 }} scheduled</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="admin-surface rounded-xl p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recommended Jobs</h2>
                <a href="{{ route('applicant.jobs.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View all</a>
            </div>

            <div class="space-y-3">
                @forelse(($recommendedJobs ?? collect()) as $job)
                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 p-4">
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-800 truncate">{{ $job->title }}</div>
                        <div class="text-sm text-gray-500 truncate">{{ $job->employer->name ?? trim(($job->employer->first_name ?? '') . ' ' . ($job->employer->last_name ?? '')) ?: 'Employer' }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $job->location ?? 'No location' }}</div>
                    </div>
                    <a href="{{ route('applicant.jobs.show', $job->id) }}" class="admin-button-primary text-white px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap">View</a>
                </div>
                @empty
                <div class="text-sm text-gray-500">No recommended jobs yet.</div>
                @endforelse
            </div>
        </div>

        <div class="admin-surface rounded-xl p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Applications</h2>
                <a href="{{ route('applicant.applications.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View all</a>
            </div>

            <div class="space-y-3">
                @forelse(($recentApplications ?? collect()) as $application)
                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 p-4">
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-800 truncate">{{ $application->job->title ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500 truncate">Applied {{ optional($application->created_at)->diffForHumans() ?? 'recently' }}</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ ucfirst($application->status ?? 'pending') }}</span>
                </div>
                @empty
                <div class="text-sm text-gray-500">No applications yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="admin-surface rounded-xl p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Saved Jobs</h2>
                <a href="{{ route('applicant.saved.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View all</a>
            </div>

            <div class="space-y-3">
                @forelse(($savedJobs ?? collect()) as $job)
                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 p-4">
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-800 truncate">{{ $job->title }}</div>
                        <div class="text-sm text-gray-500 truncate">{{ $job->location ?? 'No location' }}</div>
                    </div>
                    <a href="{{ route('applicant.jobs.show', $job->id) }}" class="text-sm text-blue-600 hover:underline font-medium whitespace-nowrap">Open</a>
                </div>
                @empty
                <div class="text-sm text-gray-500">No saved jobs yet.</div>
                @endforelse
            </div>
        </div>

        <div class="admin-surface rounded-xl p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Profile Snapshot</h2>
                <a href="{{ route('applicant.profile') }}" class="text-sm text-blue-600 hover:underline font-medium">Open profile</a>
            </div>

            @php
            $latestJobTitle = $latestApplicationForDetails->job->title ?? 'N/A';
            @endphp

            <div class="space-y-3 text-sm text-gray-700">
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                    <span class="text-gray-500">Latest application</span>
                    <span class="font-medium text-gray-900 text-right">{{ $latestJobTitle }}</span>
                </div>
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                    <span class="text-gray-500">Current interview status</span>
                    <span class="font-medium text-gray-900 text-right">{{ $nearestInterviewSchedule ?? 'None' }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-gray-500">Quick actions</span>
                    <span class="font-medium text-gray-900 text-right">
                        <a href="{{ route('applicant.jobs.index') }}" class="text-blue-600 hover:underline">Browse jobs</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection