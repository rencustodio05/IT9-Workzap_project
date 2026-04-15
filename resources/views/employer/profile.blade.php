@extends('layouts.employer')

@section('title', 'My Profile')
@section('subtitle', 'Manage your profile and account security.')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My profile</h1>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="bg-white rounded-lg shadow p-8 flex flex-col items-center">
        <div class="mb-6">
            <div class="rounded-full bg-gray-200 w-24 h-24 flex items-center justify-center text-4xl text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 15a4 4 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <div class="text-center space-y-1 mb-4">
            <div class="text-xl font-bold text-gray-900">{{ $user->name }}</div>
            <div class="text-gray-500">Employer</div>
            <div class="text-gray-400 text-sm">{{ $user->email }}</div>
        </div>

        <form method="POST" action="{{ route('employer.profile.update') }}" class="w-full mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">First Name</label>
                <input name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Last Name</label>
                <input name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <button class="mt-1 inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">
                Edit profile
            </button>
        </form>

        <div class="w-full border-t mt-8 pt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Change Password</h2>

            @if(!session('password_verified'))
            <form method="POST" action="{{ route('employer.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="step" value="verify">

                <div>
                    <label class="block text-sm font-medium mb-1">Current Password</label>
                    <input name="current_password" type="password" class="w-full border rounded px-3 py-2" required>
                </div>

                <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Verify Password</button>
            </form>
            @else
            <form method="POST" action="{{ route('employer.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="step" value="update">

                <div>
                    <label class="block text-sm font-medium mb-1">Current Password</label>
                    <input name="current_password" type="password" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">New Password</label>
                    <input name="password" type="password" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Confirm New Password</label>
                    <input name="password_confirmation" type="password" class="w-full border rounded px-3 py-2" required>
                </div>

                <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save New Password</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection