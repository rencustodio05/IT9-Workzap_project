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
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Account Security</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email Address</label>
                <input name="email" type="email" value="{{ $user->email }}" class="w-full rounded-lg border px-3 py-2 bg-gray-50" readonly>
            </div>

            <form method="POST" action="{{ route('applicant.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="step" value="update">

                <div>
                    <label class="block text-sm font-medium mb-1">Current Password</label>
                    <input name="current_password" type="password" class="w-full rounded-lg border px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">New Password</label>
                    <input name="password" type="password" class="w-full rounded-lg border px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <input name="password_confirmation" type="password" class="w-full rounded-lg border px-3 py-2" required>
                </div>

                <div class="pt-2 flex items-center gap-2">
                    <button class="px-4 py-2 rounded bg-gray-700 text-white hover:bg-gray-800">Update Password</button>
                    <a href="{{ route('applicant.profile') }}" class="px-4 py-2 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">Back to Profile</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection