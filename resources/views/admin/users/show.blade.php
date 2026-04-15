@extends('admin.layouts.app')

@section('title', 'User Details')
@section('subtitle', 'View selected user profile information and current role.')

@section('content')
<div class="max-w-3xl admin-surface rounded-xl p-5 admin-fade-up">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">First Name</div>
            <div class="mt-1 font-semibold">{{ $user->first_name ?? 'N/A' }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Last Name</div>
            <div class="mt-1 font-semibold">{{ $user->last_name ?? 'N/A' }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Email</div>
            <div class="mt-1 font-semibold">{{ $user->email }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Username</div>
            <div class="mt-1 font-semibold">{{ $user->username ?? 'N/A' }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Role</div>
            <div class="mt-1"><span class="admin-chip rounded-full px-2.5 py-1 text-xs">{{ ucfirst($user->role) }}</span></div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Account Status</div>
            <div class="mt-1">
                <span class="rounded-full px-2.5 py-1 text-xs {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Created At</div>
            <div class="mt-1 font-semibold">{{ optional($user->created_at)->format('M d, Y h:i A') }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Updated At</div>
            <div class="mt-1 font-semibold">{{ optional($user->updated_at)->format('M d, Y h:i A') }}</div>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Edit Role</a>
        <a href="{{ route('admin.users.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Back to Users</a>
    </div>
</div>
@endsection