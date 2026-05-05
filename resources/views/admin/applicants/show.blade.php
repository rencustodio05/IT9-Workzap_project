@extends('admin.layouts.app')

@section('title', 'Applicant Profile')
@section('subtitle', 'Inspect applicant activity and account status.')

@section('content')
<div class="space-y-6">
    <div class="admin-surface rounded-xl p-5 admin-fade-up">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Name</div>
                <div class="mt-1 font-semibold">{{ trim(($applicant->first_name ?? '') . ' ' . ($applicant->last_name ?? '')) ?: 'N/A' }}</div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Email</div>
                <div class="mt-1 font-semibold">{{ $applicant->email }}</div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Account Status</div>
                <div class="mt-1">
                    <span class="rounded-full px-2.5 py-1 text-xs {{ $applicant->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $applicant->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Total Applications</div>
                <div class="mt-1 font-semibold">{{ $applications->total() }}</div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide" style="color: var(--admin-muted);">Joined</div>
                <div class="mt-1 font-semibold">{{ optional($applicant->created_at)->format('M d, Y') }}</div>
            </div>
        </div>

        <div class="mt-5 flex items-center gap-2">
            <form method="POST" action="{{ route('admin.applicants.toggle-status', $applicant) }}" onsubmit="return confirm('Update applicant account status?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold {{ $applicant->is_active ? 'bg-red-600 text-white' : 'bg-emerald-600 text-white' }}">
                    {{ $applicant->is_active ? 'Deactivate Applicant' : 'Activate Applicant' }}
                </button>
            </form>
            <a href="{{ route('admin.applicants.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Back</a>
        </div>
    </div>

    <div class="admin-surface rounded-xl p-5 admin-fade-up overflow-x-auto">
        <h3 class="text-base font-semibold mb-3">Application History</h3>
        @if($applications->count())
        <table class="admin-table min-w-full text-sm">
            <thead>
                <tr>
                    <th class="py-3 pr-4">Job Title</th>
                    <th class="py-3 pr-4">Employer</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3">Applied</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td class="py-3 pr-4">{{ optional($application->job)->title ?? 'N/A' }}</td>
                    <td class="py-3 pr-4">{{ trim((optional($application->job->employer)->first_name ?? '') . ' ' . (optional($application->job->employer)->last_name ?? '')) ?: 'N/A' }}</td>
                    <td class="py-3 pr-4">
                        <span class="rounded-full px-2.5 py-1 text-xs {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($application->status === 'accepted' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </td>
                    <td class="py-3">{{ optional($application->created_at)->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $applications->links() }}</div>
        @else
        @include('admin.components.empty-state', ['title' => 'No applications', 'message' => 'This applicant has not applied to any jobs yet.'])
        @endif
    </div>
</div>
@endsection