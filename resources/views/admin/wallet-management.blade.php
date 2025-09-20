@extends('layouts.admin')

@section('title', 'Wallet Management')

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
                    Wallet Management
                </h4>
                <p class="text-body-secondary mb-0">Monitor and manage user e-wallets</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-arrow-left') }}"></use>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Wallet Overview Stats -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-success-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">${{ number_format($totalBalance, 2) }}</div>
                    <div>Total Wallet Balance</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wallet') }}"></use>
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-primary-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $todayDeposits ?? 0 }}</div>
                    <div>Today's Deposits</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-danger-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">${{ number_format($todayWithdrawals ?? 0, 2) }}</div>
                    <div>Today's Withdrawals</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-minus') }}"></use>
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-warning-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $pendingTransactions ?? 0 }}</div>
                    <div>Pending Transactions</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-clock') }}"></use>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- User Wallets Table -->
<div class="card mb-4">
    <div class="card-header">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-people') }}"></use>
        </svg>
        <strong>User Wallets</strong>
        <small class="text-body-secondary ms-auto">Overview of all member wallet balances and activity.</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">User</th>
                        <th scope="col">Balance</th>
                        <th scope="col">Last Transaction</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wallets as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3 {{ $user->wallet && $user->wallet->is_active ? 'bg-primary' : 'bg-danger' }}">
                                        <span class="text-white">{{ strtoupper(substr($user->fullname ?? $user->username, 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->fullname ?? $user->username }}</div>
                                        <div class="text-body-secondary">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">${{ number_format($user->wallet ? $user->wallet->balance : 0, 2) }}</div>
                                @if($user->wallet && isset($user->wallet->last_transaction_at))
                                    <div class="text-body-secondary">Last activity: {{ $user->wallet->last_transaction_at->diffForHumans() }}</div>
                                @else
                                    <div class="text-body-secondary">No activity</div>
                                @endif
                            </td>
                            <td>
                                @if(isset($user->transactions) && $user->transactions->isNotEmpty())
                                    @php $lastTransaction = $user->transactions->first(); @endphp
                                    <div class="fw-semibold">
                                        {{ ucfirst($lastTransaction->type) }}:
                                        {{ $lastTransaction->type === 'deposit' ? '+' : '-' }}${{ number_format($lastTransaction->amount, 2) }}
                                    </div>
                                    <div class="text-body-secondary">{{ $lastTransaction->created_at->diffForHumans() }}</div>
                                @else
                                    <div class="text-body-secondary">No transactions</div>
                                @endif
                            </td>
                            <td>
                                @if($user->wallet)
                                    <span class="badge {{ $user->wallet->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->wallet->is_active ? 'Active' : 'Frozen' }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">No Wallet</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                    @if($user->wallet)
                                        @if($user->wallet->is_active)
                                            <button class="btn btn-sm btn-outline-warning">Freeze</button>
                                        @else
                                            <button class="btn btn-sm btn-outline-success">Unfreeze</button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-body-secondary py-4">
                                No wallets found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($wallets) && $wallets->hasPages())
        <div class="card-footer">
            {{ $wallets->links() }}
        </div>
    @endif
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
        </svg>
        <strong>Recent Transactions</strong>
        <small class="text-body-secondary ms-auto">Latest wallet transactions across all users.</small>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($recentTransactions ?? [] as $transaction)
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3 {{ $transaction->type === 'deposit' ? 'bg-success' : ($transaction->type === 'withdrawal' ? 'bg-danger' : 'bg-primary') }}">
                            <svg class="icon text-white">
                                @if($transaction->type === 'deposit')
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                                @elseif($transaction->type === 'withdrawal')
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-minus') }}"></use>
                                @else
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-swap-horizontal') }}"></use>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <div class="fw-semibold">
                                {{ ucfirst($transaction->type) }} by {{ $transaction->user->fullname ?? $transaction->user->username }}
                            </div>
                            <div class="text-body-secondary">
                                {{ ucfirst($transaction->payment_method ?? 'N/A') }} • {{ $transaction->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold {{ $transaction->type === 'deposit' ? 'text-success' : 'text-danger' }}">
                            {{ $transaction->type === 'deposit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                        </div>
                        <span class="badge
                            @if($transaction->status == 'approved') bg-success
                            @elseif($transaction->status == 'rejected') bg-danger
                            @elseif($transaction->status == 'pending') bg-warning
                            @else bg-secondary @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-center text-body-secondary py-4">
                    No recent transactions
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection