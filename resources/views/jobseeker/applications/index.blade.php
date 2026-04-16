@extends('layouts.jobseeker')
@section('title', 'My Applications')
@section('subtitle', 'Track your job applications and current status.')
@section('content')


@if(session('success'))
<div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
    {{ session('error') }}
</div>
@endif

<div class="admin-surface rounded-xl admin-fade-up overflow-x-auto">
    <table class="admin-table min-w-full text-sm text-left whitespace-nowrap">
        <thead class="bg-gray-50 border-b text-sm text-gray-600">
            <tr>
                <th class="py-3 px-4">Job</th>
                <th class="py-3 px-4">Applied At</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Interview</th>
                <th class="py-3 px-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
            <tr class="border-b" data-application-row="{{ $application->id }}">
                <td class="py-3 px-4 font-medium">{{ $application->job->title ?? 'N/A' }}</td>
                <td class="py-3 px-4 text-sm text-gray-600">{{ $application->created_at->format('M d, Y h:i A') }}</td>
                <td class="py-3 px-4">
                    <span class="px-2 py-1 text-xs rounded {{ $application->status === 'hired' ? 'bg-green-100 text-green-800' : ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : ($application->status === 'interview' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-600">
                    {{ $application->interview?->scheduled_at ? $application->interview->scheduled_at->format('M d, Y h:i A') : 'Not scheduled' }}
                </td>
                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('jobseeker.jobs.show', ['id' => $application->job_id, 'application_id' => $application->id, 'from' => 'applications']) }}" title="View" aria-label="View" class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 hover:bg-blue-50 transition">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                            <circle cx="12" cy="12" r="2.75" />
                        </svg>
                    </a>
                    @if(in_array($application->status, ['pending', 'interview']))
                    <form method="POST" action="{{ route('jobseeker.applications.update', $application->id) }}">
                        @csrf
                        @method('PUT')
                        <button class="px-3 py-1 bg-red-600 text-white rounded text-sm">Cancel</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('jobseeker.applications.destroy', $application->id) }}" class="js-delete-form" data-application-id="{{ $application->id }}" onsubmit="return confirm('Delete this application permanently?');">
                        @csrf
                        @method('DELETE')
                        <button title="Delete" aria-label="Delete" class="inline-flex items-center justify-center p-2 rounded-md text-red-600 hover:bg-red-50 transition">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5h15" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 7.5V6a1.5 1.5 0 0 1 1.5-1.5h1.5A1.5 1.5 0 0 1 14.25 6v1.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l.75 11.25a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5l.75-11.25" />
                            </svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-6 px-4 text-center text-gray-500">No applications found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $applications->links() }}
</div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.js-delete-form');

        const showToast = (message) => {
            const toast = document.createElement('div');
            toast.className = 'fixed right-6 bottom-6 z-50 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800 shadow-lg';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2500);
        };

        forms.forEach((form) => {
            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                const applicationId = form.dataset.applicationId;
                const row = document.querySelector('[data-application-row="' + applicationId + '"]');
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to delete item.');
                    }

                    if (row) {
                        row.remove();
                    }

                    showToast('Item deleted successfully');
                } catch (error) {
                    window.location.reload();
                }
            });
        });
    });
</script>
@endpush