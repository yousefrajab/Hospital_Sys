@extends('Dashboard.layouts.master')

@section('title')
    تعديل سجل الدخول للمريض: {{ $patientAdmission->patient->name ?? 'غير معروف' }}
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
                            <li class="breadcrumb-item active">تعديل سجل #{{ $patientAdmission->id }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">تعديل سجل الدخول للمريض: {{ $patientAdmission->patient->name ?? 'غير معروف' }}</h4>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        @php
            $errorBagName = 'editAdmissionFormBag'.$patientAdmission->id;
            $isDischargeMode = request()->get('action') === 'discharge';
        @endphp

        @if ($errors->{$errorBagName}->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->{$errorBagName}->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @elseif ($errors->any() && !$errors->hasBag($errorBagName) && !$errors->hasBag('dischargeFormBag'.$patientAdmission->id) /* إذا كانت هناك أخطاء عامة غير مرتبطة بـ bag معين */ )
            <div class="alert alert-danger">
                <p>حدث خطأ ما. يرجى مراجعة الحقول.</p>
                {{-- <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul> --}}
            </div>
        @endif


        <form action="{{ route('admin.patient_admissions.update', $patientAdmission->id) }}" method="POST" id="editAdmissionForm{{$patientAdmission->id}}">
            @csrf
            @method('PUT')

            @include('Dashboard.PatientAdmissions.partials._form', [
                'patientAdmission' => $patientAdmission, // تمرير الكائن الحالي
                'patients' => $patients,
                'doctors' => $doctors,
                'sections' => $sections,
                'availableBeds' => $availableBeds,
                'admissionStatuses' => $admissionStatuses,
                'prefillDischarge' => $isDischargeMode, // لتجهيز حقول الخروج إذا كان الرابط ?action=discharge
            ])

            <div class="row">
                <div class="col-12 text-center mt-3 mb-3">
                    @if($isDischargeMode)
                        <button type="submit" class="btn btn-warning"><i class="fas fa-user-check me-1"></i> تأكيد تسجيل الخروج</button>
                    @else
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> تحديث بيانات السجل</button>
                    @endif
                    <a href="{{ route('admin.patient_admissions.show', $patientAdmission->id) }}" class="btn btn-info"><i class="fas fa-eye me-1"></i> عرض السجل</a>
                    <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> إلغاء</a>
                </div>
            </div>
        </form>


    </div><!-- container -->
@endsection

@section('js')
    <!-- Select2 JS -->
    <script src="{{ asset('dashboard/assets/plugins/select2/select2.full.min.js') }}"></script>
    @stack('js_after_form') {{-- لجلب الـ JS من ملف _form.blade.php --}}
    @stack('js_after_modal') {{-- لجلب الـ JS من ملف _discharge_modal.blade.php إذا تم تضمينه --}}
    <script>
        // أي JS إضافي خاص بصفحة التعديل
        $(document).ready(function() {
            // إذا كان ?action=discharge في الرابط، ركز على حقل تاريخ الخروج
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'discharge') {
                $('#discharge_date').focus();
                // يمكنك أيضاً تعديل العنوان أو إضافة تنبيه
                $('.page-title').text('تسجيل خروج المريض: {{ $patientAdmission->patient->name ?? "" }}');
                // التمرير إلى قسم الخروج
                const dischargeSection = document.getElementById('discharge_date'); // أو أي عنصر في قسم الخروج
                if (dischargeSection) {
                    dischargeSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    </script>
@endsection
