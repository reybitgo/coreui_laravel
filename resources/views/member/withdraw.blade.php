@extends('layouts.admin')

@section('title', 'Withdraw Funds')

@section('content')
<!-- Page Header -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-minus') }}"></use>
                    </svg>
                    Withdraw Funds
                </h4>
                <p class="text-body-secondary mb-0">Transfer money from your e-wallet to your bank account</p>
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
        <div class="card bg-danger-gradient text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Available Balance</h5>
                <h2 class="display-4 fw-bold">${{ number_format($wallet->balance, 2) }}</h2>
                <p class="mb-0">
                    <span class="badge {{ $wallet->is_active ? 'bg-light text-success' : 'bg-warning text-dark' }}">
                        {{ $wallet->is_active ? 'Account Active' : 'Account Frozen' }}
                    </span>
                </p>
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
                    @foreach([25, 50, 100, 250, 500, 1000] as $quickAmount)
                        @if($wallet->balance >= $quickAmount)
                            <button type="button" class="btn btn-outline-danger" onclick="setAmount({{ $quickAmount }})">
                                ${{ $quickAmount }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-check') }}"></use>
        </svg>
        {{ session('success') }}
        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-x') }}"></use>
        </svg>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
    </div>
@endif

<!-- Withdrawal Form -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-minus') }}"></use>
                </svg>
                <strong>Withdrawal Form</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('wallet.withdraw.process') }}" id="withdraw-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-dollar') }}"></use>
                                    </svg>
                                    Withdrawal Amount
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                           placeholder="0.00" min="1" max="{{ min($wallet->balance, 10000) }}" step="0.01" required
                                           value="{{ old('amount') }}">
                                    <span class="input-group-text">USD</span>
                                </div>
                                <div class="form-text">
                                    Minimum: $1.00 | Maximum: ${{ number_format(min($wallet->balance, 10000), 2) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bank_name" class="form-label">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-bank') }}"></use>
                                    </svg>
                                    Bank Name
                                </label>
                                <select id="bank_name" name="bank_name" class="form-select" required>
                                    <option value="">Select your bank</option>
                                    <option value="chase" {{ old('bank_name') == 'chase' ? 'selected' : '' }}>Chase Bank</option>
                                    <option value="bofa" {{ old('bank_name') == 'bofa' ? 'selected' : '' }}>Bank of America</option>
                                    <option value="wells_fargo" {{ old('bank_name') == 'wells_fargo' ? 'selected' : '' }}>Wells Fargo</option>
                                    <option value="citibank" {{ old('bank_name') == 'citibank' ? 'selected' : '' }}>Citibank</option>
                                    <option value="us_bank" {{ old('bank_name') == 'us_bank' ? 'selected' : '' }}>US Bank</option>
                                    <option value="pnc" {{ old('bank_name') == 'pnc' ? 'selected' : '' }}>PNC Bank</option>
                                    <option value="capital_one" {{ old('bank_name') == 'capital_one' ? 'selected' : '' }}>Capital One</option>
                                    <option value="td_bank" {{ old('bank_name') == 'td_bank' ? 'selected' : '' }}>TD Bank</option>
                                    <option value="other" {{ old('bank_name') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div class="form-text">
                                    Select your bank for faster processing
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bank_account" class="form-label">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-credit-card') }}"></use>
                                    </svg>
                                    Bank Account Number
                                </label>
                                <input type="text" name="bank_account" id="bank_account" class="form-control"
                                       placeholder="1234567890123456" minlength="10" maxlength="20" required
                                       value="{{ old('bank_account') }}">
                                <div class="form-text">
                                    Enter your bank account number (10-20 digits)
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="routing_number" class="form-label">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-sort-numeric-down') }}"></use>
                                    </svg>
                                    Routing Number (Optional)
                                </label>
                                <input type="text" name="routing_number" id="routing_number" class="form-control"
                                       placeholder="123456789" maxlength="9"
                                       value="{{ old('routing_number') }}">
                                <div class="form-text">
                                    9-digit routing number for faster processing (optional)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Withdrawal Summary -->
                    <div id="withdrawal-fee-info" class="card bg-info-subtle border-info mb-3 d-none">
                        <div class="card-body">
                            <h6 class="card-title">
                                <svg class="icon me-2">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-calculator') }}"></use>
                                </svg>
                                Withdrawal Summary
                            </h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-body-secondary small">Withdrawal Amount</div>
                                    <div class="fw-bold" id="withdrawal-amount-display">$0.00</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-body-secondary small">Processing Fee</div>
                                    <div class="fw-bold text-warning" id="withdrawal-fee-display">$0.00</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-body-secondary small">Total Deducted</div>
                                    <div class="fw-bold text-danger" id="total-deducted-display">$0.00</div>
                                </div>
                            </div>
                            <hr>
                            <p class="small mb-0 text-info">
                                <svg class="icon me-1">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-info') }}"></use>
                                </svg>
                                <strong>Note:</strong> Processing fee is non-refundable and deducted immediately.
                            </p>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-warning') }}"></use>
                            </svg>
                            Important Information
                        </h6>
                        <ul class="mb-0">
                            <li>Withdrawals require admin approval and typically take 1-3 business days to process</li>
                            <li>Please verify your bank account details are correct to avoid delays</li>
                            <li>Funds will be deducted from your wallet immediately upon submission</li>
                            <li>Processing fees may apply based on your bank and withdrawal amount</li>
                        </ul>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                        <label class="form-check-label" for="agree_terms">
                            <strong>I confirm and authorize this withdrawal</strong>
                        </label>
                        <div class="form-text">
                            I confirm that the bank account details are correct and authorize this withdrawal from my e-wallet.
                            I understand that this transaction cannot be reversed once processed.
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-danger btn-lg flex-md-fill"
                                {{ !$wallet->is_active || $wallet->balance <= 0 ? 'disabled' : '' }}>
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-minus') }}"></use>
                            </svg>
                            Submit Withdrawal Request
                        </button>
                        <a href="{{ route('wallet.transactions') }}" class="btn btn-outline-secondary">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                            </svg>
                            View Transactions
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Withdrawal fee settings from backend
    const withdrawalSettings = @json($withdrawalSettings);

    function setAmount(amount) {
        document.getElementById('amount').value = amount;
        updateWithdrawalSummary();
    }

    function calculateWithdrawalFee(amount) {
        if (!withdrawalSettings.fee_enabled || amount <= 0) {
            return 0;
        }

        let fee = 0;
        if (withdrawalSettings.fee_type === 'percentage') {
            fee = (amount * withdrawalSettings.fee_value) / 100;
        } else {
            fee = parseFloat(withdrawalSettings.fee_value);
        }

        // Apply min/max limits
        fee = Math.max(fee, parseFloat(withdrawalSettings.minimum_fee));
        fee = Math.min(fee, parseFloat(withdrawalSettings.maximum_fee));

        return Math.round(fee * 100) / 100; // Round to 2 decimal places
    }

    function updateWithdrawalSummary() {
        const amountInput = document.getElementById('amount');
        const amount = parseFloat(amountInput.value) || 0;

        if (amount > 0) {
            const fee = calculateWithdrawalFee(amount);
            const total = amount + fee;

            document.getElementById('withdrawal-amount-display').textContent = '$' + amount.toFixed(2);
            document.getElementById('withdrawal-fee-display').textContent = '$' + fee.toFixed(2);
            document.getElementById('total-deducted-display').textContent = '$' + total.toFixed(2);
            document.getElementById('withdrawal-fee-info').classList.remove('d-none');
        } else {
            document.getElementById('withdrawal-fee-info').classList.add('d-none');
        }
    }

    // Add event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        amountInput.addEventListener('input', updateWithdrawalSummary);
        amountInput.addEventListener('change', updateWithdrawalSummary);
    });
</script>
@endpush

@endsection