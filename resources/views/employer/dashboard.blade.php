@extends('layouts.employer')

@section('title', 'Employer Dashboard')
@section('subtitle', 'Manage your postings, applicants, and interviews.')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Employer Overview</h1>
            <p class="text-gray-500 mt-1">Manage your job postings and applicants</p>
        </div>
        <a href="{{ route('jobs.create') }}"
            class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold transition">
            + Post a Job
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
            <div class="text-gray-500 mb-1">My Job Postings</div>
            <div class="text-2xl font-extrabold text-gray-900">{{ $totalJobs ?? 0 }}</div>
            <div class="text-green-600 font-semibold">{{ $activeJobs ?? 0 }} active</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
            <div class="text-gray-500 mb-1">Total Applicants</div>
            <div class="text-2xl font-extrabold text-gray-900">{{ $totalApplicants ?? 0 }}</div>
            <div class="text-blue-600 font-semibold">+{{ $applicantsToday ?? 0 }} today</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
            <div class="text-gray-500 mb-1">Interviews Today</div>
            <div class="text-2xl font-extrabold text-gray-900">{{ $interviewsToday ?? 0 }}</div>
            <div class="text-yellow-600 font-semibold">Scheduled today</div>
        </div>
    </div>

    <!-- Main Dashboard Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Recent Applicants -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col h-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Applicants</h2>
                <a href="{{ route('applications.index') }}" class="text-blue-600 hover:underline font-medium">View all</a>
            </div>
            <div class="space-y-4">
                @forelse(($recentApplicants ?? collect()) as $application)
                <div class="flex justify-between items-center p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">{{ $application->jobseeker->name ?? 'N/A' }}</div>
                        <div class="text-gray-500 text-sm">{{ $application->job->title ?? 'N/A' }}</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800 font-semibold">{{ ucfirst($application->status) }}</span>
                </div>
                @empty
                <div class="text-sm text-gray-500">No recent applicants yet.</div>
                @endforelse
            </div>
        </div>

        <!-- My Job Postings -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col h-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">My Job Postings</h2>
                <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:underline font-medium">View all</a>
            </div>
            <div class="space-y-4">
                @forelse(($latestJobs ?? collect()) as $job)
                <div class="flex justify-between items-center p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">{{ $job->title }}</div>
                        <div class="text-gray-500 text-sm">{{ $job->applications()->count() }} applicants</div>
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