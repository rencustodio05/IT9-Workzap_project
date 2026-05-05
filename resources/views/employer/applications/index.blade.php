@extends('layouts.employer')

@section('title', 'Applications')
@section('subtitle', 'Review and manage all incoming applicants.')

@section('content')
<div class="max-w-7xl mx-auto w-full min-w-0">
    <form method="GET" action="{{ route('employer.applications.index') }}"
        class="admin-surface rounded-xl p-4 flex flex-col md:flex-row gap-4 mb-6">

        <div id="applicant-search-wrapper" class="relative flex-1">
            <input type="text"
                id="applicant-search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search applicant name..."
                autocomplete="off"
                class="rounded-lg border px-3 py-2 w-full" />

            <ul id="applicant-suggestions"
                class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded shadow max-h-56 overflow-y-auto"
                style="display:none;"></ul>
        </div>

        <select name="status" class="rounded-lg border px-3 py-2">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="interview" {{ request('status')=='interview'?'selected':'' }}>Interview</option>
            <option value="hired" {{ request('status')=='hired'?'selected':'' }}>Hired</option>
            <option value="fired" {{ request('status')=='fired'?'selected':'' }}>Fired</option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
        </select>

        <button class="admin-button-primary text-white px-4 py-2 rounded">
            Search
        </button>
    </form>

    <div class="overflow-x-auto admin-surface rounded-xl w-full max-w-full">
        <table class="admin-table w-full min-w-[860px] table-auto text-sm text-left">
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
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="rounded-full bg-gray-200 w-8 h-8 flex items-center justify-center text-gray-600">
                                U
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold max-w-[180px] truncate">
                                    {{ trim(($app->jobseeker->first_name ?? '') . ' ' . ($app->jobseeker->last_name ?? '')) ?: 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500 max-w-[220px] truncate">
                                    {{ $app->jobseeker->email ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="py-4 px-6 max-w-[240px] truncate">
                        <span class="block truncate">{{ $app->job->title ?? 'N/A' }}</span>
                    </td>

                    <td class="py-4 px-6">
                        {{ $app->created_at->format('M d, Y') }}
                    </td>

                    <td class="py-4 px-6 text-sm text-gray-900">
                        {{ $app->hired_at ? \Carbon\Carbon::parse($app->hired_at)->format('M d, Y') : 'N/A' }}
                    </td>

                    <td class="py-4 px-6 text-sm text-gray-900">
                        {{ $app->fired_at ? \Carbon\Carbon::parse($app->fired_at)->format('M d, Y') : 'N/A' }}
                    </td>

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

                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="flex gap-2 items-center">
                            <a href="{{ $app->status === 'interview' ? route('employer.applications.decision', $app->id) : route('employer.applications.show', $app->id) }}"
                                data-stop-row-click
                                title="{{ $app->status === 'interview' ? 'Review decision' : 'View application' }}"
                                class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 hover:bg-blue-50 transition"
                                aria-label="{{ $app->status === 'interview' ? 'Review decision' : 'View application' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                                    <circle cx="12" cy="12" r="2.75" />
                                </svg>
                            </a>
                        </div>
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
        <div class="mt-4 px-6 pb-4">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('applicant-search');
        const suggestionsBox = document.getElementById('applicant-suggestions');
        const searchWrapper = document.getElementById('applicant-search-wrapper');
        const stopLinks = document.querySelectorAll('a[data-stop-row-click]');

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

        stopLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                event.stopPropagation();
            });
        });
    });
</script>
@endsection