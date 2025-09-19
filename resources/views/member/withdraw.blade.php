@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Withdraw Funds</h1>
                        <p class="mt-1 text-sm text-gray-600">Transfer money from your e-wallet to your bank account</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Current Balance Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Available Balance</dt>
                            <dd class="text-2xl font-bold text-gray-900">${{ number_format($wallet->balance, 2) }}</dd>
                        </dl>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $wallet->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $wallet->is_active ? 'Active' : 'Frozen' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawal Form -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('wallet.withdraw.process') }}">
                    @csrf
                    <div class="space-y-6">
                        <!-- Amount Section -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">
                                Withdrawal Amount
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="amount" id="amount"
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                       placeholder="0.00" min="1" max="{{ min($wallet->balance, 10000) }}" step="0.01" required
                                       value="{{ old('amount') }}">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">USD</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Minimum: $1.00 | Maximum: ${{ number_format(min($wallet->balance, 10000), 2) }}
                            </p>
                        </div>

                        <!-- Quick Amount Buttons -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Amounts</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach([25, 50, 100, 250] as $quickAmount)
                                    @if($wallet->balance >= $quickAmount)
                                        <button type="button" onclick="document.getElementById('amount').value = {{ $quickAmount }}"
                                                class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            ${{ $quickAmount }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Bank Details Section -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="bank_account" class="block text-sm font-medium text-gray-700">
                                    Bank Account Number
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="bank_account" id="bank_account"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           placeholder="1234567890123456" minlength="10" maxlength="20" required
                                           value="{{ old('bank_account') }}">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Enter your bank account number (10-20 digits)
                                </p>
                            </div>

                            <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700">
                                    Bank Name
                                </label>
                                <select id="bank_name" name="bank_name" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
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
                            </div>
                        </div>

                        <div>
                            <label for="routing_number" class="block text-sm font-medium text-gray-700">
                                Routing Number (Optional)
                            </label>
                            <div class="mt-1">
                                <input type="text" name="routing_number" id="routing_number"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       placeholder="123456789" maxlength="9"
                                       value="{{ old('routing_number') }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                9-digit routing number for faster processing (optional)
                            </p>
                        </div>

                        <!-- Withdrawal Summary -->
                        <div id="withdrawal-fee-info" class="bg-blue-50 border border-blue-200 rounded-md p-4 hidden">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Withdrawal Summary
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <div class="space-y-1">
                                            <div class="flex justify-between">
                                                <span>Withdrawal Amount:</span>
                                                <span id="withdrawal-amount-display">$0.00</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Processing Fee:</span>
                                                <span id="withdrawal-fee-display">$0.00</span>
                                            </div>
                                            <div class="flex justify-between font-medium border-t border-blue-200 pt-1">
                                                <span>Total Deducted:</span>
                                                <span id="total-deducted-display">$0.00</span>
                                            </div>
                                            <div class="text-xs text-blue-600 mt-2">
                                                <strong>Note:</strong> Processing fee is non-refundable and deducted immediately.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Important Notice -->
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Important Information
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Withdrawals require admin approval and typically take 1-3 business days to process</li>
                                            <li>Please verify your bank account details are correct to avoid delays</li>
                                            <li>Funds will be deducted from your wallet immediately upon submission</li>
                                            <li>Processing fees may apply based on your bank and withdrawal amount</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="agree_terms" name="agree_terms" type="checkbox" required
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="agree_terms" class="font-medium text-gray-700">
                                    I confirm and authorize this withdrawal
                                </label>
                                <p class="text-gray-500">
                                    I confirm that the bank account details are correct and authorize this withdrawal from my e-wallet.
                                    I understand that this transaction cannot be reversed once processed.
                                </p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ !$wallet->is_active || $wallet->balance <= 0 ? 'disabled' : '' }}>
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                                Submit Withdrawal Request
                            </button>
                            <a href="{{ route('wallet.transactions') }}"
                               class="flex-1 sm:flex-initial inline-flex items-center justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                View Transactions
                            </a>
                        </div>
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
            document.getElementById('withdrawal-fee-info').classList.remove('hidden');
        } else {
            document.getElementById('withdrawal-fee-info').classList.add('hidden');
        }
    }

    // Add event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        amountInput.addEventListener('input', updateWithdrawalSummary);
        amountInput.addEventListener('change', updateWithdrawalSummary);

        // Update quick amount buttons to use the new calculation
        const quickButtons = document.querySelectorAll('[onclick*="document.getElementById(\'amount\').value"]');
        quickButtons.forEach(button => {
            button.addEventListener('click', function() {
                setTimeout(updateWithdrawalSummary, 100); // Small delay to ensure value is set
            });
        });
    });
</script>
@endpush

@endsection