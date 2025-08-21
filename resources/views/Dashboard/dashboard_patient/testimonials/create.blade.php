@extends('Dashboard.layouts.master') {{-- افترض أن هذا هو الـ layout الرئيسي للوحة تحكم المريض --}}

@section('title', 'إضافة تعليق جديد')


@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        {{-- <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('patients.patient_operations') }}</h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ trans('patients.payments') }}</span>
            </div>
        </div> --}}
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">{{ trans('patients.add_your_testimonial') }}</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('dashboard.patient') }}">{{ trans('patients.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('patients.add_comment_breadcrumb') }}</li>
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
                        <h4 class="card-title">{{ trans('patients.share_your_opinion') }}</h4>
                        <p class="text-muted mb-0">{{ trans('patients.we_value_your_feedback') }}</p>
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
                                <label for="patient_name"
                                    class="form-label">{{ trans('patients.your_name_in_comment') }}</label>
                                <input type="text" class="form-control" id="patient_name"
                                    value="{{ Auth::guard('patient')->user()->name }}" readonly>
                                <small
                                    class="form-text text-muted">{{ trans('patients.your_current_name_will_be_used') }}</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="comment" class="form-label">{{ trans('patients.your_comment') }} <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="5"
                                    placeholder="{{ trans('patients.write_your_comment_here') }}" required>{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mt-4 text-center">
                                <button type="submit"
                                    class="btn btn-primary">{{ trans('patients.submit_comment_for_review') }}</button>
                                <a href="{{ route('dashboard.patient') }}"
                                    class="btn btn-outline-secondary ms-2">{{ trans('doctors.cancel') }}</a>
                            </div>
                        </form>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
@endsection
