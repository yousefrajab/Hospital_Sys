{{-- "Set a new password" (from the emailed link) for any role. Include with ['role' => ..., 'token' => ..., 'email' => ...]. --}}
@extends('Dashboard.layouts.auth')
@section('title', trans('ui.auth.reset_title'))


@section('form')
    <h2>{{ trans('ui.auth.reset_title') }}</h2>
    <p class="text-muted">{{ trans('ui.auth.reset_text') }}</p>

    <form method="POST" action="{{ route($role . '.password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <label for="email">{{ trans('ui.auth.email') }}</label>
            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">{{ trans('ui.auth.new_password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" autofocus
                class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">{{ trans('ui.auth.confirm_password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ trans('ui.auth.save_password') }}</button>
    </form>

    <p class="hms-auth__register"><a href="{{ route('login', ['role' => $role]) }}"><i class="fas fa-arrow-right hms-flip-ltr"></i> {{ trans('ui.auth.back_to_login') }}</a></p>
@endsection

@section('content')
    @include('Dashboard.auth.passwords.frame', ['icon' => 'fa-lock'])
@endsection
