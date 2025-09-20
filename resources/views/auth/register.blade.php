@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4 mx-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img class="logo-dark" src="{{ asset('coreui-template/assets/brand/gawis_logo.png') }}" width="110" height="39" alt="{{ config('app.name', 'Gawis iHerbal') }} Logo" />
                            <img class="logo-light" src="{{ asset('coreui-template/assets/brand/gawis_logo_light.png') }}" width="110" height="39" alt="{{ config('app.name', 'Gawis iHerbal') }} Logo" />
                        </div>
                        <h1>Register</h1>
                        <p class="text-body-secondary">Create your account</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                                    </svg>
                                </span>
                                <input class="form-control @error('name') is-invalid @enderror"
                                       type="text"
                                       name="name"
                                       id="name"
                                       placeholder="Full Name"
                                       value="{{ old('name') }}"
                                       autocomplete="name"
                                       required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                                    </svg>
                                </span>
                                <input class="form-control @error('username') is-invalid @enderror"
                                       type="text"
                                       name="username"
                                       id="username"
                                       placeholder="Username"
                                       value="{{ old('username') }}"
                                       autocomplete="username"
                                       required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-envelope-open') }}"></use>
                                    </svg>
                                </span>
                                <input class="form-control @error('email') is-invalid @enderror"
                                       type="email"
                                       name="email"
                                       id="email"
                                       placeholder="Email"
                                       value="{{ old('email') }}"
                                       autocomplete="email"
                                       required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                                    </svg>
                                </span>
                                <input class="form-control @error('password') is-invalid @enderror"
                                       type="password"
                                       name="password"
                                       id="password"
                                       placeholder="Password"
                                       autocomplete="new-password"
                                       required>
                            </div>

                            <div class="input-group mb-4">
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('coreui-template/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                                    </svg>
                                </span>
                                <input class="form-control"
                                       type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       placeholder="Repeat password"
                                       autocomplete="new-password"
                                       required>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a>
                                    and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                </label>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-success" type="submit">Create Account</button>
                            </div>

                            <div class="text-center mt-3">
                                <p class="text-body-secondary">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="text-decoration-none">Sign in here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection