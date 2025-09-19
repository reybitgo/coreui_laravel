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
                        <h1 class="text-2xl font-bold text-gray-900">System Reports</h1>
                        <p class="mt-1 text-sm text-gray-600">Generate comprehensive system and business reports</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="refreshStats()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh Stats
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_users']) }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-sm text-green-600">+{{ $stats['new_users_this_month'] }} this month</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Transactions</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_transactions']) }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-sm text-gray-500">{{ $stats['approved_transactions'] }} approved, {{ $stats['pending_transactions'] }} pending</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Transaction Volume</dt>
                                <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['total_volume'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-sm text-green-600">+12.5% from last month</span>
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Rejected Transactions</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['rejected_transactions'] }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-sm text-red-500">{{ number_format(($stats['rejected_transactions']/$stats['total_transactions'])*100, 1) }}% rejection rate</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Report Generation Form -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Generate New Report</h3>
                    <form id="report-form" class="space-y-6">
                        @csrf
                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700">Report Type</label>
                            <select id="report_type" name="report_type" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Report Type</option>
                                <option value="users">User Activity Report</option>
                                <option value="transactions">Transaction Report</option>
                                <option value="financial">Financial Summary Report</option>
                                <option value="security">Security Audit Report</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_range" class="block text-sm font-medium text-gray-700">Date Range</label>
                            <select id="date_range" name="date_range" required onchange="toggleCustomDates()"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Date Range</option>
                                <option value="today">Today</option>
                                <option value="week">Last 7 Days</option>
                                <option value="month">Last 30 Days</option>
                                <option value="quarter">Last Quarter</option>
                                <option value="year">Last Year</option>
                                <option value="custom">Custom Date Range</option>
                            </select>
                        </div>

                        <div id="custom-dates" class="hidden space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                                    <input type="date" id="date_from" name="date_from"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" id="date_to" name="date_to"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="format" class="block text-sm font-medium text-gray-700">Export Format</label>
                            <select id="format" name="format" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Format</option>
                                <option value="pdf">PDF Document</option>
                                <option value="csv">CSV Spreadsheet</option>
                                <option value="excel">Excel Workbook</option>
                            </select>
                        </div>

                        <div class="pt-5">
                            <button type="submit" id="generate-btn"
                                class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Report Templates -->
            <div class="space-y-6">
                <!-- Pre-built Report Templates -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Report Templates</h3>
                        <div class="space-y-3">
                            <button onclick="generateQuickReport('daily-summary')" class="w-full bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-4 text-left transition-colors">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Daily Summary Report</h4>
                                        <p class="text-sm text-gray-500">Today's transactions and user activity</p>
                                    </div>
                                </div>
                            </button>

                            <button onclick="generateQuickReport('weekly-financial')" class="w-full bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 text-left transition-colors">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Weekly Financial Report</h4>
                                        <p class="text-sm text-gray-500">7-day financial performance summary</p>
                                    </div>
                                </div>
                            </button>

                            <button onclick="generateQuickReport('security-audit')" class="w-full bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg p-4 text-left transition-colors">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Security Audit Report</h4>
                                        <p class="text-sm text-gray-500">Monthly security events and threats</p>
                                    </div>
                                </div>
                            </button>

                            <button onclick="generateQuickReport('compliance')" class="w-full bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 rounded-lg p-4 text-left transition-colors">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Compliance Report</h4>
                                        <p class="text-sm text-gray-500">Regulatory compliance summary</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Reports</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Financial Summary - December 2024</h4>
                                    <p class="text-sm text-gray-500">Generated 2 hours ago • 45.2 KB</p>
                                </div>
                                <button class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Download
                                </button>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">User Activity Report - Q4 2024</h4>
                                    <p class="text-sm text-gray-500">Generated yesterday • 127.8 KB</p>
                                </div>
                                <button class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Download
                                </button>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Security Audit - November 2024</h4>
                                    <p class="text-sm text-gray-500">Generated 3 days ago • 89.4 KB</p>
                                </div>
                                <button class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                  document.querySelector('input[name="_token"]')?.value;

function toggleCustomDates() {
    const dateRange = document.getElementById('date_range').value;
    const customDates = document.getElementById('custom-dates');

    if (dateRange === 'custom') {
        customDates.classList.remove('hidden');
        document.getElementById('date_from').required = true;
        document.getElementById('date_to').required = true;
    } else {
        customDates.classList.add('hidden');
        document.getElementById('date_from').required = false;
        document.getElementById('date_to').required = false;
    }
}

function refreshStats() {
    showAlert('Statistics refreshed successfully', 'success');
    window.location.reload();
}

// Handle report generation form
document.getElementById('report-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const generateBtn = document.getElementById('generate-btn');
    const originalText = generateBtn.innerHTML;

    // Show loading state
    generateBtn.disabled = true;
    generateBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Generating...
    `;

    const data = {
        report_type: formData.get('report_type'),
        date_range: formData.get('date_range'),
        format: formData.get('format'),
        date_from: formData.get('date_from'),
        date_to: formData.get('date_to')
    };

    fetch('/admin/reports/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(`Report generated successfully! File: ${data.report.filename} (${data.report.size})`, 'success');
            // Reset form
            document.getElementById('report-form').reset();
            toggleCustomDates();
        } else {
            showAlert(data.message || 'Failed to generate report', 'error');
        }
    })
    .catch(error => {
        showAlert('Error generating report: ' + error.message, 'error');
    })
    .finally(() => {
        // Reset button state
        generateBtn.disabled = false;
        generateBtn.innerHTML = originalText;
    });
});

function generateQuickReport(template) {
    const templates = {
        'daily-summary': {
            report_type: 'transactions',
            date_range: 'today',
            format: 'pdf'
        },
        'weekly-financial': {
            report_type: 'financial',
            date_range: 'week',
            format: 'excel'
        },
        'security-audit': {
            report_type: 'security',
            date_range: 'month',
            format: 'pdf'
        },
        'compliance': {
            report_type: 'financial',
            date_range: 'quarter',
            format: 'csv'
        }
    };

    const config = templates[template];
    if (!config) return;

    fetch('/admin/reports/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(config)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(`Quick report generated! File: ${data.report.filename} (${data.report.records} records)`, 'success');
        } else {
            showAlert(data.message || 'Failed to generate quick report', 'error');
        }
    })
    .catch(error => {
        showAlert('Error generating quick report: ' + error.message, 'error');
    });
}

function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alert-container');
    const alertClass = type === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400';

    const alert = document.createElement('div');
    alert.className = `${alertClass} border px-4 py-3 rounded mb-4 shadow-lg max-w-sm`;
    alert.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="float-right text-xl leading-none">&times;</button>
    `;

    alertContainer.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}
</script>
@endsection