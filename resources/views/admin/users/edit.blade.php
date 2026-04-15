@extends('admin.layouts.app')

@section('title', 'Edit User Role')
@section('subtitle', 'Update role assignment for selected user account.')

@section('content')
<div class="max-w-2xl admin-surface rounded-xl p-5 admin-fade-up">
    <div class="mb-5">
        <div class="text-sm" style="color: var(--admin-muted);">User</div>
        <div class="text-lg font-bold">{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A' }}</div>
        <div class="text-sm" style="color: var(--admin-muted);">{{ $user->email }}</div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="role" class="block text-sm font-medium mb-1">Role</label>
            <select id="role" name="role" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="employer" {{ old('role', $user->role) === 'employer' ? 'selected' : '' }}>Employer</option>
                <option value="jobseeker" {{ old('role', $user->role) === 'jobseeker' ? 'selected' : '' }}>Jobseeker</option>
            </select>
            @error('role')
            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="is_active" class="block text-sm font-medium mb-1">Account Status</label>
            <select id="is_active" name="is_active" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
                <option value="1" {{ (int) old('is_active', (int) $user->is_active) === 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ (int) old('is_active', (int) $user->is_active) === 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('is_active')
            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center gap-2 pt-2">
            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Save Changes</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Back</a>
        </div>
    </form>
</div>
@endsection