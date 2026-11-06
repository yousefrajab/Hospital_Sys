{{-- "Forgot password" for any role. Include with ['role' => 'doctor'] etc. --}}
@extends('Dashboard.layouts.auth')
@section('title', trans('ui.auth.forgot_title'))


@section('form')
    <h2>{{ trans('ui.auth.forgot_title') }}</h2>
    <p class="text-muted">{{ trans('ui.auth.forgot_text') }}</p>
    <span class="hms-pill hms-tone-primary mb-3">{{ trans('ui.auth.account', ['role' => trans('ui.roles.' . $role)]) }}</span>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route($role . '.password.email') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="email">{{ trans('ui.auth.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ trans('ui.auth.send_link') }}</button>
    </form>

    <p class="hms-auth__register"><a href="{{ route('login', ['role' => $role]) }}"><i class="fas fa-arrow-right hms-flip-ltr"></i> {{ trans('ui.auth.back_to_login') }}</a></p>
@endsection

@section('content')
    @include('Dashboard.auth.passwords.frame', ['icon' => 'fa-key'])
@endsection
