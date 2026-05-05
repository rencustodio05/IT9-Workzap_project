@extends('admin.layouts.app')

@section('title', 'Create User')
@section('subtitle', 'Add a new account to the system.')

@section('content')
<div class="max-w-3xl admin-surface rounded-xl p-5 admin-fade-up">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="employer" {{ old('role') === 'employer' ? 'selected' : '' }}>Employer</option>
                    <option value="applicant" {{ old('role') === 'applicant' ? 'selected' : '' }}>Applicant</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Account Status</label>
                <select name="is_active" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Cancel</a>
        </div>
    </form>
</div>
@endsection