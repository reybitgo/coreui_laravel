@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<!-- Page Header -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-settings') }}"></use>
                    </svg>
                    System Settings
                </h4>
                <p class="text-body-secondary mb-0">Configure system parameters and security settings</p>
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

<div class="row g-3">
    <!-- Navigation Menu -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                </svg>
                <strong>Settings Categories</strong>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="#general" onclick="showSection('general')" class="settings-nav-item list-group-item list-group-item-action active">
                        <svg class="icon me-3">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-settings') }}"></use>
                        </svg>
                        General Settings
                    </a>
                    <a href="#security" onclick="showSection('security')" class="settings-nav-item list-group-item list-group-item-action">
                        <svg class="icon me-3">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-shield-alt') }}"></use>
                        </svg>
                        Security
                    </a>
                    <a href="#wallet" onclick="showSection('wallet')" class="settings-nav-item list-group-item list-group-item-action">
                        <svg class="icon me-3">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wallet') }}"></use>
                        </svg>
                        Wallet Settings
                    </a>
                    <a href="#notifications" onclick="showSection('notifications')" class="settings-nav-item list-group-item list-group-item-action">
                        <svg class="icon me-3">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
                        </svg>
                        Notifications
                    </a>
                    <a href="#maintenance" onclick="showSection('maintenance')" class="settings-nav-item list-group-item list-group-item-action">
                        <svg class="icon me-3">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wrench') }}"></use>
                        </svg>
                        Maintenance
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="col-lg-9">
        <!-- General Settings -->
        <div id="general-section" class="settings-section">
            <div class="card mb-4">
                <div class="card-header">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-info') }}"></use>
                    </svg>
                    <strong>System Information</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card bg-primary-subtle border-primary">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-primary">Laravel Version</h6>
                                    <h4 class="text-primary">{{ $systemStats['laravel_version'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success-subtle border-success">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-success">PHP Version</h6>
                                    <h4 class="text-success">{{ $systemStats['php_version'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info-subtle border-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-info">Database Size</h6>
                                    <h4 class="text-info">{{ $systemStats['database_size'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning-subtle border-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-warning">Total Users</h6>
                                    <h4 class="text-warning">{{ $systemStats['total_users'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger-subtle border-danger">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-danger">Total Transactions</h6>
                                    <h4 class="text-danger">{{ $systemStats['total_transactions'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-secondary-subtle border-secondary">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-secondary">Storage Available</h6>
                                    <h4 class="text-secondary">{{ $systemStats['storage_used'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-settings') }}"></use>
                    </svg>
                    <strong>General Settings</strong>
                </div>
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="app_name" class="form-label">Application Name</label>
                                <input type="text" id="app_name" name="app_name" value="E-Wallet System" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="app_url" class="form-label">Application URL</label>
                                <input type="url" id="app_url" name="app_url" value="http://127.0.0.1:8000" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="app_description" class="form-label">Application Description</label>
                            <textarea id="app_description" name="app_description" rows="3" class="form-control">Secure digital wallet platform for financial transactions</textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="timezone" class="form-label">System Timezone</label>
                                <select id="timezone" name="timezone" class="form-select">
                                    <option value="UTC" selected>UTC</option>
                                    <option value="America/New_York">Eastern Time</option>
                                    <option value="America/Chicago">Central Time</option>
                                    <option value="America/Denver">Mountain Time</option>
                                    <option value="America/Los_Angeles">Pacific Time</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="language" class="form-label">Default Language</label>
                                <select id="language" name="language" class="form-select">
                                    <option value="en" selected>English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                    <option value="de">German</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" onclick="saveSettings('general')" class="btn btn-primary">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-save') }}"></use>
                            </svg>
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div id="security-section" class="settings-section d-none">
            <div class="card">
                <div class="card-header">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-shield-alt') }}"></use>
                    </svg>
                    <strong>Security Settings</strong>
                </div>
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="mb-4">
                            <h6 class="mb-3">Authentication Settings</h6>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="require_2fa" name="require_2fa"
                                    {{ isset($settings['require_2fa']) && $settings['require_2fa']->value ? 'checked' : '' }}>
                                <label class="form-check-label" for="require_2fa">
                                    <strong>Require Two-Factor Authentication</strong>
                                    <div class="text-body-secondary small">Force all users to enable 2FA for enhanced security</div>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="email_verification_enabled" name="email_verification_enabled"
                                    {{ isset($settings['email_verification_enabled']) && $settings['email_verification_enabled']->value ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_verification_enabled">
                                    <strong>Email Verification Required</strong>
                                    <div class="text-body-secondary small">When disabled, users can login without verifying their email address</div>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="session_timeout" name="session_timeout" checked>
                                <label class="form-check-label" for="session_timeout">
                                    <strong>Automatic Session Timeout</strong>
                                    <div class="text-body-secondary small">Sessions expire after 120 minutes of inactivity</div>
                                </label>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="max_login_attempts" class="form-label">Max Login Attempts</label>
                                <input type="number" id="max_login_attempts" name="max_login_attempts" value="3" min="1" max="10" class="form-control">
                                <div class="form-text">Account locked after this many failed attempts</div>
                            </div>
                            <div class="col-md-6">
                                <label for="lockout_duration" class="form-label">Lockout Duration (minutes)</label>
                                <input type="number" id="lockout_duration" name="lockout_duration" value="15" min="1" max="1440" class="form-control">
                                <div class="form-text">How long accounts remain locked</div>
                            </div>
                        </div>
                        <button type="button" onclick="saveSettings('security')" class="btn btn-primary">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-save') }}"></use>
                            </svg>
                            Update Security Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Wallet Settings -->
        <div id="wallet-section" class="settings-section d-none">
            <div class="card">
                <div class="card-header">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wallet') }}"></use>
                    </svg>
                    <strong>Wallet Settings</strong>
                </div>
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="min_deposit" class="form-label">Minimum Deposit Amount ($)</label>
                                <input type="number" id="min_deposit" name="min_deposit" value="1.00" min="0" step="0.01" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="max_deposit" class="form-label">Maximum Deposit Amount ($)</label>
                                <input type="number" id="max_deposit" name="max_deposit" value="10000.00" min="1" step="0.01" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="min_withdrawal" class="form-label">Minimum Withdrawal Amount ($)</label>
                                <input type="number" id="min_withdrawal" name="min_withdrawal" value="1.00" min="0" step="0.01" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="max_withdrawal" class="form-label">Maximum Withdrawal Amount ($)</label>
                                <input type="number" id="max_withdrawal" name="max_withdrawal" value="10000.00" min="1" step="0.01" class="form-control">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Fee Configuration</h6>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="withdrawal_fee_enabled" name="withdrawal_fee_enabled"
                                {{ isset($settings['withdrawal_fee_enabled']) && $settings['withdrawal_fee_enabled']->value ? 'checked' : '' }}>
                            <label class="form-check-label" for="withdrawal_fee_enabled">
                                <strong>Enable Withdrawal Fees</strong>
                                <div class="text-body-secondary small">Charge fees for withdrawal transactions</div>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="transfer_fee_enabled" name="transfer_fee_enabled"
                                {{ isset($settings['transfer_charge_enabled']) && $settings['transfer_charge_enabled']->value ? 'checked' : '' }}>
                            <label class="form-check-label" for="transfer_fee_enabled">
                                <strong>Enable Transfer Fees</strong>
                                <div class="text-body-secondary small">Charge fees for fund transfers between users</div>
                            </label>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading">Transaction Approval Policy</h6>
                            <ul class="mb-0">
                                <li><strong>Withdrawals:</strong> Always require manual admin approval</li>
                                <li><strong>Deposits:</strong> Always require manual admin approval</li>
                                <li><strong>Transfers:</strong> Process automatically (user-to-user transfers)</li>
                            </ul>
                        </div>

                        <button type="button" onclick="saveSettings('wallet')" class="btn btn-primary">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-save') }}"></use>
                            </svg>
                            Save Wallet Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notifications Settings -->
        <div id="notifications-section" class="settings-section d-none">
            <div class="card">
                <div class="card-header">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
                    </svg>
                    <strong>Notification Settings</strong>
                </div>
                <div class="card-body">
                    <form>
                        @csrf
                        <h6 class="mb-3">Email Notifications</h6>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_new_user" name="notify_new_user" checked>
                            <label class="form-check-label" for="notify_new_user">
                                <strong>New User Registration</strong>
                                <div class="text-body-secondary small">Notify admins when new users register</div>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_large_transaction" name="notify_large_transaction" checked>
                            <label class="form-check-label" for="notify_large_transaction">
                                <strong>Large Transactions</strong>
                                <div class="text-body-secondary small">Notify admins of transactions over the review threshold</div>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_suspicious" name="notify_suspicious" checked>
                            <label class="form-check-label" for="notify_suspicious">
                                <strong>Suspicious Activity</strong>
                                <div class="text-body-secondary small">Immediate alerts for flagged transactions</div>
                            </label>
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Admin Email Address</label>
                            <input type="email" id="admin_email" name="admin_email" value="admin@example.com" class="form-control">
                            <div class="form-text">Primary email for system notifications</div>
                        </div>
                        <button type="button" onclick="saveSettings('notifications')" class="btn btn-primary">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-save') }}"></use>
                            </svg>
                            Save Notification Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Maintenance Settings -->
        <div id="maintenance-section" class="settings-section d-none">
            <div class="card">
                <div class="card-header">
                    <svg class="icon me-2">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wrench') }}"></use>
                    </svg>
                    <strong>Maintenance Settings</strong>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Maintenance Mode</h6>
                        <p class="mb-3">System is currently operational. Use maintenance mode for scheduled updates.</p>
                        <button type="button" onclick="toggleMaintenanceMode()" class="btn btn-warning">
                            Enable Maintenance Mode
                        </button>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Database Backup</h6>
                                <p class="mb-3">Last backup: 2 hours ago</p>
                                <button type="button" onclick="createBackup()" class="btn btn-info">
                                    Create Backup Now
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                <h6 class="alert-heading">Cache Management</h6>
                                <p class="mb-3">System cache status: Active</p>
                                <button type="button" onclick="clearCache()" class="btn btn-success">
                                    Clear All Caches
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-danger">
                        <h6 class="alert-heading">Danger Zone</h6>
                        <p class="mb-3">These actions are irreversible. Please proceed with caution.</p>
                        <button type="button" onclick="resetSystem()" class="btn btn-danger">
                            Reset System Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(section => {
        section.classList.add('d-none');
    });

    // Show selected section
    document.getElementById(sectionName + '-section').classList.remove('d-none');

    // Update navigation
    document.querySelectorAll('.settings-nav-item').forEach(item => {
        item.classList.remove('active');
    });

    event.target.classList.add('active');
}

function saveSettings(category) {
    if (category === 'security') {
        // Get security settings values
        const emailVerificationEnabled = document.getElementById('email_verification_enabled').checked;
        const require2fa = document.getElementById('require_2fa').checked;


        // Send AJAX request to update settings
        fetch('{{ route("admin.system.settings.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                email_verification_enabled: emailVerificationEnabled,
                require_2fa: require2fa,
                maintenance_mode: false
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Security settings updated successfully!', 'success');
            } else {
                showAlert('Error updating settings: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Error updating settings. Please try again.', 'error');
        });
    } else if (category === 'wallet') {
        // Get withdrawal fee settings values
        const withdrawalFeeEnabled = document.getElementById('withdrawal_fee_enabled').checked;
        const withdrawalFeeType = document.getElementById('withdrawal_fee_type').value;
        const withdrawalFeeValue = parseFloat(document.getElementById('withdrawal_fee_value').value) || 0;
        const withdrawalMinimumFee = parseFloat(document.getElementById('withdrawal_minimum_fee').value) || 0;
        const withdrawalMaximumFee = parseFloat(document.getElementById('withdrawal_maximum_fee').value) || 0;

        // Get transfer fee settings values
        const transferFeeEnabled = document.getElementById('transfer_fee_enabled').checked;
        const transferFeeType = document.getElementById('transfer_fee_type').value;
        const transferFeeValue = parseFloat(document.getElementById('transfer_fee_value').value) || 0;
        const transferMinimumFee = parseFloat(document.getElementById('transfer_minimum_fee').value) || 0;
        const transferMaximumFee = parseFloat(document.getElementById('transfer_maximum_fee').value) || 0;

        // Send AJAX request to update wallet settings
        fetch('{{ route("admin.system.settings.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                withdrawal_fee_enabled: withdrawalFeeEnabled,
                withdrawal_fee_type: withdrawalFeeType,
                withdrawal_fee_value: withdrawalFeeValue,
                withdrawal_minimum_fee: withdrawalMinimumFee,
                withdrawal_maximum_fee: withdrawalMaximumFee,
                transfer_fee_enabled: transferFeeEnabled,
                transfer_fee_type: transferFeeType,
                transfer_fee_value: transferFeeValue,
                transfer_minimum_fee: transferMinimumFee,
                transfer_maximum_fee: transferMaximumFee
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Wallet settings updated successfully!', 'success');
            } else {
                showAlert('Error updating settings: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Error updating settings. Please try again.', 'error');
        });
    } else {
        // Simulate saving other categories
        showAlert(`${category.charAt(0).toUpperCase() + category.slice(1)} settings saved successfully!`, 'success');
    }
}

function toggleMaintenanceMode() {
    if (confirm('Are you sure you want to enable maintenance mode? This will prevent users from accessing the system.')) {
        showAlert('Maintenance mode enabled. System is now offline for users.', 'success');
    }
}

function createBackup() {
    showAlert('Database backup started. You will be notified when complete.', 'success');
}

function clearCache() {
    if (confirm('Are you sure you want to clear all caches? This may temporarily slow down the system.')) {
        showAlert('All caches cleared successfully.', 'success');
    }
}

function resetSystem() {
    if (confirm('Are you sure you want to reset all system settings? This action cannot be undone.')) {
        if (confirm('This will reset ALL settings to default values. Are you absolutely sure?')) {
            showAlert('System settings have been reset to defaults.', 'success');
        }
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