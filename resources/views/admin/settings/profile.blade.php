@extends('admin.layouts.app')

@section('title', 'Profile Admin')
@section('subtitle', 'Update admin profile and change password securely.')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <div class="admin-surface rounded-xl p-5 admin-fade-up">
        <h3 class="text-base font-semibold mb-4">Admin Profile</h3>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username', $admin->username) }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
            </div>

            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Save Profile</button>
        </form>
    </div>

    <div class="admin-surface rounded-xl p-5 admin-fade-up">
        <h3 class="text-base font-semibold mb-4">Change Password</h3>

        <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Old Password</label>
                <input type="password" name="old_password" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">New Password</label>
                <input type="password" name="password" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
            </div>

            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Change Password</button>
        </form>
    </div>
</div>
@endsection