{{-- resources/views/Dashboard/auth/ray_employee/passwords/email.blade.php --}}
@extends('Dashboard.layouts.master-auth') {{-- أو أي layout تستخدمه لصفحات المصادقة --}}
@section('title', 'طلب إعادة تعيين كلمة مرور موظف الأشعة')

@section('css')
    {{-- يمكنك إضافة أي CSS خاص هنا إذا لزم الأمر --}}
    <style>
        .auth-card {
            max-width: 450px;
            margin: 50px auto;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }
        .auth-card-header {
            background-color: #4e73df; /* لون أساسي من لوحة التحكم */
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 1.25rem 1.5rem;
        }
        .auth-card-body {
            padding: 2rem 2.5rem;
        }
        .form-control-auth {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
        }
        .form-control-auth:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        .btn-auth {
            background-color: #4e73df;
            border-color: #4e73df;
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
        }
        .btn-auth:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card auth-card my-5">
                <div class="card-header auth-card-header text-center">
                    <h4 class="mb-0">إعادة تعيين كلمة المرور (موظف الأشعة)</h4>
                </div>

                <div class="card-body auth-card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ray_employee.password.email') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">البريد الإلكتروني:</label>
                            <input id="email" type="email"
                                   class="form-control form-control-auth @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autofocus
                                   placeholder="ادخل بريدك الإلكتروني المسجل">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-auth btn-block">
                                <i class="fas fa-paper-plane me-2"></i>إرسال رابط إعادة التعيين
                            </button>
                        </div>
                    </form>

                    @if (Route::has('ray_employee.login')) {{-- افترض أن لديك route تسجيل دخول للأدمن --}}
                        <div class="text-center mt-4">
                            <a href="{{ route('ray_employee.login') }}">العودة لتسجيل الدخول</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
