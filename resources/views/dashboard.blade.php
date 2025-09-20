@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="row mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Welcome back, {{ $user->username }}! 👋</h4>
                        <p class="text-body-secondary mb-0">Here's what's happening with your wallet today.</p>
                    </div>
                    <div class="d-none d-md-block">
                        @if($user->hasRole('admin'))
                            <span class="badge bg-purple-gradient text-white">Administrator</span>
                        @elseif($user->hasRole('member'))
                            <span class="badge bg-info-gradient text-white">Member</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wallet Overview Cards -->
<div class="row g-3 mb-4">
    <!-- Current Balance -->
    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-success-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">${{ number_format($wallet->balance, 2) }}</div>
                    <div>Current Balance</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wallet') }}"></use>
                </svg>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <!-- Mini chart can be added here -->
            </div>
        </div>
    </div>

    <!-- Total Deposits -->
    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-info-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">${{ number_format($totalDeposits, 2) }}</div>
                    <div>Total Deposits</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-circle-bottom') }}"></use>
                </svg>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <!-- Mini chart can be added here -->
            </div>
        </div>
    </div>

    <!-- Total Withdrawals -->
    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-warning-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">${{ number_format($totalWithdrawals, 2) }}</div>
                    <div>Total Withdrawals</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-circle-top') }}"></use>
                </svg>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <!-- Mini chart can be added here -->
            </div>
        </div>
    </div>

    <!-- Pending Transactions -->
    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-danger-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $pendingTransactions }}</div>
                    <div>Pending Transactions</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-clock') }}"></use>
                </svg>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <!-- Mini chart can be added here -->
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Quick Actions</h5>
        <div class="card-header-actions">
            <small class="text-body-secondary">Manage your wallet and transactions</small>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-2">
            @if($user->hasRole('admin'))
                <!-- Admin Actions -->
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-purple btn-block">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-chart-pie') }}"></use>
                        </svg>
                        Admin Dashboard
                    </a>
                </div>
                @can('wallet_management')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.wallet.management') }}" class="btn btn-info btn-block">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wallet') }}"></use>
                        </svg>
                        Wallet Management
                    </a>
                </div>
                @endcan
                @can('transaction_approval')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.transaction.approval') }}" class="btn btn-warning btn-block">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-task') }}"></use>
                        </svg>
                        Transaction Approval
                    </a>
                </div>
                @endcan
                @can('system_settings')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.system.settings') }}" class="btn btn-secondary btn-block">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-settings') }}"></use>
                        </svg>
                        System Settings
                    </a>
                </div>
                @endcan
            @else
                <!-- Member Actions -->
                @can('deposit_funds')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('wallet.deposit') }}" class="btn btn-success btn-block">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-circle-bottom') }}"></use>
                        </svg>
                        Deposit Funds
                    </a>
                </div>
                @endcan
                @can('transfer_funds')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('wallet.transfer') }}" class="btn btn-info btn-block">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-right') }}"></use>
                        </svg>
                        Transfer Funds
                    </a>
                </div>
                @endcan
                @can('withdraw_funds')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('wallet.withdraw') }}" class="btn btn-danger btn-block">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-circle-top') }}"></use>
                        </svg>
                        Withdraw Funds
                    </a>
                </div>
                @endcan
                @can('view_transactions')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('wallet.transactions') }}" class="btn btn-outline-primary btn-block">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list-numbered') }}"></use>
                        </svg>
                        View Transactions
                    </a>
                </div>
                @endcan
            @endif
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card mb-4">
    <div class="card-header">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list-numbered') }}"></use>
        </svg>
        <strong>Recent Transactions</strong>
        <small class="text-body-secondary ms-auto">Your latest wallet activity</small>
    </div>
    <div class="card-body p-0">
        @forelse($recentTransactions as $transaction)
            <div class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md me-3
                        @if($transaction->type === 'deposit' || $transaction->type === 'transfer_in') bg-success-gradient
                        @elseif($transaction->type === 'withdrawal' || $transaction->type === 'transfer_out') bg-danger-gradient
                        @elseif($transaction->type === 'transfer_charge') bg-warning-gradient
                        @else bg-info-gradient @endif">
                        <svg class="icon text-white">
                            @if($transaction->type === 'deposit' || $transaction->type === 'transfer_in')
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-circle-bottom') }}"></use>
                            @elseif($transaction->type === 'withdrawal' || $transaction->type === 'transfer_out')
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-circle-top') }}"></use>
                            @elseif($transaction->type === 'transfer_charge')
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-dollar') }}"></use>
                            @else
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-swap-horizontal') }}"></use>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <div class="fw-semibold">
                            @if($transaction->type === 'transfer_out')
                                Transfer Sent
                            @elseif($transaction->type === 'transfer_in')
                                Transfer Received
                            @elseif($transaction->type === 'transfer_charge')
                                Transfer Fee
                            @else
                                {{ ucfirst($transaction->type) }}
                            @endif
                        </div>
                        <div class="small text-body-secondary">
                            <svg class="icon icon-xs me-1">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-clock') }}"></use>
                            </svg>
                            {{ $transaction->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold
                        @if($transaction->type === 'deposit' || $transaction->type === 'transfer_in') text-success
                        @elseif($transaction->type === 'transfer_charge') text-warning
                        @else text-danger @endif">
                        @if($transaction->type === 'deposit' || $transaction->type === 'transfer_in')
                            +${{ number_format($transaction->amount, 2) }}
                        @else
                            -${{ number_format($transaction->amount, 2) }}
                        @endif
                    </div>
                    <span class="badge
                        @if($transaction->status == 'approved') bg-success-gradient
                        @elseif($transaction->status == 'rejected') bg-danger-gradient
                        @elseif($transaction->status == 'pending') bg-warning-gradient
                        @else bg-secondary-gradient @endif">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="p-4 text-center">
                <svg class="icon icon-3xl text-body-secondary mb-3">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-inbox') }}"></use>
                </svg>
                <h4 class="text-body-secondary">No transactions</h4>
                <p class="text-body-secondary">Get started by making your first deposit.</p>
                @can('deposit_funds')
                    <a href="{{ route('wallet.deposit') }}" class="btn btn-primary mt-2">
                        <svg class="icon me-1">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                        </svg>
                        Make Deposit
                    </a>
                @endcan
            </div>
        @endforelse
    </div>
    @if($recentTransactions->count() > 0)
        <div class="card-footer">
            <a href="{{ route('wallet.transactions') }}" class="btn btn-outline-primary btn-sm">
                <svg class="icon me-1">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                </svg>
                View all transactions
            </a>
        </div>
    @endif
</div>

<!-- Account Information -->
<div class="card mb-4">
    <div class="card-header">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
        </svg>
        <strong>Account Information</strong>
        <small class="text-body-secondary ms-auto">Your account details and security status</small>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>Email</strong>
                    <div class="small text-body-secondary">{{ $user->email }}</div>
                </div>
                <svg class="icon text-body-secondary">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-envelope-closed') }}"></use>
                </svg>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>Account Status</strong>
                    <div class="small text-body-secondary mt-1">
                        @if($user->email_verified_at)
                            <span class="badge bg-success-gradient">
                                <svg class="icon icon-xs me-1">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-check-circle') }}"></use>
                                </svg>
                                Verified
                            </span>
                        @else
                            <span class="badge bg-danger-gradient">
                                <svg class="icon icon-xs me-1">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-x-circle') }}"></use>
                                </svg>
                                Unverified
                            </span>
                        @endif
                    </div>
                </div>
                <svg class="icon text-body-secondary">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-shield-alt') }}"></use>
                </svg>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>Two-Factor Authentication</strong>
                    <div class="small text-body-secondary mt-1">
                        @if($user->two_factor_secret)
                            <span class="badge bg-success-gradient">
                                <svg class="icon icon-xs me-1">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                                </svg>
                                Enabled
                            </span>
                        @else
                            <span class="badge bg-warning-gradient">
                                <svg class="icon icon-xs me-1">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-lock-unlocked') }}"></use>
                                </svg>
                                Disabled
                            </span>
                        @endif
                    </div>
                </div>
                <svg class="icon text-body-secondary">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-mobile') }}"></use>
                </svg>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>Wallet Status</strong>
                    <div class="small text-body-secondary mt-1">
                        @if($wallet->is_active)
                            <span class="badge bg-success-gradient">
                                <svg class="icon icon-xs me-1">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-check-circle') }}"></use>
                                </svg>
                                Active
                            </span>
                        @else
                            <span class="badge bg-danger-gradient">
                                <svg class="icon icon-xs me-1">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-ban') }}"></use>
                                </svg>
                                Frozen
                            </span>
                        @endif
                    </div>
                </div>
                <svg class="icon text-body-secondary">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wallet') }}"></use>
                </svg>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>Total Transactions</strong>
                    <div class="small text-body-secondary">{{ $totalTransactions }} transactions</div>
                </div>
                <span class="badge bg-info-gradient">{{ $totalTransactions }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>Member Since</strong>
                    <div class="small text-body-secondary">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
                <svg class="icon text-body-secondary">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Transaction Summary -->
@if($monthlyTransactions->count() > 0)
<div class="card mb-4">
    <div class="card-header">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-chart-line') }}"></use>
        </svg>
        <strong>Monthly Transaction Summary</strong>
        <small class="text-body-secondary ms-auto">Your transaction activity over the past 6 months</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Month</th>
                        <th scope="col">Deposits</th>
                        <th scope="col">Withdrawals</th>
                        <th scope="col">Transactions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyTransactions as $monthly)
                        <tr>
                            <td class="fw-semibold">
                                {{ date('M Y', mktime(0, 0, 0, $monthly->month, 1, $monthly->year)) }}
                            </td>
                            <td class="text-success fw-semibold">
                                ${{ number_format($monthly->deposits, 2) }}
                            </td>
                            <td class="text-danger fw-semibold">
                                ${{ number_format($monthly->withdrawals, 2) }}
                            </td>
                            <td class="fw-semibold">
                                {{ $monthly->count }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection