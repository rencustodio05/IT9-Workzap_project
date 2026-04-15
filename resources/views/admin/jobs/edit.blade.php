@extends('admin.layouts.app')

@section('title', 'Edit Job')
@section('subtitle', 'Update existing job post details.')

@section('content')
<div class="max-w-3xl admin-surface rounded-xl p-5 admin-fade-up">
    <form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Employer</label>
            <select name="user_id" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
                @foreach($employers as $employer)
                <option value="{{ $employer->id }}" {{ (int) old('user_id', $job->user_id) === (int) $employer->id ? 'selected' : '' }}>
                    {{ trim(($employer->first_name ?? '') . ' ' . ($employer->last_name ?? '')) }} ({{ $employer->email }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $job->title) }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" rows="5" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>{{ old('description', $job->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Salary Min</label>
                <input type="number" step="0.01" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Salary Max</label>
                <input type="number" step="0.01" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Location</label>
            <input type="text" name="location" value="{{ old('location', $job->location) }}" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);" required>
                <option value="open" {{ old('status', $job->status === 'active' ? 'open' : 'closed') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ old('status', $job->status === 'active' ? 'open' : 'closed') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Update Job</button>
            <a href="{{ route('admin.jobs.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Cancel</a>
        </div>
    </form>
</div>
@endsection