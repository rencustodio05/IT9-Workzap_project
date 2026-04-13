@extends('layouts.employer')

@section('title', 'My Job Postings')

@vite(['resources/css/employer.css', 'resources/js/app.js'])

@section('content')

<div class="job-container">

    {{-- HEADER --}}
    <div class="job-header">
        <div>
            <h1>My job postings</h1>
            <p>Manage all your job listings here.</p>
        </div>

        <a href="{{ route('jobs.create') }}" class="btn-primary">
            + Post a job
        </a>
    </div>

    {{-- FILTERS --}}
    <div class="job-filters">

        <form method="GET" action="{{ route('jobs.index') }}" class="filter-form">

            {{-- SEARCH --}}
            <div class="search-wrapper">
                <input type="text"
                    id="jobSearch"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search job title..."
                    autocomplete="off">

                <div id="suggestions"></div>
            </div>

            {{-- STATUS --}}
            <select name="status">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            {{-- DATE --}}
            <select name="date">
                <option value="">Latest</option>
                <option value="latest" {{ request('date') == 'latest' ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ request('date') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>

            <button type="submit" class="filter-btn">Filter</button>

        </form>
    </div>

    {{-- TABS --}}
    <div class="job-tabs">

        <a href="{{ route('jobs.index') }}"
            class="tab {{ !request('status') ? 'active' : '' }}">
            All ({{ $jobs->total() }})
        </a>

        <a href="{{ route('jobs.index', ['status' => 'active']) }}"
            class="tab {{ request('status') == 'active' ? 'active' : '' }}">
            Active
        </a>

        <a href="{{ route('jobs.index', ['status' => 'closed']) }}"
            class="tab {{ request('status') == 'closed' ? 'active' : '' }}">
            Closed
        </a>

    </div>

    {{-- JOB LIST --}}
    @forelse($jobs as $job)

    <div class="job-card">

        <div>
            <h3>{{ $job->title }}</h3>

            <p>
                <span class="status">{{ ucfirst($job->status) }}</span> ·
                {{ $job->location }} ·
                {{ $job->views }} views ·
                {{ $job->created_at->format('M d, Y') }}
            </p>
        </div>

        <div class="actions">

            <a href="{{ route('jobs.show', $job->id) }}" class="btn-outline">
                View
            </a>

            <a href="{{ route('jobs.edit', $job->id) }}" class="btn-outline">
                Edit
            </a>

            <form action="{{ route('jobs.destroy', $job->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn-danger"
                    onclick="return confirm('Delete this job?')">
                    Delete
                </button>
            </form>

        </div>

    </div>

    @empty
    <div class="job-card">
        <p>No job postings yet.</p>
    </div>
    @endforelse

</div>

@endsection


{{-- ✅ SCRIPT RESTORED --}}
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        let input = document.getElementById('jobSearch');
        let box = document.getElementById('suggestions');

        if (!input) return;

        input.addEventListener('keyup', function() {

            let query = this.value;

            if (query.length < 1) {
                box.style.display = 'none';
                return;
            }

            fetch(`{{ route('jobs.suggest') }}?query=${query}`)
                .then(res => res.json())
                .then(data => {

                    box.innerHTML = '';

                    if (data.length > 0) {
                        box.style.display = 'block';

                        data.forEach(item => {
                            let div = document.createElement('div');
                            div.textContent = item;

                            div.onclick = function() {
                                input.value = item;
                                box.style.display = 'none';
                            };

                            box.appendChild(div);
                        });

                    } else {
                        box.style.display = 'none';
                    }
                });

        });

    });
</script>
@endpush