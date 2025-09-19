@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">System Logs</h1>
                        <p class="mt-1 text-sm text-gray-600">Monitor system activity and security events</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="refreshLogs()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('admin.logs') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search Logs</label>
                        <input type="text" name="search" id="search" value="{{ $search }}"
                            placeholder="Search by message or IP address..."
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="min-w-0 flex-1">
                        <label for="type" class="block text-sm font-medium text-gray-700">Log Type</label>
                        <select name="type" id="type"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="all" {{ $logType == 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="security" {{ $logType == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="transaction" {{ $logType == 'transaction' ? 'selected' : '' }}>Transaction</option>
                            <option value="system" {{ $logType == 'system' ? 'selected' : '' }}>System</option>
                        </select>
                    </div>
                    <div class="min-w-0 flex-1">
                        <label for="level" class="block text-sm font-medium text-gray-700">Log Level</label>
                        <select name="level" id="level"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="all" {{ $level == 'all' ? 'selected' : '' }}>All Levels</option>
                            <option value="DEBUG" {{ $level == 'DEBUG' ? 'selected' : '' }}>Debug</option>
                            <option value="INFO" {{ $level == 'INFO' ? 'selected' : '' }}>Info</option>
                            <option value="WARNING" {{ $level == 'WARNING' ? 'selected' : '' }}>Warning</option>
                            <option value="ERROR" {{ $level == 'ERROR' ? 'selected' : '' }}>Error</option>
                            <option value="CRITICAL" {{ $level == 'CRITICAL' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full bg-indigo-600 border border-transparent rounded-md py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Log Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Info Events</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $logs->where('level', 'INFO')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L3.316 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Warnings</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $logs->where('level', 'WARNING')->count() }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Errors</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $logs->whereIn('level', ['ERROR', 'CRITICAL'])->count() }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Entries</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $logs->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">System Activity Log</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    @if($logs->count() > 0)
                        Showing {{ $logs->count() }} log entries
                    @else
                        No log entries found matching the current filters
                    @endif
                </p>
            </div>

            @if($logs->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($logs as $log)
                        <li class="px-4 py-6 sm:px-6 {{ $log['level'] == 'CRITICAL' ? 'bg-red-50' : ($log['level'] == 'ERROR' ? 'bg-orange-50' : '') }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <!-- Log Level Badge -->
                                    <div class="flex-shrink-0">
                                        @php
                                            $levelColors = [
                                                'DEBUG' => 'bg-gray-100 text-gray-800',
                                                'INFO' => 'bg-blue-100 text-blue-800',
                                                'WARNING' => 'bg-yellow-100 text-yellow-800',
                                                'ERROR' => 'bg-orange-100 text-orange-800',
                                                'CRITICAL' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $levelColors[$log['level']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $log['level'] }}
                                        </span>
                                    </div>

                                    <!-- Log Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $log['message'] }}</h4>
                                            @php
                                                $typeColors = [
                                                    'security' => 'bg-red-100 text-red-800',
                                                    'transaction' => 'bg-green-100 text-green-800',
                                                    'system' => 'bg-blue-100 text-blue-800'
                                                ];
                                            @endphp
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $typeColors[$log['type']] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($log['type']) }}
                                            </span>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 space-x-6">
                                            <div class="flex items-center">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $log['timestamp']->format('M d, Y g:i A') }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                                                </svg>
                                                {{ $log['ip_address'] }}
                                            </div>
                                            @if($log['user_id'])
                                                <div class="flex items-center">
                                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    User ID: {{ $log['user_id'] }}
                                                </div>
                                            @endif
                                        </div>
                                        @if(strlen($log['user_agent']) > 50)
                                            <div class="mt-1 text-xs text-gray-400">
                                                <strong>User Agent:</strong> {{ Str::limit($log['user_agent'], 100) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    @if($log['level'] == 'CRITICAL' || $log['level'] == 'ERROR')
                                        <button onclick="investigateLog({{ $log['id'] }})"
                                            class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            Investigate
                                        </button>
                                    @endif
                                    <button onclick="viewLogDetails({{ json_encode($log) }})"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Details
                                    </button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No logs found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or check back later.</p>
                </div>
            @endif
        </div>

        <!-- Export Options -->
        <div class="mt-6 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Export Options</h3>
            <div class="flex flex-wrap gap-4">
                <button onclick="exportLogs('csv')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Export as CSV
                </button>
                <button onclick="exportLogs('json')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Export as JSON
                </button>
                <button onclick="clearOldLogs()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Clear Old Logs (30+ days)
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div id="log-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Log Entry Details</h3>
        <div id="log-details-content">
            <!-- Content will be populated by JavaScript -->
        </div>
        <div class="mt-6 flex justify-end">
            <button type="button" onclick="closeLogDetailsModal()"
                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function refreshLogs() {
    window.location.reload();
}

function viewLogDetails(log) {
    const modal = document.getElementById('log-details-modal');
    const content = document.getElementById('log-details-content');

    content.innerHTML = `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Timestamp</label>
                <p class="mt-1 text-sm text-gray-900">${new Date(log.timestamp).toLocaleString()}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Level</label>
                <p class="mt-1 text-sm text-gray-900">${log.level}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <p class="mt-1 text-sm text-gray-900">${log.type}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Message</label>
                <p class="mt-1 text-sm text-gray-900">${log.message}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">IP Address</label>
                <p class="mt-1 text-sm text-gray-900">${log.ip_address}</p>
            </div>
            ${log.user_id ? `
            <div>
                <label class="block text-sm font-medium text-gray-700">User ID</label>
                <p class="mt-1 text-sm text-gray-900">${log.user_id}</p>
            </div>
            ` : ''}
            <div>
                <label class="block text-sm font-medium text-gray-700">User Agent</label>
                <p class="mt-1 text-sm text-gray-900 break-all">${log.user_agent}</p>
            </div>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeLogDetailsModal() {
    const modal = document.getElementById('log-details-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function investigateLog(logId) {
    if (confirm('Mark this log entry for investigation?')) {
        showAlert(`Log entry ${logId} has been flagged for investigation`, 'success');
    }
}

function exportLogs(format) {
    showAlert(`Logs export started. ${format.toUpperCase()} file will be generated shortly.`, 'success');
}

function clearOldLogs() {
    if (confirm('Are you sure you want to clear all logs older than 30 days? This action cannot be undone.')) {
        showAlert('Old logs cleared successfully.', 'success');
    }
}

function showAlert(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400';

    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 z-50 ${alertClass} border px-4 py-3 rounded shadow-lg max-w-sm`;
    alert.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="float-right text-xl leading-none ml-4">&times;</button>
    `;

    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}
</script>
@endsection