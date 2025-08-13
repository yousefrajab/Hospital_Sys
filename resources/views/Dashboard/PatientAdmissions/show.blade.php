@extends('Dashboard.layouts.master')

@section('title')
    تفاصيل إقامة المريض: {{ $patientAdmission->patient->name ?? 'غير معروف' }} (رقم: {{ $patientAdmission->id }})
@endsection

@section('css')
    <style>
        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .patient-img-container,
        .doctor-img-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 2px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .patient-img-container img,
        .doctor-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-custom-header {
            background-color: #f1f5f9;
            /* لون أفتح قليلاً */
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            /* أكثر سماكة قليلاً */
            color: #334155;
        }

        .badge-status {
            padding: 0.5em 0.75em;
            font-size: 0.9em;
        }

        .vitals-table th,
        .vitals-table td {
            vertical-align: middle;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* لتحديد ارتفاع للرسوم */

        /* تحسينات للطباعة */
        @media print {
            body * {
                visibility: hidden;
            }

            .printable-content,
            .printable-content * {
                visibility: visible;
            }

            .printable-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }

            .modal,
            .modal-backdrop {
                display: none !important;
            }

            .card {
                border: 1px solid #dee2e6 !important;
                box-shadow: none !important;
            }

            .breadcrumb-header,
            .page-title-box .float-right {
                display: none;
            }

            h2.page-title {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-procedures fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">{{ trans('AdminSb.admissions_log') }}</h4>
                    <span class="text-muted mt-0 tx-13">/ {{ trans('AdminSb.admission_details_for') }}
                        {{ $patientAdmission->patient->name ?? trans('AdminSb.patient_id_num', ['id' => $patientAdmission->patient_id]) }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            @if ($patientAdmission->status === \App\Models\PatientAdmission::STATUS_ADMITTED && !$patientAdmission->discharge_date)
                <a href="{{ route('admin.patient_admissions.edit', $patientAdmission->id) }}"
                    class="btn btn-outline-primary btn-sm me-2" style="border-radius: var(--admin-radius-md);">
                    <i class="fas fa-edit me-1"></i> {{ trans('AdminSb.edit_admission_data') }}
                </a>
                <button type="button" class="btn btn-warning btn-sm me-2" data-toggle="modal"
                    data-target="#dischargePatientModal{{ $patientAdmission->id }}"
                    style="border-radius: var(--admin-radius-md);">
                    <i class="fas fa-user-check me-1"></i> {{ trans('AdminSb.discharge_patient_button') }}
                </button>
            @endif
            <button onclick="window.print()" class="btn btn-outline-info btn-sm me-2"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-print me-1"></i> {{ trans('AdminSb.print') }}
            </button>
            <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-outline-secondary btn-sm"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> {{ trans('AdminSb.back_to_list') }}
            </a>
        </div>
    </div>
    @include('Dashboard.PatientAdmissions.modals._discharge_modal', ['admission' => $patientAdmission])
@endsection

@section('content')
    <div class="printable-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="page-title mb-0">{{ trans('AdminSb.admission_details_for') }}
                            {{ $patientAdmission->patient->name ?? trans('AdminSb.unknown') }}</h2>
                        <span class="badge bg-primary badge-status">{{ trans('AdminSb.record_number_colon') }}
                            #{{ $patientAdmission->id }}</span>
                    </div>
                </div>
            </div>

            @include('Dashboard.messages_alert')

            <div class="row">
                <!-- Patient Information -->
                <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-custom-header d-flex align-items-center">
                            <i class="fas fa-user-injured fa-lg me-2 text-primary"></i>
                            {{ trans('AdminSb.patient_information') }}
                        </div>
                        <div class="card-body text-center">
                            @if ($patientAdmission->patient)
                                <div class="patient-img-container mx-auto">
                                    <img src="{{ $patientAdmission->patient->image ? asset('Dashboard/img/patients/' . $patientAdmission->patient->image->filename) : asset('Dashboard/img/doctor_default.png') }}"
                                        alt="{{ $patientAdmission->patient->name }}" class="patient-avatar-sm">
                                </div>
                                <h5 class="card-title mt-2 mb-1">{{ $patientAdmission->patient->name }}</h5>
                                <p class="text-muted tx-13 mb-2">{{ trans('AdminSb.id_colon') }}
                                    {{ $patientAdmission->patient->national_id ?? '-' }}</p>
                                <ul class="list-unstyled text-start mt-3 tx-13">
                                    <li class="mb-2"><span
                                            class="info-label">{{ trans('AdminSb.date_of_birth_colon') }}</span>
                                        {{ $patientAdmission->patient->Date_Birth ? $patientAdmission->patient->Date_Birth->format('Y-m-d') . ' (' . trans('AdminSb.age_colon') . $patientAdmission->patient->Date_Birth->age . ' ' . trans('AdminSb.years_old') . ')' : '-' }}
                                    </li>
                                    <li class="mb-2"><span class="info-label">{{ trans('AdminSb.gender_colon') }}</span>
                                        {{ $patientAdmission->patient->Gender == 1 ? trans('AdminSb.male') : ($patientAdmission->patient->Gender == 2 ? trans('AdminSb.female') : '-') }}
                                    </li>
                                    <li class="mb-2"><span class="info-label">{{ trans('AdminSb.phone_colon') }}</span>
                                        {{ $patientAdmission->patient->Phone ?? '-' }}</li>
                                    <li class="mb-2"><span
                                            class="info-label">{{ trans('AdminSb.blood_type_colon') }}</span> <span
                                            class="badge bg-danger-transparent">{{ $patientAdmission->patient->Blood_Group ?? '-' }}</span>
                                    </li>
                                </ul>
                                <a href="{{ route('admin.Patients.show', $patientAdmission->patient_id) }}"
                                    class="btn btn-primary-transparent btn-sm mt-2 w-100">{{ trans('AdminSb.view_full_patient_profile') }}
                                    <i class="fas fa-external-link-alt ms-1"></i></a>
                            @else
                                <p class="text-muted py-5">{{ trans('AdminSb.no_patient_data_available') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Admission Information -->
                <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-custom-header d-flex align-items-center">
                            <i class="fas fa-hospital-user fa-lg me-2 text-success"></i>
                            {{ trans('AdminSb.current_admission_details') }}
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled tx-13">
                                <li class="mb-3"><span
                                        class="info-label d-block">{{ trans('AdminSb.admission_datetime') }}</span> <span
                                        class="info-value fs-6">{{ $patientAdmission->admission_date->format('Y-m-d, h:i A') }}</span>
                                </li>
                                <li class="mb-3"><span
                                        class="info-label d-block">{{ trans('AdminSb.attending_physician') }}</span>
                                    @if ($patientAdmission->doctor)
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="doctor-img-container me-2"
                                                style="width:40px; height:40px; margin-bottom:0;">
                                                <img src="{{ $patientAdmission->doctor->image ? asset('Dashboard/img/doctors/' . $patientAdmission->doctor->image->filename) : asset('Dashboard/img/doctor_default.png') }}"
                                                    alt="{{ $patientAdmission->doctor->name }}" class="patient-avatar-sm">
                                            </div>
                                            <div>
                                                <span class="info-value">{{ $patientAdmission->doctor->name }}</span><br>
                                                <small
                                                    class="text-muted">{{ $patientAdmission->doctor->section->name ?? trans('AdminSb.unspecified_department') }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span
                                            class="info-value text-muted">{{ trans('AdminSb.doctor_not_specified') }}</span>
                                    @endif
                                </li>
                                <li class="mb-3"><span
                                        class="info-label d-block">{{ trans('AdminSb.bed_room_department') }}</span>
                                    @if ($patientAdmission->bed)
                                        <span class="info-value">{{ trans('AdminSb.bed_colon') }}
                                            {{ $patientAdmission->bed->bed_number }}</span>
                                        @if ($patientAdmission->bed->room)
                                            <span class="info-value"> / {{ trans('AdminSb.room_colon') }}
                                                {{ $patientAdmission->bed->room->room_number }}</span>
                                            @if ($patientAdmission->bed->room->section)
                                                <br><small class="text-muted">{{ trans('AdminSb.department_colon') }}
                                                    {{ $patientAdmission->bed->room->section->name }}</small>
                                            @endif
                                        @endif
                                    @elseif($patientAdmission->section)
                                        <span class="info-value text-muted">{{ trans('AdminSb.department_colon') }}
                                            {{ $patientAdmission->section->name }}
                                            ({{ trans('AdminSb.without_bed') }})</span>
                                    @else
                                        <span class="info-value text-muted">{{ trans('AdminSb.unspecified') }}</span>
                                    @endif
                                </li>
                                <li class="mb-1"><span
                                        class="info-label d-block">{{ trans('AdminSb.current_record_status') }}</span>
                                    @php
                                        $statusClass = 'secondary';
                                        if (
                                            $patientAdmission->status ===
                                                \App\Models\PatientAdmission::STATUS_ADMITTED &&
                                            !$patientAdmission->discharge_date
                                        ) {
                                            $statusClass = 'success';
                                        } elseif (
                                            $patientAdmission->status ===
                                            \App\Models\PatientAdmission::STATUS_DISCHARGED
                                        ) {
                                            $statusClass = 'danger';
                                        } elseif (
                                            $patientAdmission->status === \App\Models\PatientAdmission::STATUS_CANCELLED
                                        ) {
                                            $statusClass = 'warning';
                                        }
                                    @endphp
                                    <span
                                        class="badge bg-{{ $statusClass }}-transparent badge-status">{{ $statusDisplay }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Diagnosis and Notes Information -->
                <div class="col-xl-4 col-lg-12 col-md-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-custom-header d-flex align-items-center">
                            <i class="fas fa-file-medical-alt fa-lg me-2 text-info"></i>
                            {{ trans('AdminSb.diagnosis_and_notes') }}
                        </div>
                        <div class="card-body">
                            <h6 class="info-label">{{ trans('AdminSb.reason_for_admission_colon') }}</h6>
                            <p class="info-value mb-3">{{ $patientAdmission->reason_for_admission ?: '-' }}</p>
                            <h6 class="info-label">{{ trans('AdminSb.admitting_diagnosis_colon') }}</h6>
                            <p class="info-value mb-3">{{ $patientAdmission->admitting_diagnosis ?: '-' }}</p>
                            @if ($patientAdmission->discharge_date || $patientAdmission->status === \App\Models\PatientAdmission::STATUS_DISCHARGED)
                                <hr>
                                <h6 class="info-label">{{ trans('AdminSb.discharge_reason_colon') }}</h6>
                                <p class="info-value mb-3">{{ $patientAdmission->discharge_reason ?: '-' }}</p>
                                <h6 class="info-label">{{ trans('AdminSb.discharge_diagnosis_colon') }}</h6>
                                <p class="info-value mb-3">{{ $patientAdmission->discharge_diagnosis ?: '-' }}</p>
                                <h6 class="info-label">{{ trans('AdminSb.discharge_datetime_colon') }}</h6>
                                <p class="info-value fs-6">
                                    {{ $patientAdmission->discharge_date ? $patientAdmission->discharge_date->format('Y-m-d, h:i A') : '-' }}
                                </p>
                            @endif
                            <hr>
                            <h6 class="info-label">{{ trans('AdminSb.additional_admission_notes') }}</h6>
                            <p class="info-value mb-0">
                                {{ $patientAdmission->notes ?: trans('AdminSb.no_notes_recorded') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vital Signs Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-custom-header d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-heartbeat fa-lg me-2 text-danger"></i>
                                {{ trans('AdminSb.vital_signs') }}</div>
                            @if ($patientAdmission->status === \App\Models\PatientAdmission::STATUS_ADMITTED && !$patientAdmission->discharge_date)
                                <button type="button" class="btn btn-primary-transparent btn-sm no-print"
                                    data-toggle="modal" data-target="#addVitalSignModal">
                                    <i class="fas fa-plus me-1"></i> {{ trans('AdminSb.add_new_reading') }}
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($patientAdmission->vitalSigns->isNotEmpty())
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-hover vitals-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ trans('AdminSb.recorded_at') }}</th>
                                                <th>{{ trans('AdminSb.temperature') }}</th>
                                                <th>{{ trans('AdminSb.blood_pressure') }}</th>
                                                <th>{{ trans('AdminSb.heart_rate') }}</th>
                                                <th>{{ trans('AdminSb.respiratory_rate') }}</th>
                                                <th>{{ trans('AdminSb.oxygen_saturation') }}</th>
                                                <th>{{ trans('AdminSb.pain_level') }}</th>
                                                <th>{{ trans('AdminSb.notes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($patientAdmission->vitalSigns->sortByDesc('recorded_at') as $vital)
                                                <tr>
                                                    <td class="text-nowrap">{{ $vital->recorded_at->format('d M, H:i') }}
                                                    </td>
                                                    <td>{{ $vital->temperature ? $vital->temperature . '°C' : '-' }}</td>
                                                    <td>{{ $vital->blood_pressure ?? '-' }}</td>
                                                    <td>{{ $vital->heart_rate ? $vital->heart_rate . ' bpm' : '-' }}</td>
                                                    <td>{{ $vital->respiratory_rate ? $vital->respiratory_rate . ' rpm' : '-' }}
                                                    </td>
                                                    <td>{{ $vital->oxygen_saturation ? $vital->oxygen_saturation . '%' : '-' }}
                                                    </td>
                                                    <td>{{ $vital->pain_level ?? '-' }}</td>
                                                    <td>{{ Str::limit($vital->notes, 30) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if (!empty($vitalSignsDataForChart) && count($vitalSignsDataForChart['labels']) > 1)
                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <div class="border p-3 rounded">
                                                <h6 class="text-center text-muted">
                                                    {{ trans('AdminSb.temperature_chart') }}</h6>
                                                <div class="chart-container"><canvas id="temperatureChart"></canvas></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="border p-3 rounded">
                                                <h6 class="text-center text-muted">{{ trans('AdminSb.heart_rate_chart') }}
                                                </h6>
                                                <div class="chart-container"><canvas id="heartRateChart"></canvas></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="border p-3 rounded">
                                                <h6 class="text-center text-muted">
                                                    {{ trans('AdminSb.blood_pressure_chart') }}</h6>
                                                <div class="chart-container"><canvas id="bloodPressureChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="border p-3 rounded">
                                                <h6 class="text-center text-muted">
                                                    {{ trans('AdminSb.oxygen_saturation_chart') }}</h6>
                                                <div class="chart-container"><canvas id="oxygenSaturationChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="border p-3 rounded">
                                                <h6 class="text-center text-muted">
                                                    {{ trans('AdminSb.respiratory_rate_chart') }}</h6>
                                                <div class="chart-container"><canvas id="respiratoryRateChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(!empty($vitalSignsDataForChart) && count($vitalSignsDataForChart['labels']) <= 1)
                                    <p class="text-center text-muted">{{ trans('AdminSb.at_least_two_readings_needed') }}
                                    </p>
                                @endif
                            @else
                                <p class="text-center text-muted py-4">{{ trans('AdminSb.no_vital_signs_recorded_yet') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($patientAdmission->status === \App\Models\PatientAdmission::STATUS_ADMITTED && !$patientAdmission->discharge_date)
        <div class="modal fade no-print" id="addVitalSignModal" tabindex="-1" aria-labelledby="addVitalSignModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('admin.vital_signs.store', $patientAdmission->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addVitalSignModalLabel">
                                {{ trans('AdminSb.add_new_vital_sign_reading') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="vs_recorded_at" class="form-label">{{ trans('AdminSb.recorded_at') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="datetime-local"
                                        class="form-control @error('recorded_at', 'vitalStore') is-invalid @enderror"
                                        id="vs_recorded_at" name="recorded_at"
                                        value="{{ old('recorded_at', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('recorded_at', 'vitalStore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="vs_temperature"
                                        class="form-label">{{ trans('AdminSb.temperature_c') }}</label>
                                    <input type="number" step="0.1"
                                        class="form-control @error('temperature', 'vitalStore') is-invalid @enderror"
                                        id="vs_temperature" name="temperature" value="{{ old('temperature') }}">
                                    @error('temperature', 'vitalStore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="vs_systolic_bp"
                                        class="form-label">{{ trans('AdminSb.systolic_bp') }}</label>
                                    <input type="number"
                                        class="form-control @error('systolic_bp', 'vitalStore') is-invalid @enderror"
                                        id="vs_systolic_bp" name="systolic_bp" value="{{ old('systolic_bp') }}">
                                    @error('systolic_bp', 'vitalStore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="vs_diastolic_bp"
                                        class="form-label">{{ trans('AdminSb.diastolic_bp') }}</label>
                                    <input type="number"
                                        class="form-control @error('diastolic_bp', 'vitalStore') is-invalid @enderror"
                                        id="vs_diastolic_bp" name="diastolic_bp" value="{{ old('diastolic_bp') }}">
                                    @error('diastolic_bp', 'vitalStore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="vs_heart_rate"
                                        class="form-label">{{ trans('AdminSb.heart_rate_bpm') }}</label>
                                    <input type="number"
                                        class="form-control @error('heart_rate', 'vitalStore') is-invalid @enderror"
                                        id="vs_heart_rate" name="heart_rate" value="{{ old('heart_rate') }}">
                                    @error('heart_rate', 'vitalStore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="vs_respiratory_rate"
                                        class="form-label">{{ trans('AdminSb.respiratory_rate_rpm') }}</label>
                                    <input type="number"
                                        class="form-control @error('respiratory_rate', 'vitalStore') is-invalid @enderror"
                                        id="vs_respiratory_rate" name="respiratory_rate"
                                        value="{{ old('respiratory_rate') }}">
                                    @error('respiratory_rate', 'vitalStore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="vs_oxygen_saturation"
                                        class="form-label">{{ trans('AdminSb.oxygen_saturation_percent') }}</label>
                                    <input type="number" step="0.1"
                                        class="form-control @error('oxygen_saturation', 'vitalStore') is-invalid @enderror"
                                        id="vs_oxygen_saturation" name="oxygen_saturation"
                                        value="{{ old('oxygen_saturation') }}">
                                    @error('oxygen_saturation', 'vitalStore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="vs_pain_level"
                                        class="form-label">{{ trans('AdminSb.pain_level_scale') }}</label>
                                    <input type="number"
                                        class="form-control @error('pain_level', 'vitalStore') is-invalid @enderror"
                                        id="vs_pain_level" name="pain_level" value="{{ old('pain_level') }}"
                                        min="0" max="10">
                                    @error('pain_level', 'vitalStore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="vs_notes" class="form-label">{{ trans('AdminSb.notes') }}</label>
                                <textarea class="form-control @error('notes', 'vitalStore') is-invalid @enderror" id="vs_notes" name="notes"
                                    rows="3">{{ old('notes') }}</textarea>
                                @error('notes', 'vitalStore')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ trans('AdminSb.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('AdminSb.save_reading') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('js')
    {{-- تأكد من تحميل Chart.js إما هنا أو في التخطيط الرئيسي --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- أو من ملفاتك المحلية إذا قمت بتثبيته --}}
    <script src="{{ asset('dashboard/assets/plugins/chart.js/Chart.min.js') }}"></script>


    @if (!empty($vitalSignsDataForChart) && count($vitalSignsDataForChart['labels']) > 1)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartData = @json($vitalSignsDataForChart);

                function createVitalSignChart(canvasId, label, data, borderColor, yAxisLabel = '', suggestedMin =
                    undefined, suggestedMax = undefined) {
                    const ctx = document.getElementById(canvasId);
                    if (!ctx) return;
                    new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: label,
                                data: data,
                                borderColor: borderColor,
                                backgroundColor: borderColor.replace('rgb(', 'rgba(').replace(')',
                                    ', 0.1)'),
                                tension: 0.2,
                                fill: true,
                                pointBackgroundColor: borderColor,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    title: {
                                        display: yAxisLabel !== '',
                                        text: yAxisLabel
                                    },
                                    suggestedMin: suggestedMin, // لتحديد حد أدنى مقترح للمحور
                                    suggestedMax: suggestedMax, // لتحديد حد أقصى مقترح للمحور
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'وقت التسجيل'
                                    },
                                    ticks: {
                                        autoSkip: true,
                                        maxTicksLimit: 10
                                    }, // لتقليل عدد التسميات إذا كانت كثيرة
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(0,0,0,0.7)',
                                    titleFont: {
                                        weight: 'bold'
                                    },
                                    bodySpacing: 5,
                                    padding: 10,
                                    cornerRadius: 4
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                }

                if (chartData.temperature && chartData.temperature.some(val => val !== null)) createVitalSignChart(
                    'temperatureChart', 'الحرارة', chartData.temperature, 'rgb(255, 99, 132)', '°C', 35, 42);
                if (chartData.heartRate && chartData.heartRate.some(val => val !== null)) createVitalSignChart(
                    'heartRateChart', 'النبض', chartData.heartRate, 'rgb(54, 162, 235)', 'bpm', 40, 160);
                if (chartData.oxygenSaturation && chartData.oxygenSaturation.some(val => val !== null))
                    createVitalSignChart('oxygenSaturationChart', 'تشبع O2', chartData.oxygenSaturation,
                        'rgb(75, 192, 192)', '%', 85, 100);
                if (chartData.respiratoryRate && chartData.respiratoryRate.some(val => val !== null))
                    createVitalSignChart('respiratoryRateChart', 'التنفس', chartData.respiratoryRate,
                        'rgb(255, 159, 64)', 'rpm', 10, 30);

                const bpCtx = document.getElementById('bloodPressureChart');
                if (bpCtx && chartData.bloodPressure && (chartData.bloodPressure.systolic.some(val => val !== null) ||
                        chartData.bloodPressure.diastolic.some(val => val !== null))) {
                    new Chart(bpCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                    label: 'انقباضي',
                                    data: chartData.bloodPressure.systolic,
                                    borderColor: 'rgb(239, 68, 68)', // Red-600
                                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                    tension: 0.2,
                                    fill: false,
                                    pointBackgroundColor: 'rgb(239, 68, 68)',
                                    pointRadius: 3,
                                    pointHoverRadius: 6,
                                    borderWidth: 2
                                },
                                {
                                    label: 'انبساطي',
                                    data: chartData.bloodPressure.diastolic,
                                    borderColor: 'rgb(37, 99, 235)', // Blue-600
                                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                    tension: 0.2,
                                    fill: false,
                                    pointBackgroundColor: 'rgb(37, 99, 235)',
                                    pointRadius: 3,
                                    pointHoverRadius: 6,
                                    borderWidth: 2
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    title: {
                                        display: true,
                                        text: 'ضغط الدم (mmHg)'
                                    },
                                    suggestedMin: 50,
                                    suggestedMax: 200,
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'وقت التسجيل'
                                    },
                                    ticks: {
                                        autoSkip: true,
                                        maxTicksLimit: 10
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(0,0,0,0.7)',
                                    titleFont: {
                                        weight: 'bold'
                                    },
                                    bodySpacing: 5,
                                    padding: 10,
                                    cornerRadius: 4
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                }
            });
        </script>
    @endif

    <script>
        // كود إعادة فتح المودالات عند وجود أخطاء
        @if ($errors->vitalStore->any())
            var addVitalSignModalInstance = new bootstrap.Modal(document.getElementById('addVitalSignModal'));
            if (addVitalSignModalInstance) addVitalSignModalInstance.show();
        @endif

        @if (
            $errors->{'dischargeFormBag' . $patientAdmission->id} &&
                $errors->{'dischargeFormBag' . $patientAdmission->id}->any())
            var dischargeModalInstance = new bootstrap.Modal(document.getElementById(
                'dischargePatientModal{{ $patientAdmission->id }}'));
            if (dischargeModalInstance) dischargeModalInstance.show();
        @endif
    </script>
@endsection
