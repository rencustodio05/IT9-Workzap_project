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

    <table class="admin-table min-w-full text-sm text-left whitespace-nowrap">

        <thead class="bg-gray-50 border-b">
            <tr class="text-gray-600 text-sm">
                <th class="py-4 px-6">Job Title</th>
                <th class="py-4 px-6">Address</th>
                <th class="py-4 px-6">Type</th>
                <th class="py-4 px-6">Salary</th>
                <th class="py-4 px-6">Action</th>
            </tr>
        </thead>

        <tbody>

            @forelse($jobs as $job)

            <tr class="border-t hover:bg-gray-50">

                <!-- TITLE -->
                <td class="py-4 px-6 font-semibold">
                    {{ $job->title }}
                    <div class="text-xs text-gray-500">
                        {{ trim(($job->employer->first_name ?? '') . ' ' . ($job->employer->last_name ?? '')) ?: 'Employer' }}
                    </div>
                </td>

                <!-- LOCATION -->
                <td class="py-4 px-6 text-sm text-gray-600">
                    {{ $job->location ?? 'No location' }}
                </td>

                <!-- TYPE -->
                <td class="py-4 px-6">
                    @php
                    $types = explode(',', $job->type ?? 'full-time');
                    @endphp

                    @foreach($types as $type)
                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                        {{ ucfirst(trim($type)) }}
                    </span>
                    @endforeach
                </td>

                <!-- SALARY -->
                <td class="py-4 px-6 text-sm">
                    {{ number_format($job->salary_min ?? 0) }}
                    -
                    {{ number_format($job->salary_max ?? 0) }}
                </td>

                <!-- ACTION -->
                <td class="py-4 px-6 flex gap-2">

                    <a href="{{ route('jobseeker.jobs.show', $job->id) }}"
                        title="View"
                        aria-label="View"
                        class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 hover:bg-blue-50 transition">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                            <circle cx="12" cy="12" r="2.75" />
                        </svg>
                    </a>

                    @php
                    $applicationStatus = $applicationStatuses[$job->id] ?? null;
                    $jobTypes = collect(explode(',', strtolower($job->type ?? '')))->map(fn($type) => trim($type));
                    $isFullTimeJob = $jobTypes->contains('full-time');
                    $limitReached = ($activeApplicationsCount ?? 0) >= 2;
                    $fullTimeLimitReached = ($activeHasFullTime ?? false) && $isFullTimeJob;
                    $isApplyBlockedByRule = $limitReached || $fullTimeLimitReached;
                    @endphp

                    @if(in_array($applicationStatus, ['pending', 'interview', 'hired']))

                    <button disabled
                        class="px-3 py-1 bg-gray-300 text-gray-600 rounded">
                        Applied
                    </button>

                    @elseif($isApplyBlockedByRule)

                    <button disabled
                        title="You cannot apply for more than 1 Full-Time job. Part-Time jobs are allowed up to 2."
                        class="px-3 py-1 bg-gray-300 text-gray-600 rounded cursor-not-allowed">
                        Restricted
                    </button>

                    @else

                    <form action="{{ route('jobseeker.apply', $job->id) }}"
                        method="POST">
                        @csrf
                        <button class="px-3 py-1 admin-button-primary text-white rounded hover:brightness-95">
                            {{ in_array($applicationStatus, ['rejected', 'cancelled']) ? 'Apply Again' : 'Apply' }}
                        </button>
                    </form>

                    @endif

                    @php
                    $isSaved = in_array($job->id, $savedJobs ?? []);
                    @endphp

                    @if($isSaved)
                    <form action="{{ route('jobseeker.saved.destroy', $job->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                            Unsave
                        </button>
                    </form>
                    @else
                    <form action="{{ route('jobseeker.saved.store', $job->id) }}" method="POST">
                        @csrf
                        <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Save
                        </button>
                    </form>
                    @endif

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="5" class="text-center py-8 text-gray-500">
                    No jobs available
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

</div>

@endsection