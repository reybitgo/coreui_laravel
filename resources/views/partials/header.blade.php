<header class="header header-sticky p-0 mb-4">
    <div class="container-fluid px-4 border-bottom">
        <button class="header-toggler" type="button" onclick='coreui.Sidebar.getInstance(document.querySelector("#sidebar")).toggle()' style="margin-inline-start: -14px">
            <svg class="icon icon-lg">
                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
            </svg>
        </button>
        <ul class="header-nav d-none d-md-flex">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            @if(auth()->user()->hasRole('admin'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users') }}">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.system.settings') }}">Settings</a>
            </li>
            @endif
        </ul>
        <ul class="header-nav ms-auto">
            <!-- Theme Switcher -->
            <li class="nav-item">
                <div class="dropdown">
                    <button class="btn btn-link nav-link" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                        <svg class="icon icon-lg theme-icon-active">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-contrast') }}"></use>
                        </svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem">
                        <li>
                            <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="light">
                                <svg class="icon icon-lg me-3">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-sun') }}"></use>
                                </svg>
                                <span>Light</span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                                <svg class="icon icon-lg me-3">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-moon') }}"></use>
                                </svg>
                                <span>Dark</span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="auto">
                                <svg class="icon icon-lg me-3">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-contrast') }}"></use>
                                </svg>
                                <span>Auto</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <!-- Notifications -->
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 pe-2" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
                    </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0 w-auto">
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                        E-Wallet Notifications
                    </div>
                    @if(auth()->user()->hasRole('admin'))
                        @php
                            $pendingTransactionsCount = \App\Models\Transaction::where('status', 'pending')->count();
                        @endphp
                        @if($pendingTransactionsCount > 0)
                            <a class="dropdown-item" href="{{ route('admin.transaction.approval') }}">
                                <svg class="icon me-2 text-warning">
                                    <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-warning') }}"></use>
                                </svg>
                                {{ $pendingTransactionsCount }} pending transaction(s)
                            </a>
                        @endif
                    @endif
                    <!-- Wallet Balance Display -->
                    @if(auth()->user()->wallet)
                        <a class="dropdown-item" href="#">
                            <svg class="icon me-2 text-success">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-wallet') }}"></use>
                            </svg>
                            Balance: ${{ number_format(auth()->user()->wallet->balance, 2) }}
                        </a>
                    @endif
                    @if(!isset($pendingTransactionsCount) || $pendingTransactionsCount == 0)
                        <div class="dropdown-item text-center border-top">
                            <strong>No new notifications</strong>
                        </div>
                    @endif
                </div>
            </li>
            <!-- User Profile -->
            <li class="nav-item dropdown">
                <a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md">
                        <div class="avatar-img bg-primary text-white d-flex align-items-center justify-content-center">
                            {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0 w-auto">
                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                        </svg>
                        <span>Profile</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}"></use>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <div class="container-fluid px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                    @foreach($breadcrumbs as $breadcrumb)
                        @if(isset($breadcrumb['url']) && !$loop->last)
                            <li class="breadcrumb-item">
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                            </li>
                        @else
                            <li class="breadcrumb-item active">
                                <span>{{ $breadcrumb['title'] }}</span>
                            </li>
                        @endif
                    @endforeach
                @else
                    <li class="breadcrumb-item active">
                        <span>@yield('page-title', 'Dashboard')</span>
                    </li>
                @endif
            </ol>
        </nav>
    </div>
</header>