@extends('layouts.employer')

@section('title', 'My Job Postings')
@section('subtitle', 'Manage your active and closed job listings.')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6">
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Job Postings</h1>
                <p class="text-sm text-slate-500 mt-1">Manage all your active and closed listings in one place.</p>
            </div>

            <a href="{{ route('jobs.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition">
                + Post a Job
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sm:p-5">
        <form method="GET" action="{{ route('jobs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search job title..."
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            </div>

            <div>
                <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="closed" {{ request('status') == 'closed' || request('status') == 'inactive' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div>
                <select name="date" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                    <option value="">Latest</option>
                    <option value="latest" {{ request('date') == 'latest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('date') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>

            <div class="md:col-span-4">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('jobs.index') }}"
            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition {{ !request('status') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100' }}">
            All ({{ $jobs->total() }})
        </a>

        <a href="{{ route('jobs.index', ['status' => 'active']) }}"
            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'active' ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Active
        </a>

        <a href="{{ route('jobs.index', ['status' => 'closed']) }}"
            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'closed' || request('status') == 'inactive' ? 'bg-slate-200 text-slate-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Closed
        </a>
    </div>

    <div class="space-y-3">
        @forelse($jobs as $job)
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sm:p-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-900 truncate">{{ $job->title }}</h3>

                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-slate-500">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $job->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                            {{ ucfirst($job->status) }}
                        </span>
                        <span>{{ $job->location }}</span>
                        <span>{{ $job->views }} views</span>
                        <span>{{ $job->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center px-3 py-2 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                        View
                    </a>

                    <a href="{{ route('jobs.edit', $job->id) }}" class="inline-flex items-center px-3 py-2 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                        Edit
                    </a>

                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition"
                            onclick="return confirm('Delete this job?')">
                            Delete
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