@extends('Dashboard.layouts.master')

@section('title')
    تعديل سجل الدخول للمريض: {{ $patientAdmission->patient->name ?? 'غير معروف' }}
@endsection

@section('css')
    <!-- Select2 CSS -->
    <link href="{{ asset('dashboard/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/assets/plugins/select2-bootstrap5-theme/select2-bootstrap-5-theme.min.css') }}"
        rel="stylesheet" type="text/css" />
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
                            <li class="breadcrumb-item"><a
                                    href="{{ route('dashboard.admin') }}">{{ trans('AdminSb.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.patient_admissions.index') }}">{{ trans('AdminSb.patient_admission_records') }}</a>
                            </li>
                            <li class="breadcrumb-item active">
                                {{ trans('AdminSb.edit_record_number', ['id' => $patientAdmission->id]) }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        {{ trans('AdminSb.edit_admission_record_for_patient', ['name' => $patientAdmission->patient->name ?? trans('AdminSb.unknown')]) }}
                    </h4>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        @php
            $errorBagName = 'editAdmissionFormBag' . $patientAdmission->id;
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
        @elseif ($errors->any() && !$errors->hasBag($errorBagName) && !$errors->hasBag('dischargeFormBag' . $patientAdmission->id))
            <div class="alert alert-danger">
                <p>{{ trans('AdminSb.generic_error_message') }}</p>
            </div>
        @endif


        <form action="{{ route('admin.patient_admissions.update', $patientAdmission->id) }}" method="POST"
            id="editAdmissionForm{{ $patientAdmission->id }}">
            @csrf
            @method('PUT')

            @include('Dashboard.PatientAdmissions.partials._form', [
                'patientAdmission' => $patientAdmission,
                'patients' => $patients,
                'doctors' => $doctors,
                'sections' => $sections,
                'availableBeds' => $availableBeds,
                'admissionStatuses' => $admissionStatuses,
                'prefillDischarge' => $isDischargeMode,
            ])

            <div class="row">
                <div class="col-12 text-center mt-3 mb-3">
                    @if ($isDischargeMode)
                        <button type="submit" class="btn btn-warning"><i class="fas fa-user-check me-1"></i>
                            {{ trans('AdminSb.confirm_discharge') }}</button>
                    @else
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>
                            {{ trans('AdminSb.update_record_data') }}</button>
                    @endif
                    <a href="{{ route('admin.patient_admissions.show', $patientAdmission->id) }}" class="btn btn-info"><i
                            class="fas fa-eye me-1"></i> {{ trans('AdminSb.view_record') }}</a>
                    <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-secondary"><i
                            class="fas fa-times me-1"></i> {{ trans('AdminSb.cancel') }}</a>
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
                $('.page-title').text('تسجيل خروج المريض: {{ $patientAdmission->patient->name ?? '' }}');
                // التمرير إلى قسم الخروج
                const dischargeSection = document.getElementById('discharge_date'); // أو أي عنصر في قسم الخروج
                if (dischargeSection) {
                    dischargeSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    </script>
@endsection
