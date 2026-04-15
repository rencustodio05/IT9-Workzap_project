@extends('layouts.jobseeker')
@section('title', 'Saved Jobs')
@section('subtitle', 'Review jobs you bookmarked and revisit opportunities quickly.')
@section('content')

<div class="mb-4 text-right">
    <a href="{{ url('/applications/history') }}" class="js-brand-btn inline-flex items-center px-4 py-2 rounded text-sm font-medium">History</a>
</div>


<div class="js-card overflow-x-auto">
    <table class="min-w-full text-left whitespace-nowrap">
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
                    <a href="{{ route('jobseeker.jobs.show', $job->id) }}" class="px-3 py-1 rounded border text-sm">View</a>
                    <form method="POST" action="{{ route('jobseeker.saved.destroy', $job->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 rounded bg-red-600 text-white text-sm">Remove</button>
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
</div>
@endsection