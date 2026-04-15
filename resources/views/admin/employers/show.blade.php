@extends('admin.layouts.app')

@section('title', 'Employer Profile')
@section('subtitle', 'Inspect employer activity and account status.')

@section('content')
<div class="space-y-6">
    <div class="admin-surface rounded-xl p-5 admin-fade-up">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Name</div>
                <div class="mt-1 font-semibold">{{ trim(($employer->first_name ?? '') . ' ' . ($employer->last_name ?? '')) ?: 'N/A' }}</div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Email</div>
                <div class="mt-1 font-semibold">{{ $employer->email }}</div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Username</div>
                <div class="mt-1 font-semibold">{{ $employer->username ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Account Status</div>
                <div class="mt-1">
                    <span class="rounded-full px-2.5 py-1 text-xs {{ $employer->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $employer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Posted Jobs</div>
                <div class="mt-1 font-semibold">{{ $jobs->total() }}</div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Total Applicants</div>
                <div class="mt-1 font-semibold">{{ $totalApplicants }}</div>
            </div>
        </div>

        <div class="mt-5 flex items-center gap-2">
            <form method="POST" action="{{ route('admin.employers.toggle-status', $employer) }}" onsubmit="return confirm('Update employer account status?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold {{ $employer->is_active ? 'bg-red-600 text-white' : 'bg-emerald-600 text-white' }}">
                    {{ $employer->is_active ? 'Deactivate Employer' : 'Activate Employer' }}
                </button>
            </form>
            <a href="{{ route('admin.employers.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Back</a>
        </div>
    </div>

    <div class="admin-surface rounded-xl p-5 admin-fade-up overflow-x-auto">
        <h3 class="text-base font-semibold mb-3">Posted Jobs</h3>
        @if($jobs->count())
        <table class="admin-table min-w-full text-sm">
            <thead>
                <tr>
                    <th class="py-3 pr-4">Title</th>
                    <th class="py-3 pr-4">Location</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3">Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $job)
                <tr>
                    <td class="py-3 pr-4">{{ $job->title }}</td>
                    <td class="py-3 pr-4">{{ $job->location }}</td>
                    <td class="py-3 pr-4">{{ $job->status === 'active' ? 'Open' : 'Closed' }}</td>
                    <td class="py-3">{{ optional($job->created_at)->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $jobs->links() }}</div>
        @else
        @include('admin.components.empty-state', ['title' => 'No posted jobs', 'message' => 'This employer has not created any jobs yet.'])
        @endif
    </div>
</div>
@endsection