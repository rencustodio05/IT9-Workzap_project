@extends('layouts.employer')

@section('title', 'Employer Dashboard')
@section('subtitle', 'Manage your postings, applicants, and interviews.')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex justify-end">
        <a href="{{ route('jobs.create') }}"
            class="inline-flex items-center px-4 py-2 admin-button-primary text-white rounded-lg hover:brightness-95 text-sm font-semibold transition">
            + Post a Job
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="admin-surface rounded-xl p-4 sm:p-5 flex flex-col">
            <div class="text-sm text-gray-500 mb-2">My Job Postings</div>
            <div class="ml-auto text-right">
                <div class="text-xl sm:text-2xl font-black text-gray-900 leading-tight">{{ $totalJobs ?? 0 }}</div>
                <div class="text-xs sm:text-sm text-green-600 font-semibold">{{ $activeJobs ?? 0 }} active</div>
            </div>
        </div>
        <div class="admin-surface rounded-xl p-4 sm:p-5 flex flex-col">
            <div class="text-sm text-gray-500 mb-2">Total Applicants</div>
            <div class="ml-auto text-right">
                <div class="text-xl sm:text-2xl font-black text-gray-900 leading-tight">{{ $totalApplicants ?? 0 }}</div>
                <div class="text-xs sm:text-sm text-blue-600 font-semibold">+{{ $applicantsToday ?? 0 }} today</div>
            </div>
        </div>
        <div class="admin-surface rounded-xl p-4 sm:p-5 flex flex-col">
            <div class="text-sm text-gray-500 mb-2">Interviews Today</div>
            <div class="ml-auto text-right">
                <div class="text-xl sm:text-2xl font-black text-gray-900 leading-tight">{{ $interviewsToday ?? 0 }}</div>
                <div class="text-xs sm:text-sm text-yellow-600 font-semibold">Scheduled today</div>
            </div>
        </div>
        <div class="admin-surface rounded-xl p-4 sm:p-5 flex flex-col">
            <div class="text-sm text-gray-500 mb-2">Hired Applicants</div>
            <div class="ml-auto text-right">
                <div class="text-xl sm:text-2xl font-black text-gray-900 leading-tight">
                    {{ $hiredApplicants ?? 0 }}
                </div>
                <div class="text-xs sm:text-sm text-green-600 font-semibold">
                    Successfully hired
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Recent Applicants -->
        <div class="admin-surface rounded-xl p-4 sm:p-5 flex flex-col h-full">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-base font-semibold text-gray-900">Recent Applicants</h2>
                <a href="{{ route('applications.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View all</a>
            </div>
            <div class="space-y-3">
                @forelse(($recentApplicants ?? collect()) as $application)
                <div class="flex justify-between items-center p-2.5 border rounded-lg">
                    <div>
                        <div class="font-semibold text-sm text-gray-800">{{ $application->jobseeker->name ?? 'N/A' }}</div>
                        <div class="text-gray-500 text-xs sm:text-sm">{{ $application->job->title ?? 'N/A' }}</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800 font-semibold">{{ ucfirst($application->status) }}</span>
                </div>
                @empty
                <div class="text-sm text-gray-500">No recent applicants yet.</div>
                @endforelse
            </div>
        </div>

        <!-- My Job Postings -->
        <div class="admin-surface rounded-xl p-4 sm:p-5 flex flex-col h-full">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-base font-semibold text-gray-900">My Job Postings</h2>
                <a href="{{ route('jobs.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View all</a>
            </div>
            <div class="space-y-3">
                @forelse(($latestJobs ?? collect()) as $job)
                <div class="flex justify-between items-center p-2.5 border rounded-lg">
                    <div>
                        <div class="font-semibold text-sm text-gray-800">{{ $job->title }}</div>
                        <div class="text-gray-500 text-xs sm:text-sm">{{ $job->applications()->count() }} applicants</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">{{ ucfirst($job->status) }}</span>
                </div>
                @empty
                <div class="text-sm text-gray-500">No jobs posted yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection