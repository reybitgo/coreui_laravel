@extends('layouts.app')

@section('content')
<!-- Success/Error Messages -->
<div id="alert-container" class="fixed top-4 right-4 z-50"></div>
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Transaction Approval</h1>
                        <p class="mt-1 text-sm text-gray-600">Review and approve pending transactions</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Approval Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd id="pending-count" class="text-lg font-medium text-gray-900">{{ $pendingTransactions->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Today Approved</dt>
                                <dd id="approved-count" class="text-lg font-medium text-gray-900">{{ \App\Models\Transaction::where('status', 'approved')->whereDate('approved_at', today())->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Today Rejected</dt>
                                <dd id="rejected-count" class="text-lg font-medium text-gray-900">{{ \App\Models\Transaction::where('status', 'rejected')->whereDate('approved_at', today())->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Value</dt>
                                <dd id="total-value" class="text-lg font-medium text-gray-900">${{ number_format($pendingTransactions->sum('amount'), 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Bulk Actions</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex flex-wrap gap-4">
                    <button onclick="selectAll()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Select All
                    </button>
                    <button onclick="clearSelection()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        Clear Selection
                    </button>
                    <button onclick="bulkApprove()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Approve Selected
                    </button>
                    <button onclick="bulkReject()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Reject Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Pending Transactions -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pending Transactions</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Transactions awaiting administrative approval.
                </p>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($pendingTransactions as $transaction)
                    <li class="px-4 py-6 sm:px-6" data-transaction-id="{{ $transaction->id }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <input type="checkbox" name="selected_transactions[]" value="{{ $transaction->id }}" class="transaction-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 @if($transaction->type === 'deposit') bg-green-100 @elseif($transaction->type === 'withdrawal') bg-red-100 @else bg-blue-100 @endif rounded-full flex items-center justify-center">
                                        @if($transaction->type === 'deposit')
                                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        @elseif($transaction->type === 'withdrawal')
                                            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center">
                                        <h4 class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->type) }} Request</h4>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($transaction->amount > 5000) bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif">
                                            @if($transaction->amount > 5000) High Priority @else Standard @endif
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $transaction->user->fullname }}</span> ({{ $transaction->user->email }})
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Ref: {{ $transaction->reference_number }} •
                                            {{ ucfirst($transaction->payment_method) }} •
                                            Requested: {{ $transaction->created_at->diffForHumans() }}
                                        </p>
                                        @if($transaction->description)
                                            <p class="text-sm text-gray-500 mt-1">{{ $transaction->description }}</p>
                                        @endif
                                    </div>
                                    <div class="mt-2 flex items-center space-x-4">
                                        <span class="text-lg font-bold
                                            @if($transaction->type === 'deposit') text-green-600
                                            @elseif($transaction->type === 'withdrawal') text-red-600
                                            @else text-blue-600 @endif">
                                            @if($transaction->type === 'withdrawal')-@endif${{ number_format($transaction->amount, 2) }}
                                        </span>
                                        @if($transaction->user->wallet)
                                            <span class="text-sm text-gray-500">Current Balance: ${{ number_format($transaction->user->wallet->balance, 2) }}</span>
                                        @else
                                            <span class="text-sm text-gray-500">Current Balance: $0.00</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="approveTransaction({{ $transaction->id }})"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Approve
                                </button>
                                <button onclick="rejectTransaction({{ $transaction->id }})"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Reject
                                </button>
                                <button onclick="reviewTransaction({{ $transaction->id }})"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                                    Review
                                </button>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 sm:px-6">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending transactions</h3>
                            <p class="mt-1 text-sm text-gray-500">All transactions have been processed.</p>
                        </div>
                    </li>
                @endforelse
            </ul>

            @if($pendingTransactions->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $pendingTransactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Transaction</h3>
            <form id="approvalForm">
                <div class="mb-4">
                    <label for="approvalNotes" class="block text-sm font-medium text-gray-700">Admin Notes (Optional)</label>
                    <textarea id="approvalNotes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Add any notes about this approval..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Confirm Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Transaction</h3>
            <form id="rejectionForm">
                <div class="mb-4">
                    <label for="rejectionReason" class="block text-sm font-medium text-gray-700">Rejection Reason (Required)</label>
                    <textarea id="rejectionReason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Explain why this transaction is being rejected..." required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentTransactionId = null;

function approveTransaction(id) {
    currentTransactionId = id;
    document.getElementById('approvalModal').classList.remove('hidden');
}

function rejectTransaction(id) {
    currentTransactionId = id;
    document.getElementById('rejectionModal').classList.remove('hidden');
}

function reviewTransaction(id) {
    alert(`Review functionality for transaction ${id} would be implemented here.`);
}

function closeModal() {
    document.getElementById('approvalModal').classList.add('hidden');
    document.getElementById('rejectionModal').classList.add('hidden');
    currentTransactionId = null;
}

// Handle approval form submission
document.getElementById('approvalForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const notes = document.getElementById('approvalNotes').value;

    fetch(`/admin/transactions/${currentTransactionId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Transaction approved successfully!', 'success');
            document.querySelector(`[data-transaction-id="${currentTransactionId}"]`).remove();
            updateStats();
        } else {
            showAlert(data.message || 'Error approving transaction', 'error');
        }
        closeModal();
    })
    .catch(error => {
        showAlert('Error approving transaction', 'error');
        closeModal();
    });
});

// Handle rejection form submission
document.getElementById('rejectionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const reason = document.getElementById('rejectionReason').value;

    fetch(`/admin/transactions/${currentTransactionId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Transaction rejected successfully!', 'success');
            document.querySelector(`[data-transaction-id="${currentTransactionId}"]`).remove();
            updateStats();
        } else {
            showAlert(data.message || 'Error rejecting transaction', 'error');
        }
        closeModal();
    })
    .catch(error => {
        showAlert('Error rejecting transaction', 'error');
        closeModal();
    });
});

function showAlert(message, type) {
    const alertContainer = document.getElementById('alert-container');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'} px-4 py-3 rounded mb-4 border`;
    alertDiv.textContent = message;

    alertContainer.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function selectAll() {
    document.querySelectorAll('.transaction-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function clearSelection() {
    document.querySelectorAll('.transaction-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function updateStats() {
    // Fetch updated statistics from server
    fetch('/admin/transaction-stats', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all statistics
            document.getElementById('pending-count').textContent = data.pending_count;
            document.getElementById('approved-count').textContent = data.approved_today;
            document.getElementById('rejected-count').textContent = data.rejected_today;
            document.getElementById('total-value').textContent = '$' + data.total_value;
        }
    })
    .catch(error => {
        console.error('Error updating stats:', error);
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const approvalModal = document.getElementById('approvalModal');
    const rejectionModal = document.getElementById('rejectionModal');

    if (event.target === approvalModal) {
        closeModal();
    }
    if (event.target === rejectionModal) {
        closeModal();
    }
}
</script>
@endsection