@extends('layouts.applicant')

@section('title', 'My Profile')
@section('subtitle', 'Manage your personal information and account security.')

@section('content')
@php
$photoPath = null;
if (!empty($user->profile_photo_path)) {
$photoPath = str_contains($user->profile_photo_path, '/')
? asset('storage/' . $user->profile_photo_path)
: asset('storage/profile/' . $user->profile_photo_path);
}
$defaultAvatar = asset('images/default-avatar.png');
$applyJobId = request()->query('job');
@endphp

<div class="w-full">
    <div class="space-y-6 max-w-6xl">
        @if(session('success'))
        <div class="p-3 rounded-lg border border-green-200 bg-green-50 text-green-800 text-sm">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="p-3 rounded-lg border border-red-200 bg-red-50 text-red-800 text-sm">{{ $errors->first() }}</div>
        @endif

        @if(!($isEditMode ?? false))
        <div class="admin-surface rounded-xl admin-fade-up overflow-hidden">
            <div class="px-6 py-5 md:px-8 md:py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Applicant Profile</h2>
                        <p class="text-sm text-gray-500 mt-1">Review your personal and professional information.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('applicant.profile.edit') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg admin-button-primary text-white text-sm font-medium hover:brightness-95 transition">Edit Profile</a>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <aside class="lg:col-span-4">
                        <div class="border border-gray-200 bg-white shadow-sm p-5 space-y-4">
                            <div class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-500">Profile Photo</div>
                            <div class="w-full max-w-[260px] aspect-square overflow-hidden border border-gray-200 bg-gray-50">
                                <img src="{{ $photoPath ?? $defaultAvatar }}" alt="Profile Photo" class="h-full w-full object-cover">
                            </div>
                        </div>
                    </aside>

                    <section class="lg:col-span-8 space-y-6">
                        <div class="border border-gray-200 bg-white shadow-sm">
                            <div class="px-5 py-4 border-b border-gray-100">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Personal Information</h3>
                            </div>
                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                <div>
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Full Name</div>
                                    <div class="text-sm font-medium text-gray-900">{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Email Address</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->email ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Contact Number</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->contact_number ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Date of Birth</div>
                                    <div class="text-sm font-medium text-gray-900">{{ optional($user->date_of_birth)->format('M d, Y') ?? 'N/A' }}</div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Address</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->address ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 bg-white shadow-sm">
                            <div class="px-5 py-4 border-b border-gray-100">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Professional Information</h3>
                            </div>
                            <div class="p-5 grid grid-cols-1 gap-y-5">
                                <div>
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Target Position</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->desired_job_title ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Core Skills</div>
                                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $user->skills ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Professional Experience</div>
                                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $user->work_experience ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-[0.12em] text-gray-500 mb-1">Educational Background</div>
                                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $user->education ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            @if($applyJobId)
            <div class="p-6 md:p-8 border-t border-gray-100">
                <form action="{{ route('applicant.apply', $applyJobId) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded admin-button-primary text-white hover:brightness-95">Apply for this Job</button>
                </form>
            </div>
            @endif
        </div>
        @else
        <div class="admin-surface rounded-xl admin-fade-up overflow-hidden">
            <div class="px-6 py-5 md:px-8 md:py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Edit Applicant Profile</h2>
                <p class="text-sm text-gray-500 mt-1">Update your biodata to keep your profile ready for new opportunities.</p>
            </div>

            <form method="POST" action="{{ route('applicant.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-6">
                    <section class="border border-gray-200 bg-white shadow-sm">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Personal Information</h3>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Full Name</label>
                                <input type="text" name="full_name" value="{{ old('full_name', trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-gray-50 text-gray-600" readonly required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Contact Number</label>
                                <input type="tel" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" maxlength="11" pattern="[0-9]{11}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Residential Address</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" required>
                            </div>
                        </div>
                    </section>

                    <section class="border border-gray-200 bg-white shadow-sm">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Professional Information</h3>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-5">
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Target Position</label>
                                <input type="text" name="desired_job_title" value="{{ old('desired_job_title', $user->desired_job_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Core Skills</label>
                                <textarea name="skills" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" required>{{ old('skills', $user->skills) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Professional Experience</label>
                                <textarea name="work_experience" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" required>{{ old('work_experience', $user->work_experience) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Educational Background</label>
                                <textarea name="education" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" required>{{ old('education', $user->education) }}</textarea>
                            </div>
                        </div>
                    </section>

                    <section class="border border-gray-200 bg-white shadow-sm">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.08em] text-gray-700">Profile Photo Upload</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-2">Upload New Photo</label>
                                <input type="file" name="profile_photo" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-24 h-24 overflow-hidden border border-gray-200 bg-gray-50">
                                    <img src="{{ $photoPath ?? $defaultAvatar }}" alt="Current Profile Photo" class="w-full h-full object-cover">
                                </div>
                                @if($photoPath)
                                <a href="{{ $photoPath }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 hover:underline">View current photo</a>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>

                <div class="px-6 py-4 md:px-8 border-t border-gray-200 bg-white">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('applicant.profile') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition">Cancel</a>
                        <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg admin-button-primary text-white text-sm font-semibold hover:brightness-95 transition">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection