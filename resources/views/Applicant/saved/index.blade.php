@extends('layouts.applicant')
@section('title', 'Saved Jobs')
@section('subtitle', 'Review jobs you bookmarked and revisit opportunities quickly.')

@section('content')
<div class="mb-4 text-right">
    <a href="{{ route('applicant.applications.history') }}" class="admin-button-primary text-white inline-flex items-center px-4 py-2 rounded text-sm font-medium">History</a>
</div>

<div class="admin-surface rounded-xl admin-fade-up overflow-x-auto">
    <table class="admin-table min-w-full text-sm text-left whitespace-nowrap">
        <thead class="bg-gray-50 border-b text-sm text-gray-600">
            <tr>
                <th class="py-3 px-4">Job</th>
                <th class="py-3 px-4">Company</th>
                <th class="py-3 px-4">Location</th>
                <th class="py-3 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
            <tr class="border-b">
                <td class="py-3 px-4 font-medium">{{ $job->title }}</td>
                <td class="py-3 px-4 text-sm text-gray-600">{{ $job->employer->name ?? 'Employer' }}</td>
                <td class="py-3 px-4 text-sm text-gray-600">{{ $job->location }}</td>
                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('applicant.jobs.show', $job->id) }}" title="View" aria-label="View" class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 hover:bg-blue-50 transition">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                            <circle cx="12" cy="12" r="2.75" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('applicant.saved.destroy', $job->id) }}">
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
                <td colspan="4" class="py-6 px-4 text-center text-gray-500">No saved jobs yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $jobs->links() }}
</div>
@endsection