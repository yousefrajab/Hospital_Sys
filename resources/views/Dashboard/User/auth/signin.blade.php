@php
    // One form for all seven roles: the chosen role sets where it posts and the forgot-password link.
    $roles = [
        'patient' => 'fa-user-injured',
        'doctor' => 'fa-user-md',
        'admin' => 'fa-user-shield',
        'ray_employee' => 'fa-x-ray',
        'laboratorie_employee' => 'fa-vials',
        'pharmacy_employee' => 'fa-prescription-bottle-alt',
        'pharmacy_manager' => 'fa-clinic-medical',
    ];
    $selected = old('login_role', array_key_exists(request('role'), $roles) ? request('role') : 'patient');
    $routes = [];
    foreach (array_keys($roles) as $role) {
        $routes[$role] = [
            'action' => route('login.' . $role),
            'forgot' => Route::has($role . '.password.request') ? route($role . '.password.request') : null,
        ];
    }
@endphp
@extends('Dashboard.layouts.auth')

@section('title', trans('ui.auth.submit'))

@section('content')
    <div class="hms-auth">
        <section class="hms-auth__hero" aria-hidden="true">
            <div class="hms-auth__hero-inner">
                <div class="hms-auth__brand">
                    <span class="hms-brand__mark"><i class="fas fa-plus"></i></span>
                    <span>{{ config('hospital.name') ?: trans('ui.app_name') }}</span>
                </div>
                <h1>{{ trans('ui.auth.hero_title') }}</h1>
                <p>{{ trans('ui.auth.hero_text') }}</p>
                <ul>
                    <li><i class="fas fa-calendar-check"></i> {{ trans('ui.auth.feature_1') }}</li>
                    <li><i class="fas fa-notes-medical"></i> {{ trans('ui.auth.feature_2') }}</li>
                    <li><i class="fas fa-file-invoice-dollar"></i> {{ trans('ui.auth.feature_3') }}</li>
                </ul>
            </div>
        </section>

        <section class="hms-auth__panel">
            <div class="hms-auth__top">
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    @if ($localeCode !== App::getLocale())
                        <a class="hms-icon-btn hms-icon-btn--text" hreflang="{{ $localeCode }}"
                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            <i class="fas fa-globe"></i> {{ $properties['native'] }}
                        </a>
                    @endif
                @endforeach
                <button type="button" class="hms-icon-btn" data-hms-toggle="theme" aria-label="{{ trans('ui.dark_mode') }}">
                    <i class="fas fa-moon hms-when-light"></i><i class="fas fa-sun hms-when-dark"></i>
                </button>
            </div>

            <div class="hms-auth__card">
                <h2>{{ trans('ui.auth.welcome') }}</h2>
                <p class="text-muted">{{ trans('ui.auth.subtitle') }}</p>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong class="d-block">{{ trans('ui.auth.failed') }}</strong>
                        @foreach ($errors->all() as $error)
                            <span class="d-block">{{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ $routes[$selected]['action'] }}" id="hmsLoginForm" novalidate>
                    @csrf
                    <fieldset class="hms-roles">
                        <legend>{{ trans('ui.auth.choose_role') }}</legend>
                        @foreach ($roles as $role => $icon)
                            <label class="hms-role">
                                <input type="radio" name="login_role" value="{{ $role }}" @checked($selected === $role)
                                    data-action="{{ $routes[$role]['action'] }}" data-forgot="{{ $routes[$role]['forgot'] }}">
                                <span><i class="fas {{ $icon }}"></i>{{ trans('ui.roles.' . $role) }}</span>
                            </label>
                        @endforeach
                    </fieldset>

                    <div class="form-group">
                        <label for="email">{{ trans('ui.auth.email') }}</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" autocomplete="username" required autofocus>
                    </div>

                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password">{{ trans('ui.auth.password') }}</label>
                            <a href="{{ $routes[$selected]['forgot'] ?? '#' }}" id="hmsForgot" class="small"
                                @if (! $routes[$selected]['forgot']) hidden @endif>{{ trans('ui.auth.forgot') }}</a>
                        </div>
                        <div class="hms-password">
                            <input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required>
                            <button type="button" class="hms-icon-btn" id="hmsShowPassword" aria-label="{{ trans('ui.auth.show_password') }}">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="1" @checked(old('remember'))>
                        <label class="custom-control-label" for="remember">{{ trans('ui.auth.remember') }}</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{ trans('ui.auth.submit') }}</button>
                </form>

                @if (Route::has('register.patient'))
                    <p class="hms-auth__register" id="hmsRegister" @if ($selected !== 'patient') hidden @endif>
                        {{ trans('ui.auth.no_account') }} <a href="{{ route('register.patient') }}">{{ trans('ui.auth.register') }}</a>
                    </p>
                @endif
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script>
        (function () {
            var form = document.getElementById('hmsLoginForm');
            var forgot = document.getElementById('hmsForgot');
            var register = document.getElementById('hmsRegister');
            form.addEventListener('change', function (e) {
                if (e.target.name !== 'login_role') return;
                form.action = e.target.dataset.action;
                if (forgot) {
                    forgot.hidden = !e.target.dataset.forgot;
                    forgot.href = e.target.dataset.forgot || '#';
                }
                if (register) register.hidden = e.target.value !== 'patient';
            });
            document.getElementById('hmsShowPassword').addEventListener('click', function () {
                var input = document.getElementById('password');
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                this.querySelector('i').className = show ? 'far fa-eye-slash' : 'far fa-eye';
            });
        })();
    </script>
@endsection
