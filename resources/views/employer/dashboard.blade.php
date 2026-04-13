@extends('layouts.employer')

@section('title', 'Employer Dashboard')

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
            <div class="text-2xl font-extrabold text-gray-900">6</div>
            <div class="text-green-600 font-semibold">4 active</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
            <div class="text-gray-500 mb-1">Total Applicants</div>
            <div class="text-2xl font-extrabold text-gray-900">34</div>
            <div class="text-blue-600 font-semibold">+5 today</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
            <div class="text-gray-500 mb-1">Interviews Today</div>
            <div class="text-2xl font-extrabold text-gray-900">3</div>
            <div class="text-yellow-600 font-semibold">1 pending</div>
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
                <div class="flex justify-between items-center p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Maria Reyes</div>
                        <div class="text-gray-500 text-sm">Sales Associate</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800 font-semibold">Interview</span>
                </div>
                <div class="flex justify-between items-center p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Ben Torres</div>
                        <div class="text-gray-500 text-sm">Warehouse Staff</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">Screening</span>
                </div>
            </div>
        </div>

        <!-- My Job Postings -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col h-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">My Job Postings</h2>
                <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:underline font-medium">View all</a>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Sales Associate</div>
                        <div class="text-gray-500 text-sm">14 applicants</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">Active</span>
                </div>
                <div class="flex justify-between items-center p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Cashier</div>
                        <div class="text-gray-500 text-sm">21 applicants</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-semibold">Closing soon</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection