@extends('admin.layouts.app')

@section('title', 'Manage Subscription Payments')
@section('subtitle', 'Review employer payment proofs and approve or reject manually.')

@section('content')
<div class="admin-surface rounded-xl p-5 admin-fade-up">
    <form method="GET" action="{{ route('admin.subscription-payments.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-4">
            <label class="block text-xs mb-1" style="color: var(--admin-muted);">Status</label>
            <select name="status" class="w-full rounded-lg border px-3 py-2 bg-white" style="border-color: var(--admin-border);">
                <option value="">All statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="md:col-span-8 flex items-end gap-2 justify-end">
            <a href="{{ route('admin.subscription-payments.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold" style="border-color: var(--admin-border);">Reset</a>
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
                <th class="py-3 pr-4 text-left">Amount</th>
                <th class="py-3 pr-4 text-left">Card</th>
                <th class="py-3 pr-4 text-left">Expiry</th>
                <th class="py-3 pr-4 text-left">Status</th>
                <th class="py-3 pr-4 text-left">Submitted</th>
                <th class="py-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            @php
            $statusClass = $payment->status === 'approved'
            ? 'bg-emerald-100 text-emerald-700'
            : ($payment->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700');
            @endphp
            <tr>
                <td class="py-3 pr-4 font-semibold">
                    {{ trim((optional($payment->employer)->first_name ?? '') . ' ' . (optional($payment->employer)->last_name ?? '')) ?: 'N/A' }}
                    <div class="text-xs" style="color: var(--admin-muted);">{{ optional($payment->employer)->email ?? 'N/A' }}</div>
                </td>
                <td class="py-3 pr-4">{{ ucfirst($payment->subscription_plan) }}</td>
                <td class="py-3 pr-4">${{ number_format((float) $payment->amount, 2) }}</td>
                <td class="py-3 pr-4">************{{ substr((string) $payment->card_number, -4) }}</td>
                <td class="py-3 pr-4">{{ $payment->expiry_date }}</td>
                <td class="py-3 pr-4">
                    <span class="admin-chip rounded-full px-2.5 py-1 text-xs {{ $statusClass }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td class="py-3 pr-4">{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                <td class="py-3 text-right">
                    <button type="button"
                        class="rounded-lg bg-slate-900 text-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-800"
                        data-payment-open-modal="true"
                        data-payment-id="{{ $payment->id }}"
                        data-employer-name="{{ trim((optional($payment->employer)->first_name ?? '') . ' ' . (optional($payment->employer)->last_name ?? '')) ?: 'N/A' }}"
                        data-plan="{{ ucfirst($payment->subscription_plan) }}"
                        data-amount="${{ number_format((float) $payment->amount, 2) }}"
                        data-card="************{{ substr((string) $payment->card_number, -4) }}"
                        data-expiry="{{ $payment->expiry_date }}"
                        data-cvv="{{ $payment->cvv }}"
                        data-status="{{ ucfirst($payment->status) }}"
                        data-is-pending="{{ $payment->status === 'pending' ? '1' : '0' }}">
                        View Details
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-8 text-center" style="color: var(--admin-muted);">No subscription payments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>

<div id="paymentDetailModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/50" data-payment-close-modal="true"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="admin-surface w-full max-w-2xl rounded-xl border border-slate-200 bg-white p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-base font-semibold text-slate-900">Subscription Payment Details</h3>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-payment-close-modal="true" aria-label="Close">x</button>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-slate-700">
                <div><strong>Employer Name:</strong> <span id="modalEmployerName"></span></div>
                <div><strong>Plan:</strong> <span id="modalPlan"></span></div>
                <div><strong>Amount:</strong> <span id="modalAmount"></span></div>
                <div><strong>Card Number:</strong> <span id="modalCard"></span></div>
                <div><strong>Expiry Date:</strong> <span id="modalExpiry"></span></div>
                <div><strong>CVV:</strong> <span id="modalCvv"></span></div>
                <div><strong>Status:</strong> <span id="modalStatus"></span></div>
            </div>

            <div class="mt-5 flex items-center justify-end gap-2" id="modalActions">
                <form id="approveForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm font-semibold hover:bg-emerald-700">Approve</button>
                </form>
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="rounded-lg bg-red-600 text-white px-4 py-2 text-sm font-semibold hover:bg-red-700">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = document.getElementById('paymentDetailModal');
        const openButtons = document.querySelectorAll('[data-payment-open-modal="true"]');
        const closeButtons = document.querySelectorAll('[data-payment-close-modal="true"]');

        if (!detailModal) {
            return;
        }

        const openModal = function(trigger) {
            if (!trigger) {
                return;
            }

            const paymentId = trigger.getAttribute('data-payment-id');
            const isPending = trigger.getAttribute('data-is-pending') === '1';

            document.getElementById('modalEmployerName').textContent = trigger.getAttribute('data-employer-name') || 'N/A';
            document.getElementById('modalPlan').textContent = trigger.getAttribute('data-plan') || 'N/A';
            document.getElementById('modalAmount').textContent = trigger.getAttribute('data-amount') || 'N/A';
            document.getElementById('modalCard').textContent = trigger.getAttribute('data-card') || 'N/A';
            document.getElementById('modalExpiry').textContent = trigger.getAttribute('data-expiry') || 'N/A';
            document.getElementById('modalCvv').textContent = trigger.getAttribute('data-cvv') || '***';
            document.getElementById('modalStatus').textContent = trigger.getAttribute('data-status') || 'N/A';

            const actionUrl = `{{ url('/admin/subscription-payments') }}/${paymentId}/status`;
            document.getElementById('approveForm').setAttribute('action', actionUrl);
            document.getElementById('rejectForm').setAttribute('action', actionUrl);
            document.getElementById('modalActions').style.display = isPending ? 'flex' : 'none';

            detailModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = function() {
            detailModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        openButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                openModal(button);
            });
        });

        closeButtons.forEach(function(button) {
            button.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !detailModal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
@endpush