@extends('layouts.employer')

@section('title', 'My Job Postings')
@section('subtitle', 'Manage your active and closed job listings.')

@section('content')
<div class="max-w-6xl mx-auto space-y-5">
    <div class="flex justify-end">
        <a href="{{ $canPostJobs ? route('employer.jobs.create') : route('employer.subscription.index') }}"
            class="inline-flex items-center px-4 py-2 admin-button-primary text-white rounded-lg hover:brightness-95 text-sm font-semibold transition">
            {{ $canPostJobs ? '+ Post a Job' : 'Subscribe to Post a Job' }}
        </a>
    </div>

    <div class="admin-surface rounded-xl p-4 sm:p-5">
        <form method="GET" action="{{ route('employer.jobs.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-5">
                <label class="block text-xs mb-1 text-slate-500">Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search job title..."
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs mb-1 text-slate-500">Status</label>
                <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="closed" {{ request('status') == 'closed' || request('status') == 'inactive' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs mb-1 text-slate-500">Sort</label>
                <select name="date" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                    <option value="">Latest</option>
                    <option value="latest" {{ request('date') == 'latest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('date') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>

            <div class="md:col-span-3 flex items-end justify-end gap-2">
                <a href="{{ route('employer.jobs.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Reset
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('employer.jobs.index') }}"
            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition {{ !request('status') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100' }}">
            All ({{ $jobs->total() }})
        </a>

        <a href="{{ route('employer.jobs.index', ['status' => 'active']) }}"
            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'active' ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Active
        </a>

        <a href="{{ route('employer.jobs.index', ['status' => 'closed']) }}"
            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'closed' || request('status') == 'inactive' ? 'bg-slate-200 text-slate-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Closed
        </a>
    </div>

    <div class="space-y-3">
        @forelse($jobs as $job)
        <div class="admin-surface rounded-xl p-4 sm:p-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-900 truncate">{{ $job->title }}</h3>

                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-slate-500">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $job->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                            {{ ucfirst($job->status) }}
                        </span>
                        <span>{{ $job->location }}</span>
                        <span>{{ $job->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('employer.jobs.show', $job->id) }}" title="View" aria-label="View" class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 hover:bg-blue-50 transition">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                            <circle cx="12" cy="12" r="2.75" />
                        </svg>
                    </a>

                    <a href="{{ route('employer.jobs.edit', $job->id) }}" title="Edit" aria-label="Edit" class="inline-flex items-center justify-center p-2 rounded-md text-amber-600 hover:bg-amber-50 transition">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.1 2.1 0 1 1 2.971 2.971L8.25 18.042l-4.5 1.125 1.125-4.5L16.862 3.487z" />
                        </svg>
                    </a>

                    <form action="{{ route('employer.jobs.destroy', $job->id) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('DELETE')

                        <button type="submit" title="Delete" aria-label="Delete" class="inline-flex items-center justify-center p-2 rounded-md text-red-600 hover:bg-red-50 transition"
                            onclick="return confirm('Delete this job?')">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5h15" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 7.5V6a1.5 1.5 0 0 1 1.5-1.5h1.5A1.5 1.5 0 0 1 14.25 6v1.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l.75 11.25a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5l.75-11.25" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white border border-dashed border-slate-300 rounded-xl p-8 text-center text-slate-500">
            No job postings yet.
        </div>
        @endforelse
    </div>

    @if(method_exists($jobs, 'links'))
    <div class="pt-2">
        {{ $jobs->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection