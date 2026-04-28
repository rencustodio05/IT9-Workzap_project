@extends('layouts.employer')

@section('title', 'Hiring Decision')
@section('subtitle', 'Review interview details and decide the next step.')

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
$interview = $application->interview;
@endphp

<div class="max-w-3xl mx-auto">
    <div class="admin-surface rounded-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
            <h2 class="text-2xl font-bold text-gray-900">Applicant Decision</h2>
            <p class="text-sm text-gray-500 mt-1">Review interview outcomes and make a hiring decision.</p>
        </div>

        <div class="p-6 space-y-6">
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
                            <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Applicant Profile</h3>
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
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Target Position</div>
                                <div class="text-sm font-medium text-gray-900">{{ $jobseeker->desired_job_title ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 bg-white shadow-sm">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Interview Details</h3>
                        </div>

                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Interview Date</div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $interview && $interview->interview_date ? \Illuminate\Support\Carbon::parse($interview->interview_date)->format('M d, Y') : 'N/A' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Interview Time</div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $interview && $interview->interview_time ? \Illuminate\Support\Carbon::parse($interview->interview_time)->format('g:i A') : 'N/A' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Status</div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($interview->status ?? 'scheduled') }}
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Notes</div>
                                <div class="text-sm text-gray-900 whitespace-pre-line">
                                    {{ $interview->notes ?? 'No notes provided.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    @if(in_array($application->status, ['pending', 'interview'], true))
                    <form method="POST" action="{{ route('employer.applications.hire', $application->id) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">Hire</button>
                    </form>

                    <form method="POST" action="{{ route('employer.applications.reject', $application->id) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">Reject</button>
                    </form>
                    @else
                    <span class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium">
                        Decision already made: {{ ucfirst($application->status) }}
                    </span>

                    @if($application->status === 'hired')
                    <form method="POST" action="{{ route('employer.applications.fire', $application->id) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gray-700 text-white hover:bg-gray-800 transition">Fire</button>
                    </form>
                    @endif
                    @endif
                </div>

                <a href="{{ route('employer.applications.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection