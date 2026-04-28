@extends('layouts.employer')

@section('title', 'Job Details')
@section('subtitle', 'Review job post information and performance.')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="admin-surface rounded-xl p-6 space-y-4">

        <!-- HEADER INSIDE CARD -->
        <div class="flex items-start justify-between gap-4 border-b pb-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900">
                    {{ $job->title }}
                </h1>

                <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $job->status === 'active'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>
            </div>

            <a href="{{ route('employer.jobs.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                Back
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

            <div>
                <div class="text-gray-500 text-xs uppercase">Location</div>
                <div class="font-semibold text-gray-900">{{ $job->location }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase">Salary</div>
                <div class="font-semibold text-gray-900">
                    {{ ($job->salary_min || $job->salary_max)
                        ? number_format($job->salary_min ?? 0, 2) . ' - ' . number_format($job->salary_max ?? 0, 2)
                        : 'Not specified' }}
                </div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase">Views</div>
                <div class="font-semibold text-gray-900">{{ $job->views }}</div>
            </div>

            <div>
                <div class="text-gray-500 text-xs uppercase">Posted</div>
                <div class="font-semibold text-gray-900">{{ $job->created_at->format('F d, Y') }}</div>
            </div>

        </div>

        <div class="mt-4">
            <div class="text-xs uppercase text-gray-500 mb-2">Job Description</div>
            <div class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                {{ $job->description }}
            </div>
        </div>
    </div>
</div>

@endsection