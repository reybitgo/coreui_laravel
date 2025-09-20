@extends('layouts.admin')

@section('title', 'Transaction History')

@section('content')
<!-- Page Header -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                    </svg>
                    Transaction History
                </h4>
                <p class="text-body-secondary mb-0">View your e-wallet transactions and activity</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('wallet.deposit') }}" class="btn btn-success">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                    </svg>
                    Deposit
                </a>
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

<!-- Wallet Overview -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card bg-primary-gradient text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Current Balance</h5>
                <h2 class="display-4 fw-bold">${{ number_format($wallet->balance, 2) }}</h2>
                <p class="mb-0">
                    <span class="badge {{ $wallet->is_active ? 'bg-light text-success' : 'bg-warning text-dark' }}">
                        {{ $wallet->is_active ? 'Account Active' : 'Account Frozen' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg>
                <strong>Quick Actions</strong>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    @can('deposit_funds')
                    <a href="{{ route('wallet.deposit') }}" class="btn btn-success btn-sm">
                        <svg class="icon me-1">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                        </svg>
                        Deposit
                    </a>
                    @endcan
                    @can('transfer_funds')
                    <a href="{{ route('wallet.transfer') }}" class="btn btn-primary btn-sm">
                        <svg class="icon me-1">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-swap-horizontal') }}"></use>
                        </svg>
                        Transfer
                    </a>
                    @endcan
                    @can('withdraw_funds')
                    <a href="{{ route('wallet.withdraw') }}" class="btn btn-danger btn-sm">
                        <svg class="icon me-1">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-minus') }}"></use>
                        </svg>
                        Withdraw
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions List -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                </svg>
                <strong>Recent Transactions</strong>
            </div>
            <div class="d-flex align-items-center gap-3">
                <small class="text-body-secondary">Total: {{ $transactions->total() }} transactions</small>
                <div class="d-flex align-items-center gap-2">
                    <label for="per_page" class="form-label mb-0 small">Show:</label>
                    <select id="per_page" onchange="changePerPage(this.value)" class="form-select form-select-sm">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <small class="text-body-secondary">per page</small>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @forelse($transactions as $transaction)
            <div class="list-group-item list-group-item-action border-0 border-bottom">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3 {{
                            $transaction->type == 'deposit' || $transaction->type == 'transfer_in' ? 'bg-success' :
                            ($transaction->type == 'transfer_charge' || $transaction->type == 'withdrawal_fee' ? 'bg-warning' : 'bg-danger')
                        }}">
                            <svg class="icon text-white">
                                @if($transaction->type == 'deposit' || $transaction->type == 'transfer_in')
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                                @elseif($transaction->type == 'transfer_out')
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-swap-horizontal') }}"></use>
                                @elseif($transaction->type == 'transfer_charge' || $transaction->type == 'withdrawal_fee')
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-dollar') }}"></use>
                                @else
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-minus') }}"></use>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <div class="fw-semibold">
                                @if($transaction->type == 'transfer_out')
                                    Transfer Sent
                                @elseif($transaction->type == 'transfer_in')
                                    Transfer Received
                                @elseif($transaction->type == 'transfer_charge')
                                    Transfer Fee
                                @elseif($transaction->type == 'withdrawal_fee')
                                    Withdrawal Fee
                                @else
                                    {{ ucfirst($transaction->type) }}
                                @endif
                            </div>
                            <div class="text-body-secondary">
                                {{ $transaction->created_at->format('M d, Y') }}
                                <span class="d-none d-sm-inline">at {{ $transaction->created_at->format('g:i A') }}</span>
                            </div>
                            @if($transaction->description)
                                <div class="small text-body-secondary">{{ $transaction->description }}</div>
                            @endif
                            @if($transaction->reference_number)
                                <div class="small text-body-secondary">Ref: {{ $transaction->reference_number }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold {{
                            in_array($transaction->type, ['deposit', 'transfer_in']) ? 'text-success' :
                            (in_array($transaction->type, ['transfer_charge', 'withdrawal_fee']) ? 'text-warning' : 'text-danger')
                        }}">
                            {{ in_array($transaction->type, ['deposit', 'transfer_in']) ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                        </div>
                        <span class="badge {{
                            $transaction->status == 'approved' ? 'bg-success' :
                            ($transaction->status == 'rejected' ? 'bg-danger' :
                            ($transaction->status == 'pending' ? 'bg-warning' : 'bg-secondary'))
                        }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <svg class="icon icon-4xl text-body-secondary mb-3">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                </svg>
                <h5 class="text-body-secondary">No transactions yet</h5>
                <p class="text-body-secondary">Your transaction history will appear here once you start using your e-wallet.</p>
                <a href="{{ route('wallet.deposit') }}" class="btn btn-success">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                    </svg>
                    Make your first deposit
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
        <div class="card-footer">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page when changing per_page
    window.location.href = url.toString();
}
</script>
@endpush

@endsection