@extends('admin.layouts.app')

@section('title', 'Subscription Requests')
@section('subtitle', 'Review and approve employer subscriptions to control platform access.')

@section('content')
<div class="admin-surface rounded-xl p-5 admin-fade-up">
    <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-4">
            <label class="block text-xs mb-1" style="color: var(--admin-muted);">Status</label>
            <select name="status" class="w-full rounded-lg border px-3 py-2 bg-white" style="border-color: var(--admin-border);">
                <option value="">All statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>

        <div class="md:col-span-8 flex items-end gap-2 justify-end">
            <a href="{{ route('admin.subscriptions.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Reset</a>
            <button type="submit" class="admin-button-primary text-white rounded-lg px-4 py-2 text-sm font-semibold">Apply</button>
        </div>
    </form>
</div>

<div class="admin-surface rounded-xl p-5 admin-fade-up overflow-x-auto">
    <table class="admin-table min-w-full text-sm">
        <thead>
            <tr>
                <th class="py-3 pr-4 text-left">Employer</th>
                <th class="py-3 pr-4 text-left">Plan</th>
                <th class="py-3 pr-4 text-left">Price</th>
                <th class="py-3 pr-4 text-left">Start</th>
                <th class="py-3 pr-4 text-left">End</th>
                <th class="py-3 pr-4 text-left">Status</th>
                <th class="py-3 pr-4 text-left">Approved By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscriptions as $subscription)
            <tr>
                <td class="py-3 pr-4 font-semibold">
                    {{ trim((optional($subscription->employer)->first_name ?? '') . ' ' . (optional($subscription->employer)->last_name ?? '')) ?: 'N/A' }}
                    <div class="text-xs" style="color: var(--admin-muted);">{{ optional($subscription->employer)->email ?? 'N/A' }}</div>
                </td>
                <td class="py-3 pr-4">{{ ucfirst($subscription->plan_type) }}</td>
                <td class="py-3 pr-4">${{ number_format((float) $subscription->price, 2) }}</td>
                <td class="py-3 pr-4">{{ optional($subscription->start_date)->format('M d, Y') ?? 'N/A' }}</td>
                <td class="py-3 pr-4">{{ optional($subscription->end_date)->format('M d, Y') ?? 'N/A' }}</td>
                <td class="py-3 pr-4">
                    <span class="admin-chip rounded-full px-2.5 py-1 text-xs {{ $subscription->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($subscription->status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($subscription->status) }}
                    </span>
                </td>
                <td class="py-3 pr-4">
                    {{ trim((optional($subscription->approvedBy)->first_name ?? '') . ' ' . (optional($subscription->approvedBy)->last_name ?? '')) ?: 'N/A' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-8 text-center" style="color: var(--admin-muted);">No subscription requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection