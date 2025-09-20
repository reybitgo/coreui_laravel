@extends('layouts.admin')

@section('title', 'System Logs')

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
                    System Logs
                </h4>
                <p class="text-body-secondary mb-0">Monitor system activity and security events</p>
            </div>
            <div>
                <button onclick="refreshLogs()" class="btn btn-primary me-2">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-reload') }}"></use>
                    </svg>
                    Refresh
                </button>
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

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-filter') }}"></use>
        </svg>
        <strong>Filter Logs</strong>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.logs') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Logs</label>
                <input type="text" name="search" id="search" value="{{ $search }}"
                    placeholder="Search by message or IP address..." class="form-control">
            </div>
            <div class="col-md-3">
                <label for="type" class="form-label">Log Type</label>
                <select name="type" id="type" class="form-select">
                    <option value="all" {{ $logType == 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="security" {{ $logType == 'security' ? 'selected' : '' }}>Security</option>
                    <option value="transaction" {{ $logType == 'transaction' ? 'selected' : '' }}>Transaction</option>
                    <option value="system" {{ $logType == 'system' ? 'selected' : '' }}>System</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="level" class="form-label">Log Level</label>
                <select name="level" id="level" class="form-select">
                    <option value="all" {{ $level == 'all' ? 'selected' : '' }}>All Levels</option>
                    <option value="DEBUG" {{ $level == 'DEBUG' ? 'selected' : '' }}>Debug</option>
                    <option value="INFO" {{ $level == 'INFO' ? 'selected' : '' }}>Info</option>
                    <option value="WARNING" {{ $level == 'WARNING' ? 'selected' : '' }}>Warning</option>
                    <option value="ERROR" {{ $level == 'ERROR' ? 'selected' : '' }}>Error</option>
                    <option value="CRITICAL" {{ $level == 'CRITICAL' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Log Statistics -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-primary-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $logs->where('level', 'INFO')->count() }}</div>
                    <div>Info Events</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-info') }}"></use>
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-warning-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $logs->where('level', 'WARNING')->count() }}</div>
                    <div>Warnings</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-warning') }}"></use>
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-danger-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $logs->whereIn('level', ['ERROR', 'CRITICAL'])->count() }}</div>
                    <div>Errors</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-x') }}"></use>
                </svg>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card text-white bg-success-gradient">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $logs->count() }}</div>
                    <div>Total Entries</div>
                </div>
                <svg class="icon icon-3xl">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-chart-line') }}"></use>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Logs Display -->
<div class="card">
    <div class="card-header">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
        </svg>
        <strong>System Activity Log</strong>
        <small class="text-body-secondary ms-auto">
            @if($logs->count() > 0)
                Showing {{ $logs->count() }} log entries
            @else
                No log entries found matching the current filters
            @endif
        </small>
    </div>

    @if($logs->count() > 0)
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($logs as $log)
                    <div class="list-group-item {{ $log['level'] == 'CRITICAL' ? 'bg-danger-subtle' : ($log['level'] == 'ERROR' ? 'bg-warning-subtle' : '') }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex flex-grow-1">
                                <!-- Log Level Badge -->
                                <div class="me-3">
                                    @php
                                        $levelColors = [
                                            'DEBUG' => 'bg-secondary',
                                            'INFO' => 'bg-primary',
                                            'WARNING' => 'bg-warning',
                                            'ERROR' => 'bg-danger',
                                            'CRITICAL' => 'bg-dark'
                                        ];
                                    @endphp
                                    <span class="badge {{ $levelColors[$log['level']] ?? 'bg-secondary' }}">
                                        {{ $log['level'] }}
                                    </span>
                                </div>

                                <!-- Log Content -->
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <h6 class="mb-0 me-2">{{ $log['message'] }}</h6>
                                        @php
                                            $typeColors = [
                                                'security' => 'bg-danger',
                                                'transaction' => 'bg-success',
                                                'system' => 'bg-info'
                                            ];
                                        @endphp
                                        <span class="badge {{ $typeColors[$log['type']] ?? 'bg-secondary' }} badge-sm">
                                            {{ ucfirst($log['type']) }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap text-body-secondary small gap-3">
                                        <div class="d-flex align-items-center">
                                            <svg class="icon me-1">
                                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-clock') }}"></use>
                                            </svg>
                                            {{ $log['timestamp']->format('M d, Y g:i A') }}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <svg class="icon me-1">
                                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-location-pin') }}"></use>
                                            </svg>
                                            {{ $log['ip_address'] }}
                                        </div>
                                        @if($log['user_id'])
                                            <div class="d-flex align-items-center">
                                                <svg class="icon me-1">
                                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                                                </svg>
                                                User ID: {{ $log['user_id'] }}
                                            </div>
                                        @endif
                                    </div>
                                    @if(strlen($log['user_agent']) > 50)
                                        <div class="mt-1 small text-body-secondary">
                                            <strong>User Agent:</strong> {{ Str::limit($log['user_agent'], 100) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                @if($log['level'] == 'CRITICAL' || $log['level'] == 'ERROR')
                                    <button onclick="investigateLog({{ $log['id'] }})" class="btn btn-sm btn-outline-danger">
                                        Investigate
                                    </button>
                                @endif
                                <button onclick="viewLogDetails({{ json_encode($log) }})" class="btn btn-sm btn-outline-primary">
                                    Details
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card-body text-center py-5">
            <svg class="icon icon-xxl text-body-secondary mb-3">
                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-file') }}"></use>
            </svg>
            <h5 class="text-body-secondary">No logs found</h5>
            <p class="text-body-secondary">Try adjusting your filters or check back later.</p>
        </div>
    @endif
</div>

<!-- Export Options -->
<div class="card mt-4">
    <div class="card-header">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-data-transfer-down') }}"></use>
        </svg>
        <strong>Export Options</strong>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-3">
            <button onclick="exportLogs('csv')" class="btn btn-success">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-spreadsheet') }}"></use>
                </svg>
                Export as CSV
            </button>
            <button onclick="exportLogs('json')" class="btn btn-primary">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-code') }}"></use>
                </svg>
                Export as JSON
            </button>
            <button onclick="clearOldLogs()" class="btn btn-danger">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-trash') }}"></use>
                </svg>
                Clear Old Logs (30+ days)
            </button>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="log-details-modal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailsModalLabel">Log Entry Details</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="log-details-content">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshLogs() {
    window.location.reload();
}

function viewLogDetails(log) {
    const content = document.getElementById('log-details-content');

    content.innerHTML = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Timestamp</label>
                <p class="text-body-secondary">${new Date(log.timestamp).toLocaleString()}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Level</label>
                <p class="text-body-secondary">${log.level}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Type</label>
                <p class="text-body-secondary">${log.type}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">IP Address</label>
                <p class="text-body-secondary">${log.ip_address}</p>
            </div>
            ${log.user_id ? `
            <div class="col-md-6">
                <label class="form-label fw-semibold">User ID</label>
                <p class="text-body-secondary">${log.user_id}</p>
            </div>
            ` : ''}
            <div class="col-12">
                <label class="form-label fw-semibold">Message</label>
                <p class="text-body-secondary">${log.message}</p>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">User Agent</label>
                <p class="text-body-secondary small">${log.user_agent}</p>
            </div>
        </div>
    `;

    const modal = new coreui.Modal(document.getElementById('log-details-modal'));
    modal.show();
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
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show shadow position-fixed top-0 end-0 m-3`;
    alert.style.zIndex = '1060';
    alert.innerHTML = `
        <div class="d-flex align-items-center">
            <svg class="icon me-2">
                <use xlink:href="${window.location.origin}/coreui-template/vendors/@coreui/icons/svg/free.svg#cil-${type === 'success' ? 'check' : 'x'}"></use>
            </svg>
            ${message}
        </div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;

    document.body.appendChild(alert);

    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection