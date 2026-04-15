@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('subtitle', 'Search, filter, update roles, and manage registered users.')

@section('content')
<div class="admin-surface rounded-xl p-5 admin-fade-up">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-6">
            <label for="q" class="block text-xs mb-1" style="color: var(--admin-muted);">Search</label>
            <input
                id="q"
                name="q"
                type="text"
                value="{{ $search }}"
                placeholder="Search name or email"
                class="w-full rounded-lg border px-3 py-2 bg-white"
                style="border-color: var(--admin-border);">
        </div>

        <div class="md:col-span-3">
            <label for="role" class="block text-xs mb-1" style="color: var(--admin-muted);">Role</label>
            <select id="role" name="role" class="w-full rounded-lg border px-3 py-2 bg-white" style="border-color: var(--admin-border);">
                <option value="">All roles</option>
                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="employer" {{ $role === 'employer' ? 'selected' : '' }}>Employer</option>
                <option value="jobseeker" {{ $role === 'jobseeker' ? 'selected' : '' }}>Jobseeker</option>
            </select>
        </div>

        <div class="md:col-span-3 flex items-end gap-2">
            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Apply</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Reset</a>
            <a href="{{ route('admin.users.create') }}" class="rounded-lg bg-slate-900 text-white px-4 py-2 text-sm font-semibold">Create</a>
        </div>
    </form>
</div>

<div class="admin-surface rounded-xl p-5 admin-fade-up overflow-x-auto">
    <table class="admin-table min-w-full text-sm">
        <thead>
            <tr>
                <th class="py-3 pr-4">Name</th>
                <th class="py-3 pr-4">Email</th>
                <th class="py-3 pr-4">Role</th>
                <th class="py-3 pr-4">Account</th>
                <th class="py-3 pr-4">Registered</th>
                <th class="py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td class="py-3 pr-4 font-semibold">{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A' }}</td>
                <td class="py-3 pr-4" style="color: var(--admin-muted);">{{ $user->email }}</td>
                <td class="py-3 pr-4"><span class="admin-chip rounded-full px-2.5 py-1 text-xs">{{ ucfirst($user->role) }}</span></td>
                <td class="py-3 pr-4">
                    <span class="rounded-full px-2.5 py-1 text-xs {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="py-3 pr-4" style="color: var(--admin-muted);">{{ optional($user->created_at)->format('M d, Y') }}</td>
                <td class="py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="rounded-lg border px-3 py-1.5 text-xs font-semibold" style="border-color: var(--admin-border);">View</a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border px-3 py-1.5 text-xs font-semibold" style="border-color: var(--admin-border);">Edit Role</a>
                        <button
                            type="button"
                            class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700"
                            data-delete-trigger
                            data-user-name="{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->email }}"
                            data-delete-url="{{ route('admin.users.destroy', $user) }}">
                            Archive
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center" style="color: var(--admin-muted);">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
    <div class="admin-surface rounded-xl w-full max-w-md p-5">
        <h3 class="text-base font-bold">Archive User</h3>
        <p class="mt-2 text-sm" style="color: var(--admin-muted);">
            Are you sure you want to archive <span id="deleteUserName" class="font-semibold"></span>? You can restore this account from Archive.
        </p>

        <div class="mt-5 flex items-center justify-end gap-2">
            <button type="button" id="deleteCancel" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Cancel</button>
            <form id="deleteForm" method="POST" action="#">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 text-white px-4 py-2 text-sm font-semibold hover:bg-red-700">Archive</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteUserName = document.getElementById('deleteUserName');
        const cancel = document.getElementById('deleteCancel');

        if (!modal || !deleteForm || !deleteUserName || !cancel) {
            return;
        }

        document.querySelectorAll('[data-delete-trigger]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const name = btn.getAttribute('data-user-name') || 'this user';
                const url = btn.getAttribute('data-delete-url');

                deleteUserName.textContent = name;
                deleteForm.setAttribute('action', url || '#');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        cancel.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });
</script>
@endpush