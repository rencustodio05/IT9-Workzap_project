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

@endphp
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
            <h2 class="text-2xl font-bold text-gray-900">Jobseeker Profile</h2>
            <p class="text-sm text-gray-500 mt-1">Review personal and professional profile information.</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <aside class="lg:col-span-4">
                    <div class="border border-gray-200 bg-white shadow-sm p-4 space-y-3">
                        <div class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-500">Profile Photo</div>
                        <div class="w-full aspect-square overflow-hidden border border-gray-200 bg-gray-50">
                            <img src="{{ $photoPath ?? $defaultAvatar }}" alt="Jobseeker Profile Photo" class="w-full h-full object-cover">
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

            <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('applications.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-gray-300">
                    Back
                </a>

                <div class="flex flex-wrap items-center gap-3 sm:justify-end">
                    <form method="POST" action="{{ route('applications.update', $application->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                            Reject
                        </button>
                    </form>

                    <form method="POST" action="{{ route('applications.update', $application->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="hired">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">
                            Hire
                        </button>
                    </form>

                    <form method="POST" action="{{ route('applications.update', $application->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="fired">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-900">
                            Fire
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection