<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    use AuthorizesRequests;

    public function deposit()
    {
        $this->authorize('deposit_funds');

        return view('member.deposit');
    }

    public function processDeposit(Request $request)
    {
        $this->authorize('deposit_funds');

        $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
            'payment_method' => 'required|string|in:credit_card,bank_transfer,paypal',
        ]);

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'deposit',
            'amount' => $request->amount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'description' => 'Deposit via ' . $request->payment_method,
            'metadata' => [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        ]);

        session()->flash('success', 'Deposit request of $' . number_format($request->amount, 2) . ' has been submitted for approval. Reference: ' . $transaction->reference_number);

        return redirect()->route('wallet.transactions');
    }

    public function transfer()
    {
        $this->authorize('transfer_funds');

        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        // Get transfer charge settings for JavaScript
        $transferSettings = [
            'charge_enabled' => \App\Models\SystemSetting::get('transfer_charge_enabled', false),
            'charge_type' => \App\Models\SystemSetting::get('transfer_charge_type', 'percentage'),
            'charge_value' => \App\Models\SystemSetting::get('transfer_charge_value', 0),
            'minimum_charge' => \App\Models\SystemSetting::get('transfer_minimum_charge', 0),
            'maximum_charge' => \App\Models\SystemSetting::get('transfer_maximum_charge', 999999),
        ];

        return view('member.transfer', compact('wallet', 'transferSettings'));
    }

    public function processTransfer(Request $request)
    {
        $this->authorize('transfer_funds');

        $request->validate([
            'recipient_identifier' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:10000',
            'note' => 'nullable|string|max:255',
        ]);

        $sender = Auth::user();
        $senderWallet = $sender->getOrCreateWallet();

        // Find recipient by email or username
        $recipientIdentifier = $request->recipient_identifier;

        if (filter_var($recipientIdentifier, FILTER_VALIDATE_EMAIL)) {
            $recipient = \App\Models\User::where('email', $recipientIdentifier)->first();
        } else {
            $recipient = \App\Models\User::where('username', $recipientIdentifier)->first();
        }

        if (!$recipient) {
            return redirect()->back()->withErrors(['recipient_identifier' => 'Recipient not found. Please check the email or username.']);
        }

        // Check if trying to transfer to self
        if ($recipient->id === $sender->id) {
            return redirect()->back()->withErrors(['recipient_identifier' => 'You cannot transfer funds to yourself.']);
        }

        // Calculate transfer charge
        $transferAmount = $request->amount;
        $transferCharge = $this->calculateTransferCharge($transferAmount);
        $totalAmount = $transferAmount + $transferCharge;

        // Check if sender's wallet has sufficient balance (including charge)
        if ($senderWallet->balance < $totalAmount) {
            return redirect()->back()->withErrors(['amount' => 'Insufficient balance. You need $' . number_format($totalAmount, 2) . ' (Transfer: $' . number_format($transferAmount, 2) . ' + Fee: $' . number_format($transferCharge, 2) . '). Your current balance is $' . number_format($senderWallet->balance, 2)]);
        }

        // Check if sender's wallet is active
        if (!$senderWallet->is_active) {
            return redirect()->back()->withErrors(['general' => 'Your wallet is currently frozen. Please contact support.']);
        }

        try {
            \DB::transaction(function () use ($request, $sender, $senderWallet, $recipient, $transferAmount, $transferCharge, $totalAmount) {
                $recipientWallet = $recipient->getOrCreateWallet();

                // Create outgoing transaction for sender
                $outgoingTransaction = Transaction::create([
                    'user_id' => $sender->id,
                    'type' => 'transfer_out',
                    'amount' => $transferAmount,
                    'status' => 'approved', // Transfers are instant
                    'payment_method' => 'internal',
                    'description' => 'Transfer to ' . ($recipient->username ?: $recipient->email) . ($request->note ? ' - ' . $request->note : ''),
                    'metadata' => [
                        'recipient_id' => $recipient->id,
                        'recipient_email' => $recipient->email,
                        'recipient_username' => $recipient->username,
                        'transfer_charge' => $transferCharge,
                        'total_amount' => $totalAmount,
                        'note' => $request->note,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]
                ]);

                // Create incoming transaction for recipient
                $incomingTransaction = Transaction::create([
                    'user_id' => $recipient->id,
                    'type' => 'transfer_in',
                    'amount' => $transferAmount,
                    'status' => 'approved',
                    'payment_method' => 'internal',
                    'description' => 'Transfer from ' . ($sender->username ?: $sender->email) . ($request->note ? ' - ' . $request->note : ''),
                    'metadata' => [
                        'sender_id' => $sender->id,
                        'sender_email' => $sender->email,
                        'sender_username' => $sender->username,
                        'note' => $request->note,
                        'related_transaction_id' => $outgoingTransaction->id,
                    ]
                ]);

                // Create transfer charge transaction if applicable
                if ($transferCharge > 0) {
                    Transaction::create([
                        'user_id' => $sender->id,
                        'type' => 'transfer_charge',
                        'amount' => $transferCharge,
                        'status' => 'approved',
                        'payment_method' => 'internal',
                        'description' => 'Transfer fee for transaction to ' . ($recipient->username ?: $recipient->email),
                        'metadata' => [
                            'related_transaction_id' => $outgoingTransaction->id,
                            'transfer_amount' => $transferAmount,
                            'charge_type' => \App\Models\SystemSetting::get('transfer_charge_type', 'percentage'),
                            'charge_value' => \App\Models\SystemSetting::get('transfer_charge_value', 0),
                        ]
                    ]);
                }

                // Update reference numbers to link transactions
                $outgoingTransaction->update([
                    'metadata' => array_merge($outgoingTransaction->metadata ?? [], [
                        'related_transaction_id' => $incomingTransaction->id
                    ])
                ]);

                // Update wallet balances
                $senderWallet->decrement('balance', $totalAmount); // Deduct transfer amount + charge
                $senderWallet->update(['last_transaction_at' => now()]);

                $recipientWallet->increment('balance', $transferAmount); // Recipient gets only transfer amount
                $recipientWallet->update(['last_transaction_at' => now()]);
            });

            $message = 'Transfer of $' . number_format($transferAmount, 2) . ' to ' . ($recipient->username ?: $recipient->email) . ' completed successfully!';
            if ($transferCharge > 0) {
                $message .= ' (Transfer fee: $' . number_format($transferCharge, 2) . ' deducted)';
            }
            $message .= ' The funds have been transferred instantly.';
            session()->flash('success', $message);

            return redirect()->route('wallet.transactions');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Transfer failed. Please try again later.']);
        }
    }

    public function withdraw()
    {
        $this->authorize('withdraw_funds');

        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        // Get withdrawal fee settings for JavaScript
        $withdrawalSettings = [
            'fee_enabled' => \App\Models\SystemSetting::get('withdrawal_fee_enabled', false),
            'fee_type' => \App\Models\SystemSetting::get('withdrawal_fee_type', 'percentage'),
            'fee_value' => \App\Models\SystemSetting::get('withdrawal_fee_value', 0),
            'minimum_fee' => \App\Models\SystemSetting::get('withdrawal_minimum_fee', 0),
            'maximum_fee' => \App\Models\SystemSetting::get('withdrawal_maximum_fee', 999999),
        ];

        return view('member.withdraw', compact('wallet', 'withdrawalSettings'));
    }

    public function processWithdraw(Request $request)
    {
        $this->authorize('withdraw_funds');

        $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
            'bank_account' => 'required|string|min:10|max:20',
            'bank_name' => 'required|string',
            'routing_number' => 'nullable|string|size:9',
            'agree_terms' => 'required|accepted',
        ]);

        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        // Calculate withdrawal fee
        $withdrawalAmount = $request->amount;
        $withdrawalFee = $this->calculateWithdrawalFee($withdrawalAmount);
        $totalAmount = $withdrawalAmount + $withdrawalFee;

        // Check if user's wallet has sufficient balance (including fee)
        if ($wallet->balance < $totalAmount) {
            return redirect()->back()->withErrors(['amount' => 'Insufficient balance. You need $' . number_format($totalAmount, 2) . ' (Withdrawal: $' . number_format($withdrawalAmount, 2) . ' + Fee: $' . number_format($withdrawalFee, 2) . '). Your current balance is $' . number_format($wallet->balance, 2)]);
        }

        // Check if user's wallet is active
        if (!$wallet->is_active) {
            return redirect()->back()->withErrors(['general' => 'Your wallet is currently frozen. Please contact support.']);
        }

        try {
            \DB::transaction(function () use ($request, $user, $wallet, $withdrawalAmount, $withdrawalFee, $totalAmount) {
                // Create withdrawal transaction
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'withdrawal',
                    'amount' => $withdrawalAmount,
                    'status' => 'pending', // Withdrawals need approval
                    'payment_method' => 'bank_transfer',
                    'description' => 'Withdrawal to ' . $request->bank_name . ' account ending in ' . substr($request->bank_account, -4),
                    'metadata' => [
                        'bank_account' => $request->bank_account,
                        'bank_name' => $request->bank_name,
                        'routing_number' => $request->routing_number,
                        'withdrawal_method' => 'bank_transfer',
                        'withdrawal_fee' => $withdrawalFee,
                        'total_amount' => $totalAmount,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]
                ]);

                // Create withdrawal fee transaction if applicable (ALWAYS approved - fee is non-refundable)
                if ($withdrawalFee > 0) {
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'withdrawal_fee',
                        'amount' => $withdrawalFee,
                        'status' => 'approved', // Fee is immediately approved and non-refundable
                        'approved_by' => $user->id, // System-approved
                        'approved_at' => now(),
                        'payment_method' => 'internal',
                        'description' => 'Withdrawal processing fee for ' . $request->bank_name,
                        'admin_notes' => 'Withdrawal processing fee - non-refundable',
                        'metadata' => [
                            'related_transaction_id' => $transaction->id,
                            'withdrawal_amount' => $withdrawalAmount,
                            'fee_type' => \App\Models\SystemSetting::get('withdrawal_fee_type', 'percentage'),
                            'fee_value' => \App\Models\SystemSetting::get('withdrawal_fee_value', 0),
                            'non_refundable' => true,
                        ]
                    ]);
                }

                // Update wallet balance (deduct immediately, but transaction is pending approval)
                $wallet->decrement('balance', $totalAmount); // Deduct withdrawal amount + fee
                $wallet->update(['last_transaction_at' => now()]);
            });

            $message = 'Withdrawal request of $' . number_format($withdrawalAmount, 2) . ' has been submitted for approval.';
            if ($withdrawalFee > 0) {
                $message .= ' (Processing fee: $' . number_format($withdrawalFee, 2) . ' deducted immediately)';
            }
            $message .= ' Processing typically takes 1-3 business days. Check your dashboard for updates.';

            session()->flash('success', $message);

            return redirect()->route('wallet.transactions');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Withdrawal request failed. Please try again later.']);
        }
    }

    /**
     * Calculate transfer charge based on system settings
     */
    private function calculateTransferCharge($amount)
    {
        if (!\App\Models\SystemSetting::get('transfer_charge_enabled', false)) {
            return 0;
        }

        $chargeType = \App\Models\SystemSetting::get('transfer_charge_type', 'percentage');
        $chargeValue = \App\Models\SystemSetting::get('transfer_charge_value', 0);
        $minCharge = \App\Models\SystemSetting::get('transfer_minimum_charge', 0);
        $maxCharge = \App\Models\SystemSetting::get('transfer_maximum_charge', 999999);

        if ($chargeType === 'percentage') {
            $charge = ($amount * $chargeValue) / 100;
        } else {
            $charge = $chargeValue;
        }

        // Apply min/max limits
        $charge = max($charge, $minCharge);
        $charge = min($charge, $maxCharge);

        return round($charge, 2);
    }

    /**
     * Calculate withdrawal fee based on system settings
     */
    private function calculateWithdrawalFee($amount)
    {
        if (!\App\Models\SystemSetting::get('withdrawal_fee_enabled', false)) {
            return 0;
        }

        $feeType = \App\Models\SystemSetting::get('withdrawal_fee_type', 'percentage');
        $feeValue = \App\Models\SystemSetting::get('withdrawal_fee_value', 0);
        $minFee = \App\Models\SystemSetting::get('withdrawal_minimum_fee', 0);
        $maxFee = \App\Models\SystemSetting::get('withdrawal_maximum_fee', 999999);

        if ($feeType === 'percentage') {
            $fee = ($amount * $feeValue) / 100;
        } else {
            $fee = $feeValue;
        }

        // Apply min/max limits
        $fee = max($fee, $minFee);
        $fee = min($fee, $maxFee);

        return round($fee, 2);
    }

    public function transactions(Request $request)
    {
        $this->authorize('view_transactions');

        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

        $transactions = Auth::user()->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $wallet = Auth::user()->getOrCreateWallet();

        return view('member.transactions', compact('transactions', 'wallet'));
    }
}