@extends('layouts.employer')

@section('title', 'Application Details')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-4">Applicant Details</h2>

    <p><strong>Name:</strong> {{ $application->jobseeker->name }}</p>
    <p><strong>Email:</strong> {{ $application->jobseeker->email }}</p>

    <hr class="my-4">

    <p><strong>Job:</strong> {{ $application->job->title }}</p>
    <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>
    <p><strong>Date Applied:</strong> {{ $application->created_at->format('M d, Y') }}</p>

    <div class="mt-6">
        <a href="{{ route('applications.index') }}"
            class="px-4 py-2 bg-gray-200 rounded">
            Back
        </a>
    </div>

</div>
@endsection