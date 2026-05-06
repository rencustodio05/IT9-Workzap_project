@extends('layouts.applicant')

@section('title', 'My Applications')
@section('subtitle', 'Track your submitted applications and current status.')

@section('content')
<form method="GET" action="{{ route('applicant.applications.index') }}" class="admin-surface rounded-xl admin-fade-up p-4 mb-6 grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
    <div class="md:col-span-7">
        <label class="block text-xs uppercase tracking-wide text-gray-500 mb-2">Search</label>
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by job title"
            class="w-full rounded-lg border px-3 py-2">
    </div>

    <div class="md:col-span-3">
        <label class="block text-xs uppercase tracking-wide text-gray-500 mb-2">Status</label>
        <select name="status" class="w-full rounded-lg border px-3 py-2">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Interview</option>
            <option value="hired" {{ request('status') === 'hired' ? 'selected' : '' }}>Hired</option>
            <option value="fired" {{ request('status') === 'fired' ? 'selected' : '' }}>Fired</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>

    <div class="md:col-span-2 flex gap-2">
        <button type="submit" class="w-full admin-button-primary text-white px-4 py-2 rounded-lg font-medium">
            Filter
        </button>
        <a href="{{ route('applicant.applications.index') }}" class="w-full text-center bg-gray-200 px-4 py-2 rounded-lg font-medium text-gray-700">
            Reset
        </a>
    </div>
</form>

<div class="admin-surface rounded-xl admin-fade-up overflow-x-auto">
    <table class="admin-table min-w-full text-sm text-left whitespace-nowrap">
        <thead class="bg-gray-50 border-b text-sm text-gray-600">
            <tr>
                <th class="py-3 px-4">Job</th>
                <th class="py-3 px-4">Applied At</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Interview</th>
                <th class="py-3 px-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
            <tr class="border-b">
                <td class="py-3 px-4 font-medium">{{ $application->job->title ?? 'N/A' }}</td>
                <td class="py-3 px-4 text-sm text-gray-600">{{ optional($application->created_at)->format('M d, Y h:i A') }}</td>
                <td class="py-3 px-4">
                    @php
                    $statusColor = match($application->status) {
                    'hired' => 'bg-green-100 text-green-800',
                    'interview' => 'bg-blue-100 text-blue-800',
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'rejected', 'fired', 'cancelled' => 'bg-red-100 text-red-800',
                    default => 'bg-gray-100 text-gray-800'
                    };
                    @endphp
                    <span class="px-2 py-1 text-xs rounded {{ $statusColor }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-600">
                    {{ $application->interview?->scheduled_at ? $application->interview->scheduled_at->format('M d, Y h:i A') : 'Not scheduled' }}
                </td>
                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('applicant.applications.show', $application->id) }}" class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 hover:bg-blue-50 transition" title="View" aria-label="View">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                            <circle cx="12" cy="12" r="2.75" />
                        </svg>
                    </a>
                    @if(in_array($application->status, ['pending', 'interview']))
                    <form method="POST" action="{{ route('applicant.applications.update', $application->id) }}">
                        @csrf
                        @method('PUT')
                        <button class="px-3 py-1 bg-red-600 text-white rounded text-sm">Cancel</button>
                    </form>
                    @elseif(in_array($application->status, ['hired', 'fired']))
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded bg-gray-100 text-gray-600">
                        Employment record
                    </span>
                    @endif
                    <form method="POST" action="{{ route('applicant.applications.destroy', $application->id) }}" onsubmit="return confirm('Delete this application permanently?');">
                        @csrf
                        @method('DELETE')
                        <button title="Delete" aria-label="Delete" class="inline-flex items-center justify-center p-2 rounded-md text-red-600 hover:bg-red-50 transition">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5h15" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 7.5V6a1.5 1.5 0 0 1 1.5-1.5h1.5A1.5 1.5 0 0 1 14.25 6v1.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l.75 11.25a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5l.75-11.25" />
                            </svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-6 px-4 text-center text-gray-500">No applications found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $applications->links() }}
</div>
@endsection