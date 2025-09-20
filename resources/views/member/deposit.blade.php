@extends('layouts.admin')

@section('title', 'Deposit Funds')

@section('content')
<!-- Page Header -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wallet') }}"></use>
                    </svg>
                    Deposit Funds
                </h4>
                <p class="text-body-secondary mb-0">Add money to your e-wallet</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-left') }}"></use>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Current Balance Card -->
<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <div class="card bg-primary-gradient text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Current Balance</h5>
                <h2 class="display-4 fw-bold">${{ number_format(auth()->user()->wallet ? auth()->user()->wallet->balance : 0, 2) }}</h2>
                <p class="mb-0">Available for withdrawal and transfers</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Amount Buttons -->
<div class="row mb-4">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg>
                <strong>Quick Amounts</strong>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <button type="button" class="btn btn-outline-primary" onclick="setAmount(25)">$25</button>
                    <button type="button" class="btn btn-outline-primary" onclick="setAmount(50)">$50</button>
                    <button type="button" class="btn btn-outline-primary" onclick="setAmount(100)">$100</button>
                    <button type="button" class="btn btn-outline-primary" onclick="setAmount(250)">$250</button>
                    <button type="button" class="btn btn-outline-primary" onclick="setAmount(500)">$500</button>
                    <button type="button" class="btn btn-outline-primary" onclick="setAmount(1000)">$1,000</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Form -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                </svg>
                <strong>Deposit Form</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('wallet.deposit.process') }}" id="deposit-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-dollar') }}"></use>
                                    </svg>
                                    Deposit Amount
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                           placeholder="0.00" min="1" max="10000" step="0.01" required
                                           value="{{ old('amount') }}">
                                    <span class="input-group-text">USD</span>
                                </div>
                                <div class="form-text">
                                    Minimum: $1.00 | Maximum: $10,000.00
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-credit-card') }}"></use>
                                    </svg>
                                    Payment Method
                                </label>
                                <select id="payment_method" name="payment_method" class="form-select" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                        Bank Transfer (3-5 business days)
                                    </option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>
                                        Credit Card (Instant)
                                    </option>
                                    <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>
                                        Debit Card (Instant)
                                    </option>
                                    <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>
                                        PayPal (Instant)
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-notes') }}"></use>
                            </svg>
                            Description (Optional)
                        </label>
                        <textarea id="description" name="description" class="form-control" rows="2"
                                  placeholder="Add a note for this deposit...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Fee Information -->
                    <div class="alert alert-warning d-flex align-items-start mb-3">
                        <svg class="icon me-2 flex-shrink-0">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-warning') }}"></use>
                        </svg>
                        <div>
                            <h6 class="alert-heading">Fee Information</h6>
                            <ul class="mb-0 small">
                                <li>Bank Transfer: No fees</li>
                                <li>Credit/Debit Card: 2.9% + $0.30</li>
                                <li>PayPal: 2.4% + $0.30</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="alert alert-info d-flex align-items-start mb-3">
                        <svg class="icon me-2 flex-shrink-0">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-shield-alt') }}"></use>
                        </svg>
                        <div>
                            <h6 class="alert-heading">Security & Processing</h6>
                            <p class="mb-1">All transactions are encrypted using industry-standard SSL security.</p>
                            <p class="mb-0 small">Your deposit will be reviewed and processed by our admin team. You'll receive an email confirmation once approved.</p>
                        </div>
                    </div>

                    <!-- Estimated Total -->
                    <div id="fee-calculation" class="card bg-light mb-3 d-none">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-body-secondary small">Deposit Amount</div>
                                    <div class="fw-bold" id="deposit-amount">$0.00</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-body-secondary small">Processing Fee</div>
                                    <div class="fw-bold text-warning" id="processing-fee">$0.00</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-body-secondary small">Total to Pay</div>
                                    <div class="fw-bold text-primary" id="total-amount">$0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-check') }}"></use>
                            </svg>
                            Submit Deposit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function setAmount(amount) {
    document.getElementById('amount').value = amount;
    calculateFees();
}

function calculateFees() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const paymentMethod = document.getElementById('payment_method').value;

    let fee = 0;

    if (paymentMethod === 'credit_card' || paymentMethod === 'debit_card') {
        fee = amount * 0.029 + 0.30;
    } else if (paymentMethod === 'paypal') {
        fee = amount * 0.024 + 0.30;
    }

    const total = amount + fee;

    if (amount > 0 && paymentMethod) {
        document.getElementById('fee-calculation').classList.remove('d-none');
        document.getElementById('deposit-amount').textContent = '$' + amount.toFixed(2);
        document.getElementById('processing-fee').textContent = '$' + fee.toFixed(2);
        document.getElementById('total-amount').textContent = '$' + total.toFixed(2);
    } else {
        document.getElementById('fee-calculation').classList.add('d-none');
    }
}

document.getElementById('amount').addEventListener('input', calculateFees);
document.getElementById('payment_method').addEventListener('change', calculateFees);
</script>
@endsection