@extends('layouts.jobseeker')

@section('title', 'Applicant Dashboard')
@section('subtitle', 'Find and manage your job opportunities')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 items-stretch">
    <a href="{{ route('jobseeker.applications.index') }}" class="block h-full">
        <div class="admin-surface rounded-xl admin-fade-up h-full min-h-[112px] p-5 flex items-center justify-between gap-4 hover:-translate-y-0.5 transition">
            <div class="min-w-0">
                <div class="text-sm font-semibold text-gray-800">Jobs Applying</div>
                <div class="text-sm text-gray-500 mt-1">Progress: {{ $progressApplications ?? 0 }}</div>
            </div>
            <div class="text-right text-3xl font-extrabold leading-none text-gray-900">{{ $totalAppliedJobs ?? 0 }}</div>
        </div>
    </a>

    <a href="{{ route('jobseeker.applications.index') }}" class="block h-full">
        <div class="admin-surface rounded-xl admin-fade-up h-full min-h-[112px] p-5 flex items-center justify-between gap-4 hover:-translate-y-0.5 transition">
            <div class="min-w-0">
                <div class="text-sm font-semibold text-gray-800">Interview Schedule</div>
                <div class="text-sm text-gray-500 mt-1 truncate">{{ $nearestInterviewSchedule ?? 'No upcoming interviews' }}</div>
            </div>
            <div class="text-right text-3xl font-extrabold leading-none text-gray-900">{{ $upcomingInterviewCount ?? 0 }}</div>
        </div>
    </a>

    <a href="{{ $latestHiredApplication ? route('jobseeker.applications.show', $latestHiredApplication->id) : route('jobseeker.applications.index') }}" class="block h-full">
        <div class="admin-surface rounded-xl admin-fade-up h-full min-h-[112px] p-5 flex items-center justify-between gap-4 hover:-translate-y-0.5 transition">
            @php
            $hiredTitleList = (($recentApplications ?? collect())
            ->filter(fn($application) => ($application->status ?? null) === 'hired')
            ->pluck('job.title')
            ->filter()
            ->unique()
            ->take(3)
            ->values());
            $extraHiredCount = max(0, (int) ($hiredJobsCount ?? 0) - $hiredTitleList->count());
            @endphp

            <div class="min-w-0">
                <div class="text-sm font-semibold text-gray-800">Current Jobs</div>

                @if($hiredTitleList->isEmpty())
                <div class="text-sm text-gray-500 mt-1 truncate">No hired jobs yet</div>
                @else
                <div class="text-sm text-gray-500 mt-1 space-y-0.5">
                    @foreach($hiredTitleList as $title)
                    <div class="truncate">Hired: {{ $title }}</div>
                    @endforeach

                    @if($extraHiredCount > 0)
                    <div class="truncate">+{{ $extraHiredCount }} more</div>
                    @endif
                </div>
                @endif
            </div>

            <div class="text-right text-3xl font-extrabold leading-none text-gray-900">{{ $hiredJobsCount ?? 0 }}</div>
        </div>
    </a>
</div>
<!-- Main Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Recommended Jobs -->
    <div class="admin-surface rounded-xl admin-fade-up p-6 flex flex-col h-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Recommended Jobs</h2>
            <a href="{{ route('jobseeker.jobs.index') }}" class="text-blue-600 hover:underline font-medium">View All</a>
        </div>
        <div class="space-y-4">
            @forelse(($recommendedJobs ?? collect()) as $job)
            <div class="flex justify-between items-center p-3 border rounded">
                <div>
                    <div class="font-bold text-gray-800">{{ $job->title }}</div>
                    <div class="text-gray-500 text-sm">{{ $job->employer->name ?? 'Employer' }} {{ $job->location }}</div>
                </div>
                <a href="{{ route('jobseeker.jobs.show', $job->id) }}" class="admin-button-primary text-white font-semibold px-4 py-1 rounded transition">Apply Now</a>
            </div>
            @empty
            <div class="text-sm text-gray-500">No recommended jobs right now.</div>
            @endforelse
        </div>
    </div>
    <!-- My Applications -->
    <div class="admin-surface rounded-xl admin-fade-up p-6 flex flex-col h-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">My Applications</h2>
            <a href="{{ route('jobseeker.applications.index') }}" class="text-blue-600 hover:underline font-medium">View All</a>
        </div>
        <div class="space-y-4">
            @forelse(($recentApplications ?? collect()) as $application)
            <div class="flex items-center justify-between p-3 border rounded">
                <div>
                    <div class="font-bold text-gray-800">{{ $application->job->title ?? 'N/A' }}</div>
                    <div class="text-gray-500 text-sm">Applied {{ $application->created_at->diffForHumans() }}</div>
                </div>
                <span class="bg-blue-100 text-blue-800 font-semibold px-3 py-1 rounded-full text-xs">{{ ucfirst($application->status) }}</span>
            </div>
            @empty
            <div class="text-sm text-gray-500">No applications yet.</div>
            @endforelse
        </div>
    </div>
</div>
</div>
@endsection