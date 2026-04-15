@extends('admin.layouts.app')

@section('title', 'Jobs Management')
@section('subtitle', 'Manage all job posts across employers.')

@section('content')
<div class="admin-surface rounded-xl p-5 admin-fade-up">
    <form method="GET" action="{{ route('admin.jobs.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-6">
            <label for="q" class="block text-xs mb-1" style="color: var(--admin-muted);">Search</label>
            <input id="q" name="q" type="text" value="{{ $search }}" placeholder="Search title, description, location" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
        </div>
        <div class="md:col-span-3">
            <label for="status" class="block text-xs mb-1" style="color: var(--admin-muted);">Status</label>
            <select id="status" name="status" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
                <option value="">All</option>
                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div class="md:col-span-3 flex items-end gap-2">
            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Apply</button>
            <a href="{{ route('admin.jobs.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Reset</a>
            <a href="{{ route('admin.jobs.create') }}" class="rounded-lg bg-slate-900 text-white px-4 py-2 text-sm font-semibold">Create</a>
        </div>
    </form>
</div>

<div class="admin-surface rounded-xl p-5 admin-fade-up overflow-x-auto">
    @if($jobs->count())
    <table class="admin-table min-w-full text-sm">
        <thead>
            <tr>
                <th class="py-3 pr-4">Title</th>
                <th class="py-3 pr-4">Employer</th>
                <th class="py-3 pr-4">Salary</th>
                <th class="py-3 pr-4">Location</th>
                <th class="py-3 pr-4">Status</th>
                <th class="py-3 pr-4">Created</th>
                <th class="py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobs as $job)
            <tr>
                <td class="py-3 pr-4 font-semibold">{{ $job->title }}</td>
                <td class="py-3 pr-4">{{ trim((optional($job->employer)->first_name ?? '') . ' ' . (optional($job->employer)->last_name ?? '')) ?: 'N/A' }}</td>
                <td class="py-3 pr-4">{{ $job->salary_min || $job->salary_max ? 'PHP ' . number_format($job->salary_min ?? 0) . ' - PHP ' . number_format($job->salary_max ?? 0) : 'N/A' }}</td>
                <td class="py-3 pr-4">{{ $job->location }}</td>
                <td class="py-3 pr-4">
                    <span class="rounded-full px-2.5 py-1 text-xs {{ $job->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $job->status === 'active' ? 'Open' : 'Closed' }}
                    </span>
                </td>
                <td class="py-3 pr-4">{{ optional($job->created_at)->format('M d, Y') }}</td>
                <td class="py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.jobs.show', $job) }}" class="rounded-lg border px-3 py-1.5 text-xs font-semibold" style="border-color: var(--admin-border);">View</a>
                        <a href="{{ route('admin.jobs.edit', $job) }}" class="rounded-lg border px-3 py-1.5 text-xs font-semibold" style="border-color: var(--admin-border);">Edit</a>
                        <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" onsubmit="return confirm('Archive this job post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700">Archive</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $jobs->links() }}</div>
    @else
    @include('admin.components.empty-state', ['title' => 'No jobs found', 'message' => 'Create job postings or update your filters.'])
    @endif
</div>
@endsection