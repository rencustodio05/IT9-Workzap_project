@extends('layouts.jobseeker')

@section('title', 'Application History')
@section('subtitle', 'Review your previous employment outcomes and updates.')
@section('content')


<div class="admin-surface rounded-xl admin-fade-up p-6 mb-6">
    <h2 class="text-lg font-semibold mb-4">Employment History</h2>
    <div class="space-y-3">
        @forelse($employmentHistory as $application)
        <div class="rounded-lg border p-4 flex justify-between items-center">
            <div>
                <div class="font-semibold text-gray-900">{{ $application->job->title ?? 'N/A' }}</div>
                <div class="text-sm text-gray-600">{{ $application->job->employer->name ?? 'Employer' }}</div>
                <div class="text-sm text-gray-500">{{ $application->updated_at->format('M d, Y h:i A') }}</div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $application->status === 'hired' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($application->status) }}
            </span>
        </div>
        @empty
        <div class="text-sm text-gray-500">No employment history found.</div>
        @endforelse
    </div>
</div>
</div>
@endsection
