@extends('layouts.jobseeker')

@section('title', 'Jobseeker Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Jobseeker Dashboard</h1>
            <p class="text-gray-500 mt-1">Find and manage your job opportunities</p>
        </div>
        <a href="{{ route('jobseeker.jobs.index') }}"
            class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">
            Browse Jobs
        </a>
    </div>
    <!-- Main Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Recommended Jobs -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col h-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recommended Jobs</h2>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Warehouse Staff</div>
                        <div class="text-gray-500 text-sm">Acme Corp · Davao City</div>
                    </div>
                    <a href="{{ route('jobseeker.jobs.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-1 rounded transition">Apply Now</a>
                </div>
                <div class="flex justify-between items-center p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Delivery Rider</div>
                        <div class="text-gray-500 text-sm">FastExpress · Davao City</div>
                    </div>
                    <a href="{{ route('jobseeker.jobs.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-1 rounded transition">Apply Now</a>
                </div>
            </div>
        </div>
        <!-- My Applications -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col h-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">My Applications</h2>
                <a href="{{ route('jobseeker.applications.index') }}" class="text-blue-600 hover:underline font-medium">View All</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Sales Associate</div>
                        <div class="text-gray-500 text-sm">Applied 2 days ago</div>
                    </div>
                    <span class="bg-blue-100 text-blue-800 font-semibold px-3 py-1 rounded-full text-xs">Interview</span>
                </div>
                <div class="flex items-center justify-between p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Warehouse Staff</div>
                        <div class="text-gray-500 text-sm">Applied 5 days ago</div>
                    </div>
                    <span class="bg-green-100 text-green-800 font-semibold px-3 py-1 rounded-full text-xs">Pending</span>
                </div>
                <div class="flex items-center justify-between p-3 border rounded">
                    <div>
                        <div class="font-bold text-gray-800">Cashier</div>
                        <div class="text-gray-500 text-sm">Applied 1 week ago</div>
                    </div>
                    <span class="bg-red-100 text-red-800 font-semibold px-3 py-1 rounded-full text-xs">Rejected</span>
                </div>
            </div>
        </div>
        <!-- Saved Jobs -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col h-full md:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Saved Jobs</h2>
                <a href="{{ route('jobseeker.saved.index') }}" class="text-blue-600 hover:underline font-medium">View All</a>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 flex flex-col justify-between border rounded p-3 mb-3 md:mb-0">
                    <div>
                        <div class="font-bold text-gray-800">Customer Service Rep</div>
                        <div class="text-gray-500 text-sm">Acme Support</div>
                    </div>
                </div>
                <div class="flex-1 flex flex-col justify-between border rounded p-3">
                    <div>
                        <div class="font-bold text-gray-800">Data Encoder</div>
                        <div class="text-gray-500 text-sm">MegaData Inc.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection