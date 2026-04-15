@extends('admin.layouts.app')

@section('title', 'Job Details')
@section('subtitle', 'View complete job information.')

@section('content')
<div class="max-w-4xl admin-surface rounded-xl p-5 admin-fade-up">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Title</div>
            <div class="mt-1 font-semibold">{{ $job->title }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Employer</div>
            <div class="mt-1 font-semibold">{{ trim((optional($job->employer)->first_name ?? '') . ' ' . (optional($job->employer)->last_name ?? '')) ?: 'N/A' }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Employer Email</div>
            <div class="mt-1 font-semibold">{{ optional($job->employer)->email ?? 'N/A' }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Status</div>
            <div class="mt-1 font-semibold">{{ $job->status === 'active' ? 'Open' : 'Closed' }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Salary Range</div>
            <div class="mt-1 font-semibold">{{ $job->salary_min || $job->salary_max ? 'PHP ' . number_format($job->salary_min ?? 0) . ' - PHP ' . number_format($job->salary_max ?? 0) : 'N/A' }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Location</div>
            <div class="mt-1 font-semibold">{{ $job->location }}</div>
        </div>
        <div class="md:col-span-2">
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Description</div>
            <div class="mt-1 whitespace-pre-line">{{ $job->description }}</div>
        </div>
        <div>
            <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Created At</div>
            <div class="mt-1 font-semibold">{{ optional($job->created_at)->format('M d, Y h:i A') }}</div>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-2">
        <a href="{{ route('admin.jobs.edit', $job) }}" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Edit Job</a>
        <a href="{{ route('admin.jobs.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Back</a>
    </div>
</div>
@endsection