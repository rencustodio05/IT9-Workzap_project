@extends('layouts.applicant')

@section('title', 'Account Security')
@section('subtitle', 'Manage your email display and password settings.')

@section('content')
<div class="w-full">
    <div class="mx-auto w-full max-w-2xl">
        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
        @endif

        @if(session('password_verified'))
        <div class="mb-4 p-3 rounded bg-blue-100 text-blue-800 text-sm">Current password verified. You can now set a new password.</div>
        @endif

        @if($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">{{ $errors->first() }}</div>
        @endif

        <div class="admin-surface rounded-xl admin-fade-up p-6">
            <div class="max-w-full mx-auto">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11c1.657 0 3-1.343 3-3S17.657 5 16 5s-3 1.343-3 3 1.343 3 3 3zM6 19v-1a4 4 0 014-4h4a4 4 0 014 4v1" />
                        </svg>
                    </div>
                    <div class="font-bold text-lg">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="text-sm text-slate-500">{{ $user->role ?? 'Applicant' }}</div>
                    <div class="text-xs text-slate-400 mt-1">{{ $user->email }}</div>
                </div>

                <h2 class="text-xl font-semibold text-gray-900 mb-4">Account Security</h2>

                <form method="POST" action="{{ route('applicant.profile.password') }}" class="space-y-6" id="account-security-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="step" id="form-step" value="">

                    <div class="bg-white rounded-lg border px-5 py-4 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">First Name</label>
                                <input name="first_name" type="text" value="{{ $user->first_name }}" class="w-full rounded-lg border px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Last Name</label>
                                <input name="last_name" type="text" value="{{ $user->last_name }}" class="w-full rounded-lg border px-3 py-2" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">Email Address</label>
                            <input name="email" type="email" value="{{ $user->email }}" class="w-full rounded-lg border px-3 py-2" required>
                        </div>

                        <div class="pt-4 flex items-center gap-3">
                            <button type="button" id="edit-profile-btn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Edit profile</button>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border px-5 py-4 shadow-sm">
                        <h3 class="text-sm font-medium mb-3">Change Password</h3>

                        <div>
                            <label class="block text-sm font-medium mb-1">Current Password</label>
                            <input name="current_password" type="password" class="w-full rounded-lg border px-3 py-2">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">New Password</label>
                                <input name="password" type="password" class="w-full rounded-lg border px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                                <input name="password_confirmation" type="password" class="w-full rounded-lg border px-3 py-2">
                            </div>
                        </div>

                        <div class="pt-4 flex items-center gap-3">
                            <button type="button" id="verify-password-btn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Verify Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('account-security-form');
    const stepInput = document.getElementById('form-step');
    const editBtn = document.getElementById('edit-profile-btn');
    const verifyBtn = document.getElementById('verify-password-btn');

    if (editBtn) {
        editBtn.addEventListener('click', function() {
            stepInput.value = 'profile';
            form.submit();
        });
    }

    if (verifyBtn) {
        verifyBtn.addEventListener('click', function() {
            stepInput.value = 'verify';
            form.submit();
        });
    }
});
</script>
@endpush