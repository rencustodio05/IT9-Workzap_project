@extends('layouts.jobseeker')

@section('title', 'Browse Jobs')

@section('content')

<div class="max-w-6xl mx-auto py-8">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Browse Jobs</h1>
        <p class="text-gray-600">Find jobs that match your skills</p>
    </div>

    <!-- SEARCH -->
    <form method="GET"
        action="{{ route('jobseeker.jobs.index') }}"
        class="bg-white p-4 rounded-lg shadow mb-6 flex flex-col md:flex-row gap-3 relative">

        <!-- SEARCH INPUT -->
        <div class="relative w-full">

            <input type="text"
                id="searchInput"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search job title..."
                autocomplete="off"
                class="border rounded px-3 py-2 w-full">

            <div id="suggestBox"
                class="absolute bg-white border w-full mt-1 rounded shadow hidden z-50">
            </div>

        </div>

        <!-- LOCATION -->
        <input type="text"
            name="location"
            value="{{ request('location') }}"
            placeholder="Location"
            class="border rounded px-3 py-2">

        <!-- TYPE -->
        <select name="type" class="border rounded px-3 py-2">
            <option value="">Job Type</option>
            <option value="full-time" {{ request('type')=='full-time'?'selected':'' }}>Full Time</option>
            <option value="part-time" {{ request('type')=='part-time'?'selected':'' }}>Part Time</option>
        </select>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Search
        </button>

        <a href="{{ route('jobseeker.jobs.index') }}"
            class="bg-gray-200 px-4 py-2 rounded text-center">
            Reset
        </a>

    </form>

    <!-- TABLE -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">

        <table class="min-w-full text-left whitespace-nowrap">

            <thead class="bg-gray-50 border-b">
                <tr class="text-gray-600 text-sm">
                    <th class="py-4 px-6">Job Title</th>
                    <th class="py-4 px-6">Address</th>
                    <th class="py-4 px-6">Type</th>
                    <th class="py-4 px-6">Salary</th>
                    <th class="py-4 px-6">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($jobs as $job)

                <tr class="border-t hover:bg-gray-50">

                    <!-- TITLE -->
                    <td class="py-4 px-6 font-semibold">
                        {{ $job->title }}
                        <div class="text-xs text-gray-500">
                            {{ $job->employer->name ?? 'Employer' }}
                        </div>
                    </td>

                    <!-- LOCATION -->
                    <td class="py-4 px-6 text-sm text-gray-600">
                        📍 {{ $job->location ?? 'No location' }}
                    </td>

                    <!-- TYPE -->
                    <td class="py-4 px-6">
                        @php
                        $types = explode(',', $job->type ?? 'full-time');
                        @endphp

                        @foreach($types as $type)
                        <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                            {{ ucfirst(trim($type)) }}
                        </span>
                        @endforeach
                    </td>

                    <!-- SALARY -->
                    <td class="py-4 px-6 text-sm">
                        ₱{{ number_format($job->salary_min ?? 0) }}
                        -
                        ₱{{ number_format($job->salary_max ?? 0) }}
                    </td>

                    <!-- ACTION -->
                    <td class="py-4 px-6 flex gap-2">

                        <a href="{{ route('jobseeker.jobs.show', $job->id) }}"
                            class="px-3 py-1 border rounded text-gray-700 hover:bg-gray-100">
                            View
                        </a>

                        @php
                        $applied = in_array($job->id, $appliedJobs ?? []);
                        @endphp

                        @if($applied)

                        <button disabled
                            class="px-3 py-1 bg-gray-300 text-gray-600 rounded">
                            Applied
                        </button>

                        @else

                        <form action="{{ route('jobseeker.apply', $job->id) }}"
                            method="POST">
                            @csrf
                            <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Apply
                            </button>
                        </form>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">
                        No jobs available
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection


@push('scripts')
<script>
    const input = document.getElementById('searchInput');
    const box = document.getElementById('suggestBox');

    let timer;

    input.addEventListener('input', function() {

        clearTimeout(timer);

        const query = this.value;

        if (query.length < 2) {
            box.classList.add('hidden');
            return;
        }

        timer = setTimeout(() => {

            fetch("{{ route('jobseeker.jobs.suggest') }}?q=" + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {

                    box.innerHTML = '';

                    if (!data.length) {
                        box.classList.add('hidden');
                        return;
                    }

                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.textContent = item;
                        div.className = "p-2 hover:bg-gray-100 cursor-pointer";

                        div.onclick = function() {
                            input.value = item;
                            box.classList.add('hidden');
                            input.form.submit(); // auto search
                        };

                        box.appendChild(div);
                    });

                    box.classList.remove('hidden');

                });

        }, 300);
    });

    // close dropdown
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !box.contains(e.target)) {
            box.classList.add('hidden');
        }
    });
</script>
@endpush