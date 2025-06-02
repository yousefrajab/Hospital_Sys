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
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">لوحة التحكم</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.patient_admissions.index') }}">سجلات دخول المرضى</a></li>
                            <li class="breadcrumb-item active">تسجيل دخول جديد</li>
                        </ol>
                    </div>
                    <h4 class="page-title">تسجيل دخول مريض جديد</h4>
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
                'patients' => $patients, // من المتحكم
                'doctors' => $doctors,   // من المتحكم
                'sections' => $sections, // من المتحكم
                'availableBeds' => $availableBeds, // من المتحكم
                'admissionStatuses' => $admissionStatuses, // من المتحكم
                // لا يوجد 'patientAdmission' هنا لأننا في وضع الإنشاء
            ])

            <div class="row">
                <div class="col-12 text-center mt-3 mb-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> حفظ وتسجيل الدخول</button>
                    <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> إلغاء</a>
                </div>
            </div>
        </form>

    </div><!-- container -->
@endsection

@section('js')
    <!-- Select2 JS -->
    <script src="{{ asset('dashboard/assets/plugins/select2/select2.full.min.js') }}"></script>
    {{-- <script src="{{ asset('dashboard/assets/pages/jquery.forms-advanced.js') }}"></script> --}}
    @stack('js_after_form') {{-- لجلب الـ JS من ملف _form.blade.php --}}
    <script>
        // أي JS إضافي خاص بصفحة الإنشاء
        // $(document).ready(function() {
            // إذا كنت ستستخدم error bag مخصص للإنشاء
            // const createForm = document.getElementById('createAdmissionForm');
            // if (createForm) {
            //     // ...
            // }
        // });
    </script>
@endsection
