@extends('layouts.employer')

@section('title', 'Application Details')
@section('subtitle', 'Review applicant profile and take action.')

@section('content')
@php
$jobseeker = $application->jobseeker;
$fullName = trim(($jobseeker->first_name ?? '') . ' ' . ($jobseeker->last_name ?? '')) ?: 'N/A';

$photoPath = null;
if (!empty($jobseeker->profile_photo_path)) {
$photoPath = str_contains($jobseeker->profile_photo_path, '/')
? asset('storage/' . ltrim($jobseeker->profile_photo_path, '/'))
: asset('storage/profile/' . $jobseeker->profile_photo_path);
} elseif (!empty($jobseeker->avatar)) {
$photoPath = str_starts_with($jobseeker->avatar, 'http://') || str_starts_with($jobseeker->avatar, 'https://')
? $jobseeker->avatar
: asset('storage/' . ltrim($jobseeker->avatar, '/'));
}

$defaultAvatar = asset('images/default-avatar.png');
$isFired = ($application->status === 'fired');
$isHired = ($application->status === 'hired');
$isRejected = ($application->status === 'rejected');
$isCancelled = ($application->status === 'cancelled');
$isDecided = in_array($application->status, ['rejected', 'hired', 'fired', 'cancelled'], true);
$hasInterview = $application->interview !== null;
$shouldAutoOpenInterviewModal = $hasInterview
? ($errors->has('interview_date') || $errors->has('interview_time') || $errors->has('notes'))
: ($errors->has('application_id') || $errors->has('interview_date') || $errors->has('interview_time') || $errors->has('notes'));
@endphp

<div class="max-w-3xl mx-auto">
    @if($isHired)
    <div class="relative mb-3 min-h-[28vh] flex items-center justify-center overflow-hidden">
        <div class="absolute h-28 w-28 rounded-full bg-green-200/20 blur-2xl"></div>
        <div class="relative text-center">
            <div class="text-3xl font-bold uppercase tracking-widest text-green-600">Hired</div>
            <p class="mt-2 text-sm text-gray-500">This applicant is currently hired for this position</p>
        </div>
    </div>
    @endif

    @if($isFired)
    <div class="relative mb-3 min-h-[28vh] flex items-center justify-center overflow-hidden">
        <div class="absolute h-28 w-28 rounded-full bg-red-200/20 blur-2xl"></div>
        <div class="relative text-center">
            <div class="text-3xl font-bold uppercase tracking-widest text-red-600">Fired</div>
            <p class="mt-2 text-sm text-gray-500">This applicant is no longer active in this position</p>
        </div>
    </div>
    @endif

    <div class="admin-surface rounded-xl overflow-hidden {{ $isFired ? 'opacity-70 grayscale' : '' }}">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
            <h2 class="text-2xl font-bold text-gray-900">Applicant Profile</h2>
            <p class="text-sm text-gray-500 mt-1">Review personal and professional profile information.</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <aside class="lg:col-span-4">
                    <div class="border border-gray-200 bg-white shadow-sm p-4 space-y-3">
                        <div class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-500">Profile Photo</div>
                        <div class="w-full aspect-square overflow-hidden border border-gray-200 bg-gray-50">
                            <img src="{{ $photoPath ?? $defaultAvatar }}" alt="Applicant Profile Photo" class="w-full h-full object-cover">
                        </div>
                        <div class="text-xs text-gray-500"></div>
                    </div>
                </aside>

                <section class="lg:col-span-8 space-y-5">
                    <div class="border border-gray-200 bg-white shadow-sm">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Personal Information</h3>
                        </div>

                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Full Name</div>
                                <div class="text-sm font-medium text-gray-900">{{ $fullName }}</div>
                            </div>

                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Email Address</div>
                                <div class="text-sm font-medium text-gray-900">{{ $jobseeker->email ?? 'N/A' }}</div>
                            </div>

                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Contact Number</div>
                                <div class="text-sm font-medium text-gray-900">{{ $jobseeker->contact_number ?? $jobseeker->phone ?? 'N/A' }}</div>
                            </div>

                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Date of Birth</div>
                                <div class="text-sm font-medium text-gray-900">{{ optional($jobseeker->date_of_birth)->format('M d, Y') ?? 'N/A' }}</div>
                            </div>

                            <div class="md:col-span-2">
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Residential Address</div>
                                <div class="text-sm font-medium text-gray-900">{{ $jobseeker->address ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 bg-white shadow-sm">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Professional Information</h3>
                        </div>

                        <div class="p-5 grid grid-cols-1 gap-y-4">
                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Target Position</div>
                                <div class="text-sm font-medium text-gray-900">{{ $jobseeker->desired_job_title ?? 'N/A' }}</div>
                            </div>

                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Core Skills</div>
                                <div class="text-sm text-gray-900 whitespace-pre-line">{{ $jobseeker->skills ?? 'N/A' }}</div>
                            </div>

                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Professional Experience</div>
                                <div class="text-sm text-gray-900 whitespace-pre-line">{{ $jobseeker->work_experience ?? $jobseeker->experience ?? 'N/A' }}</div>
                            </div>

                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Educational Background</div>
                                <div class="text-sm text-gray-900 whitespace-pre-line">{{ $jobseeker->education ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between gap-3"
                x-data="{ openInterviewModal: {{ $shouldAutoOpenInterviewModal ? 'true' : 'false' }} }"
                @keydown.escape.window="openInterviewModal = false">
                <div class="flex items-center gap-3">
                    <a href="{{ route('employer.applications.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
                        Back
                    </a>

                    @if($isDecided)
                    <span class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium">
                        Already decided: {{ ucfirst($application->status) }}
                    </span>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    @if($application->status === 'hired')
                    <form method="POST" action="{{ route('employer.applications.fire', $application->id) }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-gray-700 text-white shadow-sm transition hover:bg-gray-800">
                            Fire
                        </button>
                    </form>

                    @endif

                    @if(!$isDecided)
                    @if(!$hasInterview)
                    <button type="button"
                        @click="openInterviewModal = true"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-white shadow-sm transition bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800">
                        Schedule Interview
                    </button>
                    @else
                    <button type="button"
                        @click="openInterviewModal = true"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-white shadow-sm transition bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800">
                        Reschedule Interview
                    </button>
                    @endif

                    @include('employer.applications.partials.interview-modal', [
                    'application' => $application,
                    'fullName' => $fullName,
                    ])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection