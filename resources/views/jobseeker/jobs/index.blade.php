@extends('layouts.jobseeker')

@section('title', 'Browse Jobs')
@section('subtitle', 'Find jobs that match your skills and career goals.')

@section('content')

<!-- SEARCH -->
<form method="GET"
    action="{{ route('jobseeker.jobs.index') }}"
    class="admin-surface rounded-xl admin-fade-up p-4 mb-6 flex flex-col md:flex-row gap-3 relative">

    <!-- SEARCH INPUT -->
    <div class="relative w-full">

        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search job title..."
            class="rounded-lg border px-3 py-2 w-full">

    </div>

    <!-- LOCATION -->
    <input type="text"
        name="location"
        value="{{ request('location') }}"
        placeholder="Location"
        class="rounded-lg border px-3 py-2">

    <!-- TYPE -->
    <select name="type" class="rounded-lg border px-3 py-2">
        <option value="">Job Type</option>
        <option value="full-time" {{ request('type')=='full-time'?'selected':'' }}>Full Time</option>
        <option value="part-time" {{ request('type')=='part-time'?'selected':'' }}>Part Time</option>
    </select>

    <button class="admin-button-primary text-white px-4 py-2 rounded">
        Search
    </button>

    <a href="{{ route('jobseeker.jobs.index') }}"
        class="bg-gray-200 px-4 py-2 rounded text-center">
        Reset
    </a>

</form>

<!-- TABLE -->
<div class="overflow-x-auto admin-surface rounded-xl admin-fade-up">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 p-4">
        @forelse($jobs as $job)
        @php
        $applicationStatus = $applicationStatuses[$job->id] ?? null;
        $jobTypes = collect(explode(',', strtolower($job->type ?? '')))->map(fn($type) => trim($type));
        $isFullTimeJob = $jobTypes->contains('full-time');
        $limitReached = ($activeApplicationsCount ?? 0) >= 2;
        $fullTimeLimitReached = ($activeHasFullTime ?? false) && $isFullTimeJob;
        $isApplyBlockedByRule = $limitReached || $fullTimeLimitReached;
        $types = explode(',', $job->type ?? 'full-time');
        $isSaved = in_array($job->id, $savedJobs ?? []);
        @endphp

        <div class="relative rounded-xl border border-gray-200 bg-white transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute top-4 right-4 z-20">
                @if($isSaved)
                <form action="{{ route('jobseeker.saved.destroy', $job->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); this.closest('form').submit();"
                        aria-label="Unsave"
                        class="inline-flex items-center justify-center text-red-500 hover:text-red-600 transition">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M11.645 20.91a.75.75 0 0 0 .71 0c2.64-1.356 4.852-3.154 6.478-5.24 1.63-2.092 2.417-4.024 2.417-5.814 0-2.915-2.326-5.356-5.297-5.356-1.57 0-3.058.7-4.203 1.9-1.145-1.2-2.633-1.9-4.203-1.9C4.776 4.5 2.45 6.94 2.45 9.856c0 1.79.786 3.722 2.416 5.814 1.627 2.086 3.839 3.884 6.479 5.24z" />
                        </svg>
                    </button>
                </form>
                @else
                <form action="{{ route('jobseeker.saved.store', $job->id) }}" method="POST">
                    @csrf
                    <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); this.closest('form').submit();"
                        aria-label="Save"
                        class="inline-flex items-center justify-center text-gray-400 hover:text-red-500 transition">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.435 6.582C20.596 4.546 18.586 3.25 16.399 3.25c-1.656 0-3.2.743-4.399 2.028A5.84 5.84 0 0 0 7.601 3.25c-2.187 0-4.197 1.296-5.036 3.332-.84 2.039-.288 4.527 1.529 6.749C5.88 15.51 8.515 17.6 11.55 19.368a.9.9 0 0 0 .9 0c3.035-1.768 5.67-3.858 7.456-6.037 1.817-2.222 2.369-4.71 1.529-6.75z" />
                        </svg>
                    </button>
                </form>
                @endif
            </div>

            <a href="{{ route('jobseeker.jobs.show', $job->id) }}" class="block p-5 space-y-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900 leading-tight pr-10">{{ $job->title }}</h3>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ trim(($job->employer->first_name ?? '') . ' ' . ($job->employer->last_name ?? '')) ?: 'Employer' }}
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    {{ $job->location ?? 'No location' }}
                </div>

                <div class="flex flex-wrap gap-1.5">
                    @foreach($types as $type)
                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                        {{ ucfirst(trim($type)) }}
                    </span>
                    @endforeach
                </div>

                <div class="text-sm font-medium text-gray-800">
                    {{ number_format($job->salary_min ?? 0) }} - {{ number_format($job->salary_max ?? 0) }}
                </div>
            </a>
        </div>
        @empty
        <div class="col-span-full text-center py-8 text-gray-500">
            No jobs available
        </div>
        @endforelse
    </div>

</div>

</div>

@endsection