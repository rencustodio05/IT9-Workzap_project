@extends('admin.layouts.app')

@section('title', 'Employers')
@section('subtitle', 'Review employer accounts and activity.')

@section('content')
<div class="admin-surface rounded-xl p-5 admin-fade-up">
    <form method="GET" action="{{ route('admin.employers.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-9">
            <label for="q" class="block text-xs mb-1" style="color: var(--admin-muted);">Search</label>
            <input id="q" name="q" type="text" value="{{ $search }}" placeholder="Search name, username, email" class="w-full rounded-lg border px-3 py-2" style="border-color: var(--admin-border);">
        </div>
        <div class="md:col-span-3 flex items-end gap-2">
            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Apply</button>
            <a href="{{ route('admin.employers.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Reset</a>
        </div>
    </form>
</div>

<div class="admin-surface rounded-xl p-5 admin-fade-up overflow-x-auto">
    @if($employers->count())
    <table class="admin-table min-w-full text-sm">
        <thead>
            <tr>
                <th class="py-3 pr-4">Name</th>
                <th class="py-3 pr-4">Email</th>
                <th class="py-3 pr-4">Username</th>
                <th class="py-3 pr-4">Jobs Posted</th>
                <th class="py-3 pr-4">Account</th>
                <th class="py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employers as $employer)
            <tr>
                <td class="py-3 pr-4 font-semibold">{{ trim(($employer->first_name ?? '') . ' ' . ($employer->last_name ?? '')) ?: 'N/A' }}</td>
                <td class="py-3 pr-4">{{ $employer->email }}</td>
                <td class="py-3 pr-4">{{ $employer->username ?? 'N/A' }}</td>
                <td class="py-3 pr-4">{{ $employer->jobs_count }}</td>
                <td class="py-3 pr-4">
                    <span class="rounded-full px-2.5 py-1 text-xs {{ $employer->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $employer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="py-3 text-right">
                    <a href="{{ route('admin.employers.show', $employer) }}" class="rounded-lg border px-3 py-1.5 text-xs font-semibold" style="border-color: var(--admin-border);">View Profile</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $employers->links() }}</div>
    @else
    @include('admin.components.empty-state', ['title' => 'No employers found', 'message' => 'Employer records will appear here.'])
    @endif
</div>
@endsection