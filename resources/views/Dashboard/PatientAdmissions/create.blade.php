@extends('Dashboard.layouts.master')

@section('title')
    تسجيل دخول مريض جديد
@endsection

@section('css')
    <!-- Select2 CSS -->
    <link href="{{ asset('dashboard/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/assets/plugins/select2-bootstrap5-theme/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* يمكنك إضافة أي CSS مخصص هنا */
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">{{ trans('AdminSb.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.patient_admissions.index') }}">{{ trans('AdminSb.patient_admission_records') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('AdminSb.new_admission') }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ trans('AdminSb.admit_new_patient') }}</h4>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        @if ($errors->createAdmissionFormBag->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->createAdmissionFormBag->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('admin.patient_admissions.store') }}" method="POST" id="createAdmissionForm">
            @csrf

            @include('Dashboard.PatientAdmissions.partials._form', [
                'patients' => $patients,
                'doctors' => $doctors,
                'sections' => $sections,
                'availableBeds' => $availableBeds,
                'admissionStatuses' => $admissionStatuses,
            ])

            <div class="row">
                <div class="col-12 text-center mt-3 mb-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> {{ trans('AdminSb.save_and_admit') }}</button>
                    <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> {{ trans('AdminSb.cancel') }}</a>
                </div>
            </div>
        </form>

    </div><!-- container -->
@endsection

@section('js')
    <script src="{{ asset('dashboard/assets/plugins/select2/select2.full.min.js') }}"></script>
    @stack('js_after_form')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

        });
    </script>
@endsection
