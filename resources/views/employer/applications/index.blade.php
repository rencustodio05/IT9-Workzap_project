@extends('layouts.employer')

@section('title', 'Applicant Management')
@section('subtitle', 'Review and manage all incoming applicants.')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Applicant Management</h1>
            <p class="text-gray-500 mt-1">Review and manage all your applicants.</p>
        </div>
    </div>

    <!-- SEARCH / FILTER -->
    <form method="GET" action="{{ route('applications.index') }}"
        class="bg-white rounded-lg p-4 shadow flex flex-col md:flex-row gap-4 mb-6">

        <!-- SEARCH APPLICANT -->
        <div id="applicant-search-wrapper" class="relative flex-1">
            <input type="text"
                id="applicant-search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search applicant name..."
                autocomplete="off"
                class="border rounded px-3 py-2 w-full" />

            <ul id="applicant-suggestions"
                class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded shadow max-h-56 overflow-y-auto"
                style="display:none;"></ul>
        </div>


        <!-- STATUS -->
        <select name="status" class="border rounded px-3 py-2">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="interview" {{ request('status')=='interview'?'selected':'' }}>Interview</option>
            <option value="hired" {{ request('status')=='hired'?'selected':'' }}>Hired</option>
            <option value="fired" {{ request('status')=='fired'?'selected':'' }}>Fired</option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
        </select>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Search
        </button>

    </form>
    <!-- FILTER BUTTONS -->


    <!-- TABLE -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-left whitespace-nowrap">

            <thead>
                <tr class="text-gray-500 text-sm border-b">
                    <th class="py-4 px-6">Applicant</th>
                    <th class="py-4 px-6">Applied for</th>
                    <th class="py-4 px-6">Date applied</th>
                    <th class="py-4 px-6">Date Hired</th>
                    <th class="py-4 px-6">Date Fired</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-900">

                @forelse($applications as $app)

                <tr class="border-t">

                    <!-- APPLICANT -->
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">

                            <div class="rounded-full bg-gray-200 w-8 h-8 flex items-center justify-center text-gray-600">
                                👤
                            </div>

                            <div>
                                <div class="font-bold">
                                    {{ trim(($app->jobseeker->first_name ?? '') . ' ' . ($app->jobseeker->last_name ?? '')) ?: 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $app->jobseeker->email ?? 'N/A' }}
                                </div>
                            </div>

                        </div>
                    </td>

                    <!-- JOB -->
                    <td class="py-4 px-6">
                        {{ $app->job->title ?? 'N/A' }}
                    </td>

                    <!-- DATE -->
                    <td class="py-4 px-6">
                        {{ $app->created_at->format('M d, Y') }}
                    </td>

                    <!-- DATE HIRED -->
                    <td class="py-4 px-6 text-sm text-gray-900">
                        {{ $app->hired_at ? \Carbon\Carbon::parse($app->hired_at)->format('M d, Y') : 'N/A' }}
                    </td>

                    <!-- DATE FIRED -->
                    <td class="py-4 px-6 text-sm text-gray-900">
                        {{ $app->fired_at ? \Carbon\Carbon::parse($app->fired_at)->format('M d, Y') : 'N/A' }}
                    </td>

                    <!-- STATUS -->
                    <td class="py-4 px-6">
                        <span
                            class="status-badge px-3 py-1 rounded-full text-xs
        @if($app->status == 'pending') bg-yellow-100 text-yellow-800
        @elseif($app->status == 'interview') bg-blue-100 text-blue-800
        @elseif($app->status == 'hired') bg-green-100 text-green-800
        @elseif($app->status == 'fired') bg-gray-200 text-gray-800
        @else bg-red-100 text-red-800
        @endif"
                            data-id="{{ $app->id }}"
                            data-status="{{ $app->status }}">
                            {{ $app->status ? ucfirst($app->status) : 'N/A' }}
                        </span>
                    </td>

                    <!-- ACTION -->
                    <td class="py-4 px-6 flex gap-2">

                        <a href="{{ route('applications.show', $app->id) }}"
                            class="px-4 py-1 rounded border font-medium text-gray-800 hover:bg-gray-100">
                            View
                        </a>



                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        No applicants yet
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('applicant-search');
        const suggestionsBox = document.getElementById('applicant-suggestions');
        const searchWrapper = document.getElementById('applicant-search-wrapper');

        if (!searchInput || !suggestionsBox || !searchWrapper) {
            return;
        }

        const hideSuggestions = () => {
            suggestionsBox.style.display = 'none';
            suggestionsBox.innerHTML = '';
        };

        document.addEventListener('click', (event) => {
            if (!searchWrapper.contains(event.target)) {
                hideSuggestions();
            }
        });
    });
</script>
@endsection