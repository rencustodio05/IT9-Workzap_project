@extends('layouts.jobseeker')

@section('title', 'Job Details')
@section('subtitle', 'Review information and submit your application.')

@section('content')
@php
$job = $job ?? ($application->job ?? null);
$backUrl = url()->previous();
if ($backUrl === url()->current()) {
$backUrl = 'javascript:history.back()';
}
@endphp

<div class="js-card p-6">
    <div class="flex items-start justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $job->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $job->employer->name ?? 'Employer' }} · {{ $job->location }}
            </p>
        </div>
        <a href="{{ $backUrl }}" class="px-4 py-2 rounded border text-gray-700 hover:bg-gray-100">
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-50 rounded p-4">
            <div class="text-xs text-gray-500">Salary</div>
            <div class="font-semibold text-gray-900">
                ₱{{ number_format($job->salary_min ?? 0) }} - ₱{{ number_format($job->salary_max ?? 0) }}
            </div>
        </div>
        <div class="bg-gray-50 rounded p-4">
            <div class="text-xs text-gray-500">Type</div>
            <div class="font-semibold text-gray-900">{{ $job->type ? ucfirst(str_replace(',', ', ', $job->type)) : 'Not specified' }}</div>
        </div>
        <div class="bg-gray-50 rounded p-4">
            <div class="text-xs text-gray-500">Status</div>
            <div class="font-semibold text-gray-900">{{ ucfirst($job->status) }}</div>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="font-semibold text-gray-900 mb-2">Job Description</h2>
        <p class="text-gray-700 whitespace-pre-line">{{ $job->description }}</p>
    </div>

    <div>
        @if(isset($application))
        @if(in_array($application->status, ['pending', 'interview']))
        <form method="POST" action="{{ route('jobseeker.applications.update', $application->id) }}">
            @csrf
            @method('PUT')
            <button class="px-4 py-2 bg-red-600 text-white rounded">Cancel Application</button>
        </form>
        @elseif(in_array($application->status, ['cancelled', 'rejected']))
        <form method="POST" action="{{ route('jobseeker.apply', $job->id) }}">
            @csrf
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Apply Again</button>
        </form>
        @elseif($application->status === 'hired')
        <button disabled class="px-4 py-2 rounded bg-green-100 text-green-800">Currently Hired</button>
        @endif
        @else
        @if($alreadyApplied)
        <button disabled class="px-4 py-2 rounded bg-gray-300 text-gray-600">Already Applied</button>
        @elseif($applyBlockedByRule ?? false)
        <button disabled class="px-4 py-2 rounded bg-gray-300 text-gray-600 cursor-not-allowed">Apply for this job</button>
        <p class="text-sm text-red-600 mt-2">{{ $applyRestrictionMessage }}</p>
        @else
        <form action="{{ route('jobseeker.apply', $job->id) }}" method="POST">
            @csrf
            <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Apply for this job</button>
        </form>
        @endif
        @endif
    </div>
</div>
</div>
@endsection