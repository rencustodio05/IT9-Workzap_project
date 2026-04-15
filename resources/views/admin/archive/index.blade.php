@extends('admin.layouts.app')

@section('title', 'Archive')
@section('subtitle', 'Restore archived users and job posts.')

@section('content')
<div class="space-y-6">
    <div class="admin-surface rounded-xl p-5 admin-fade-up overflow-x-auto">
        <h3 class="text-base font-semibold mb-3">Archived Users</h3>
        @if($deletedUsers->count())
        <table class="admin-table min-w-full text-sm">
            <thead>
                <tr>
                    <th class="py-3 pr-4">Name</th>
                    <th class="py-3 pr-4">Email</th>
                    <th class="py-3 pr-4">Role</th>
                    <th class="py-3 pr-4">Archived At</th>
                    <th class="py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedUsers as $user)
                <tr>
                    <td class="py-3 pr-4">{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A' }}</td>
                    <td class="py-3 pr-4">{{ $user->email }}</td>
                    <td class="py-3 pr-4">{{ ucfirst($user->role) }}</td>
                    <td class="py-3 pr-4">{{ optional($user->deleted_at)->format('M d, Y h:i A') }}</td>
                    <td class="py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('admin.archive.users.restore', $user->id) }}" onsubmit="return confirm('Restore this user?');">
                                @csrf
                                <button type="submit" class="rounded-lg bg-emerald-600 text-white px-3 py-1.5 text-xs font-semibold">Restore</button>
                            </form>
                            <form method="POST" action="{{ route('admin.archive.users.force-delete', $user->id) }}" onsubmit="return confirm('Permanently delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-600 text-white px-3 py-1.5 text-xs font-semibold">Delete Permanently</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $deletedUsers->links() }}</div>
        @else
        @include('admin.components.empty-state', ['title' => 'No archived users', 'message' => 'Soft-deleted users will appear here.'])
        @endif
    </div>

    <div class="admin-surface rounded-xl p-5 admin-fade-up overflow-x-auto">
        <h3 class="text-base font-semibold mb-3">Archived Jobs</h3>
        @if($deletedJobs->count())
        <table class="admin-table min-w-full text-sm">
            <thead>
                <tr>
                    <th class="py-3 pr-4">Title</th>
                    <th class="py-3 pr-4">Employer</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Archived At</th>
                    <th class="py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedJobs as $job)
                <tr>
                    <td class="py-3 pr-4">{{ $job->title }}</td>
                    <td class="py-3 pr-4">{{ trim((optional($job->employer)->first_name ?? '') . ' ' . (optional($job->employer)->last_name ?? '')) ?: 'N/A' }}</td>
                    <td class="py-3 pr-4">{{ $job->status === 'active' ? 'Open' : 'Closed' }}</td>
                    <td class="py-3 pr-4">{{ optional($job->deleted_at)->format('M d, Y h:i A') }}</td>
                    <td class="py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('admin.archive.jobs.restore', $job->id) }}" onsubmit="return confirm('Restore this job?');">
                                @csrf
                                <button type="submit" class="rounded-lg bg-emerald-600 text-white px-3 py-1.5 text-xs font-semibold">Restore</button>
                            </form>
                            <form method="POST" action="{{ route('admin.archive.jobs.force-delete', $job->id) }}" onsubmit="return confirm('Permanently delete this job?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-600 text-white px-3 py-1.5 text-xs font-semibold">Delete Permanently</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $deletedJobs->links() }}</div>
        @else
        @include('admin.components.empty-state', ['title' => 'No archived jobs', 'message' => 'Soft-deleted jobs will appear here.'])
        @endif
    </div>
</div>
@endsection