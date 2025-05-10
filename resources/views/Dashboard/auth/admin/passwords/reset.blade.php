{{-- resources/views/Dashboard/auth/admin/passwords/reset.blade.php --}}
@extends('Dashboard.layouts.master-auth')
@section('title', 'إعادة تعيين كلمة المرور - مدير النظام')

@section('css')
    {{-- نفس الـ CSS من email.blade.php --}}
    <style>
        .auth-card { max-width: 450px; margin: 50px auto; border: none; border-radius: 10px; box-shadow: 0 5px 25px rgba(0,0,0,0.1); }
        .auth-card-header { background-color: #4e73df; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px; padding: 1.25rem 1.5rem; }
        .auth-card-body { padding: 2rem 2.5rem; }
        .form-control-auth { border-radius: 0.5rem; padding: 0.75rem 1rem; border: 1px solid #d1d3e2; }
        .form-control-auth:focus { border-color: #4e73df; box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25); }
        .btn-auth { background-color: #4e73df; border-color: #4e73df; color: white; padding: 0.75rem; border-radius: 0.5rem; font-weight: 600; }
        .btn-auth:hover { background-color: #2e59d9; border-color: #2653d4; }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card auth-card my-5">
                <div class="card-header auth-card-header text-center">
                     <h4 class="mb-0">إعادة تعيين كلمة المرور (مدير)</h4>
                </div>

                <div class="card-body auth-card-body">
                    <form method="POST" action="{{ route('admin.password.update') }}" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني:</label>
                            <input id="email" type="email"
                                   class="form-control form-control-auth @error('email') is-invalid @enderror"
                                   name="email" value="{{ $email ?? old('email') }}" required readonly>
                            @error('email') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور الجديدة:</label>
                            <input id="password" type="password"
                                   class="form-control form-control-auth @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password" placeholder="أدخل كلمة المرور الجديدة">
                            @error('password') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">تأكيد كلمة المرور الجديدة:</label>
                            <input id="password-confirm" type="password" class="form-control form-control-auth"
                                   name="password_confirmation" required autocomplete="new-password" placeholder="أعد إدخال كلمة المرور">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-auth btn-block">
                                <i class="fas fa-key me-2"></i>إعادة تعيين كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
