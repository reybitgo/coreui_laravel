@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Transaction History</h1>
                        <p class="mt-1 text-sm text-gray-600">View your e-wallet transactions and activity</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('wallet.deposit') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Deposit
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallet Balance and Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Current Balance</h3>
                            <p class="text-2xl sm:text-3xl font-bold text-green-600">${{ number_format($wallet->balance, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 {{ $wallet->is_active ? 'bg-blue-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 {{ $wallet->is_active ? 'text-blue-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if($wallet->is_active)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    @endif
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Wallet Status</h3>
                            <p class="text-lg font-semibold {{ $wallet->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $wallet->is_active ? 'Active' : 'Frozen' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    @can('deposit_funds')
                    <a href="{{ route('wallet.deposit') }}" class="inline-flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="hidden sm:inline">Deposit Funds</span>
                        <span class="sm:hidden">Deposit</span>
                    </a>
                    @endcan
                    @can('transfer_funds')
                    <a href="{{ route('wallet.transfer') }}" class="inline-flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="hidden sm:inline">Transfer Money</span>
                        <span class="sm:hidden">Transfer</span>
                    </a>
                    @endcan
                    @can('withdraw_funds')
                    <a href="{{ route('wallet.withdraw') }}" class="inline-flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        <span class="hidden sm:inline">Withdraw Funds</span>
                        <span class="sm:hidden">Withdraw</span>
                    </a>
                    @endcan
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        </svg>
                        <span class="hidden sm:inline">Dashboard</span>
                        <span class="sm:hidden">Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="bg-white shadow overflow-hidden rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Transactions</h3>
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                        <p class="text-sm text-gray-500">Total: {{ $transactions->total() }} transactions</p>
                        <div class="flex items-center space-x-2">
                            <label for="per_page" class="text-sm text-gray-500">Show:</label>
                            <select id="per_page" onchange="changePerPage(this.value)" class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-gray-500">per page</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200">
                @forelse($transactions as $transaction)
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    @if($transaction->type == 'deposit' || $transaction->type == 'transfer_in')
                                        <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                    @elseif($transaction->type == 'transfer_out')
                                        <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                    @elseif($transaction->type == 'transfer_charge')
                                        <div class="h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                            </svg>
                                        </div>
                                    @elseif($transaction->type == 'withdrawal_fee')
                                        <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate">
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
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->created_at->format('M d, Y') }}
                                                <span class="hidden sm:inline">at {{ $transaction->created_at->format('g:i A') }}</span>
                                            </div>
                                            @if($transaction->description)
                                                <div class="text-xs text-gray-400 mt-1 truncate">{{ $transaction->description }}</div>
                                            @endif
                                        </div>
                                        <div class="flex items-center mt-2 sm:mt-0 sm:ml-4">
                                            <div class="text-right">
                                                <div class="text-sm font-medium
                                                    @if(in_array($transaction->type, ['deposit', 'transfer_in'])) text-green-600
                                                    @elseif(in_array($transaction->type, ['transfer_charge', 'withdrawal_fee'])) text-orange-600
                                                    @else text-red-600 @endif">
                                                    @if(in_array($transaction->type, ['deposit', 'transfer_in'])) + @else - @endif
                                                    ${{ number_format($transaction->amount, 2) }}
                                                </div>
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($transaction->status == 'approved') bg-green-100 text-green-800
                                                        @elseif($transaction->status == 'rejected') bg-red-100 text-red-800
                                                        @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Mobile Details -->
                                    <div class="sm:hidden mt-2">
                                        @if($transaction->reference_number)
                                            <div class="text-xs text-gray-400">Ref: {{ $transaction->reference_number }}</div>
                                        @endif
                                        <div class="text-xs text-gray-400">{{ $transaction->created_at->format('g:i A') }}</div>
                                    </div>
                                    <!-- Desktop Details -->
                                    <div class="hidden sm:block">
                                        @if($transaction->reference_number)
                                            <div class="text-xs text-gray-400 mt-1">Reference: {{ $transaction->reference_number }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 sm:px-6">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No transactions yet</h3>
                            <p class="mt-2 text-sm text-gray-500">Your transaction history will appear here once you start using your e-wallet.</p>
                            <div class="mt-6">
                                <a href="{{ route('wallet.deposit') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Make your first deposit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<script>
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page when changing per_page
    window.location.href = url.toString();
}
</script>
@endsection