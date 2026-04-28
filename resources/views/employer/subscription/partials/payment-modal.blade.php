<div id="subscriptionPaymentModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/50" data-subscription-close-modal="true"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="admin-surface w-full max-w-lg rounded-xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900">Subscribe to Post Jobs</h3>
                <button type="button" class="text-slate-500 hover:text-slate-700" data-subscription-close-modal="true" aria-label="Close">x</button>
            </div>

            <form method="POST" action="{{ route('employer.subscription-payments.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf

                <div>
                    <label for="subscription_plan" class="block text-sm font-medium text-slate-700">Subscription Plan</label>
                    <select id="subscription_plan" name="subscription_plan" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                        @foreach($plans as $planKey => $plan)
                        <option value="{{ $planKey }}" data-amount="{{ number_format((float) $plan['amount'], 2, '.', '') }}">{{ ucfirst($planKey) }} ({{ $plan['price'] }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="amount_display" class="block text-sm font-medium text-slate-700">Amount</label>
                    <input type="text" id="amount_display" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-slate-50" readonly>
                </div>

                <div>
                    <label for="card_number" class="block text-sm font-medium text-slate-700">Card Number</label>
                    <input type="text" id="card_number" name="card_number" maxlength="19" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Enter card number" required>
                    <span id="card_number_error" class="mt-1 hidden text-xs text-red-600">Card number must be 16 digits</span>
                </div>

                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-slate-700">Expiry Date (MM/YY)</label>
                    <input type="text" id="expiry_date" name="expiry_date" maxlength="5" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="MM/YY" required>
                </div>

                <div>
                    <label for="cvv" class="block text-sm font-medium text-slate-700">CVV</label>
                    <input type="text" id="cvv" name="cvv" maxlength="3" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Enter CVV" required>
                </div>

                @if($pendingPayment)
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-700">
                    You already have a pending payment request. Please wait for admin review.
                </div>
                @endif

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700" data-subscription-close-modal="true">Cancel</button>
                    <button type="submit" class="rounded-lg admin-button-primary px-4 py-2 text-sm font-semibold text-white disabled:opacity-60 disabled:cursor-not-allowed" @disabled($pendingPayment)>Submit Payment Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('subscriptionPaymentModal');
        const paymentForm = modal ? modal.querySelector('form') : null;
        const planSelect = document.getElementById('subscription_plan');
        const amountDisplay = document.getElementById('amount_display');
        const cardInput = document.getElementById('card_number');
        const cardError = document.getElementById('card_number_error');
        const expiryInput = document.getElementById('expiry_date');
        const cvvInput = document.getElementById('cvv');
        const openButtons = document.querySelectorAll('[data-subscription-open-modal="true"]');
        const closeButtons = document.querySelectorAll('[data-subscription-close-modal="true"]');

        if (!modal || !planSelect || !amountDisplay) {
            return;
        }

        const validateCardNumber = function() {
            if (!cardInput || !cardError) {
                return true;
            }

            const digits = cardInput.value.replace(/\D/g, '');
            const isValid = digits.length === 16;

            if (!isValid) {
                cardError.classList.remove('hidden');
            } else {
                cardError.classList.add('hidden');
            }

            return isValid;
        };

        const updateAmount = function() {
            const option = planSelect.options[planSelect.selectedIndex];
            amountDisplay.value = option ? option.getAttribute('data-amount') || '0.00' : '0.00';
        };

        const openModal = function(selectedPlan) {
            if (selectedPlan) {
                planSelect.value = selectedPlan;
            }

            updateAmount();
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = function() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        openButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                openModal(button.getAttribute('data-selected-plan'));
            });
        });

        closeButtons.forEach(function(button) {
            button.addEventListener('click', closeModal);
        });

        if (cardInput) {
            cardInput.addEventListener('input', function() {
                const digits = cardInput.value.replace(/\D/g, '').slice(0, 16);
                cardInput.value = digits.replace(/(.{4})/g, '$1 ').trim();

                if (digits.length === 16 && cardError) {
                    cardError.classList.add('hidden');
                }
            });

            cardInput.addEventListener('blur', validateCardNumber);
        }

        if (expiryInput) {
            expiryInput.addEventListener('input', function() {
                const digits = expiryInput.value.replace(/\D/g, '').slice(0, 4);
                let formatted = digits;

                if (digits.length >= 2) {
                    formatted = digits.slice(0, 2) + '/' + digits.slice(2);
                }

                expiryInput.value = formatted;
            });
        }

        if (cvvInput) {
            cvvInput.addEventListener('input', function() {
                cvvInput.value = cvvInput.value.replace(/\D/g, '').slice(0, 3);
            });
        }

        if (paymentForm) {
            paymentForm.addEventListener('submit', function(event) {
                if (!validateCardNumber()) {
                    event.preventDefault();
                    cardInput?.focus();
                }
            });
        }

        planSelect.addEventListener('change', updateAmount);

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        updateAmount();
    });
</script>
@endpush