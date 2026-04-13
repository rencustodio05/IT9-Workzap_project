@extends('layouts.employer')

@section('title', 'Job Details')

@vite(['resources/css/employer.css', 'resources/js/app.js'])

@section('content')

<div class="job-container">

    <div class="job-header">
        <div>
            <h1>{{ $job->title }}</h1>
            <p>{{ $job->location }} · {{ ucfirst($job->status) }}</p>
        </div>

        <a href="{{ route('jobs.index') }}" class="btn-outline">
            Back
        </a>
    </div>

    <div class="job-card" style="flex-direction: column; align-items: flex-start;">

        <p><strong>Location:</strong> {{ $job->location }}</p>

        <p><strong>Salary:</strong>
            {{ $job->salary ? '₱' . number_format($job->salary, 2) : 'Not specified' }}
        </p>

        <p><strong>Status:</strong> {{ ucfirst($job->status) }}</p>

        <p><strong>Views:</strong> 👁 {{ $job->views }}</p>

        <p><strong>Posted:</strong> {{ $job->created_at->format('F d, Y') }}</p>

        <hr style="width:100%; margin:15px 0;">

        <p><strong>Description:</strong></p>

        <p style="white-space: pre-line;">
            {{ $job->description }}
        </p>

    </div>

</div>

@endsection