@extends('Dashboard.layouts.master') {{-- افترض أن هذا هو الـ layout الرئيسي للوحة تحكم المريض --}}

@section('title', 'إضافة تعليق جديد')

@section('content')
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">إضافة رأيك/تعليقك</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.patient') }}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">إضافة تعليق</li>
                            </ol>
                        </div>
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">شاركنا رأيك حول خدماتنا</h4>
                        <p class="text-muted mb-0">نقدر ملاحظاتك ونسعى دائمًا لتحسين خدماتنا بناءً على آرائكم.</p>
                    </div><!--end card-header-->
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('warning'))
                            <div class="alert alert-warning">
                                {{ session('warning') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('patient.testimonials.store') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="patient_name" class="form-label">اسمك (كما سيظهر في التعليق)</label>
                                <input type="text" class="form-control" id="patient_name"
                                       value="{{ Auth::guard('patient')->user()->name }}" readonly>
                                <small class="form-text text-muted">سيتم استخدام اسم حسابك الحالي.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="comment" class="form-label">تعليقك <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('comment') is-invalid @enderror"
                                          id="comment" name="comment" rows="5"
                                          placeholder="اكتب تعليقك هنا..." required>{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mt-4 text-center">
                                <button type="submit" class="btn btn-primary">إرسال التعليق للمراجعة</button>
                                <a href="{{ route('dashboard.patient') }}" class="btn btn-outline-secondary ms-2">إلغاء</a>
                            </div>
                        </form>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
@endsection
