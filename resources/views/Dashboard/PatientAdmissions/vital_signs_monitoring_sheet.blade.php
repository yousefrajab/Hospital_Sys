@extends('Dashboard.layouts.master')

@section('title')
    مراقبة العلامات الحيوية لـ {{ $patientAdmission->patient->name ?? 'مريض غير معروف' }} (إقامة رقم:
    {{ $patientAdmission->id }})
@endsection

@section('css')
    {{-- يمكنك إضافة أي CSS مخصص هنا إذا احتجت --}}
    <style>
        .patient-info-header {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1.5rem;
        }

        .patient-info-header h5 {
            margin-bottom: 0.25rem;
        }

        .patient-info-header p {
            margin-bottom: 0.1rem;
            font-size: 0.9em;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
        }

        /* Tailwind gray-600 */
        .info-value {
            color: #1f2937;
        }

        /* Tailwind gray-800 */

        .vitals-table th,
        .vitals-table td {
            vertical-align: middle;
            text-align: center;
            padding: 0.5rem 0.4rem;
            /* تقليل الحشو قليلاً */
            font-size: 0.85rem;
            /* تصغير الخط قليلاً للجدول */
        }

        .vitals-table thead th {
            background-color: #eef2ff;
            /* Tailwind indigo-50 */
            color: #4338ca;
            /* Tailwind indigo-700 */
            font-weight: 600;
            white-space: nowrap;
        }

        .vitals-table tbody tr:nth-child(odd) {
            background-color: #f9fafb;
        }

        /* Tailwind gray-50 */
        .vitals-table .time-col {
            min-width: 120px;
        }

        .vitals-table .notes-col {
            min-width: 150px;
            text-align: left !important;
        }

        .form-section {
            border: 1px solid #e5e7eb;
            /* Tailwind gray-200 */
            border-radius: 0.375rem;
            /* rounded-md */
            padding: 1.5rem;
            background-color: #ffffff;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            /* Tailwind gray-700 */
            margin-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0.75rem;
        }

        .chart-container-wrapper {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .chart-container {
            position: relative;
            height: 280px;
            width: 100%;
        }

        /* تحسينات للطباعة */
        @media print {
            body {
                font-size: 10pt;
            }

            .no-print {
                display: none !important;
            }

            .patient-info-header,
            .form-section,
            .chart-container-wrapper,
            .card {
                border: 1px solid #ccc !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }

            .breadcrumb-header,
            .main-content .container-fluid>.row:first-child {
                display: none;
            }

            /* إخفاء breadcrumbs والعنوان الرئيسي للصفحة */
            .page-title-print {
                display: block !important;
                text-align: center;
                margin-bottom: 1rem;
                font-size: 1.5rem;
            }

            .table thead th {
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .chart-container {
                height: 200px !important;
            }

            /* تصغير المخططات قليلاً عند الطباعة */
            a[href]:after {
                content: none !important;
            }

            /* إزالة عرض الروابط عند الطباعة */
        }

        .page-title-print {
            display: none;
        }

        /* إخفاؤه افتراضيًا */
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-monitor-heart-rate fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">{{ trans('AdminSb.vital_signs') }}</h4>
                    <span class="text-muted mt-0 tx-13">/ {{ trans('AdminSb.monitoring_sheet_for') }}
                        {{ $patientAdmission->patient->name ?? trans('AdminSb.patient') }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <button type="button" class="btn btn-primary-gradient btn-sm me-2" data-toggle="collapse"
                href="#addVitalSignFormCollapse" role="button" aria-expanded="false"
                aria-controls="addVitalSignFormCollapse">
                <i class="fas fa-plus me-1"></i> {{ trans('AdminSb.add_new_reading') }}
            </button>
            <button onclick="window.print()" class="btn btn-outline-info btn-sm me-2">
                <i class="fas fa-print me-1"></i> {{ trans('AdminSb.print') }}
            </button>
            <a href="{{ route('admin.patient_admissions.show', $patientAdmission->id) }}"
                class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> {{ trans('AdminSb.back_to_admission_details') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="page-title-print">{{ trans('AdminSb.vital_signs_sheet') }}</div>

        <div class="patient-info-header">
            <div class="row">
                <div class="col-md-4">
                    <h5>{{ $patientAdmission->patient->name ?? trans('AdminSb.patient_name_unavailable') }}</h5>
                    <p><span class="info-label">{{ trans('AdminSb.national_id_colon') }}</span> <span
                            class="info-value">{{ $patientAdmission->patient->national_id ?? '-' }}</span></p>
                    <p><span class="info-label">{{ trans('AdminSb.age_colon') }}</span> <span
                            class="info-value">{{ $patientAdmission->patient->Date_Birth->age }}
                            {{ trans('AdminSb.years_old') }}</span></p>
                    <p><span class="info-label">{{ trans('AdminSb.gender_colon') }}</span> <span
                            class="info-value">{{ $patientAdmission->patient->Gender == 1 ? trans('AdminSb.male') : ($patientAdmission->patient->Gender == 2 ? trans('AdminSb.female') : '-') }}</span>
                    </p>
                </div>
                <div class="col-md-4">
                    <p><span class="info-label">{{ trans('AdminSb.admission_date_colon') }}</span> <span
                            class="info-value">{{ $patientAdmission->admission_date->format('Y-m-d H:i A') }}</span></p>
                    <p><span class="info-label">{{ trans('AdminSb.attending_physician_colon') }}</span> <span
                            class="info-value">{{ $patientAdmission->doctor->name ?? trans('AdminSb.unspecified') }}</span>
                    </p>
                    <p><span class="info-label">{{ trans('AdminSb.department_colon') }}</span> <span
                            class="info-value">{{ $patientAdmission->bed?->room?->section?->name ?? ($patientAdmission->section?->name ?? trans('AdminSb.unspecified')) }}</span>
                    </p>
                </div>
                <div class="col-md-4">
                    <p><span class="info-label">{{ trans('AdminSb.room_bed_colon') }}</span> <span
                            class="info-value">{{ $patientAdmission->bed?->room?->room_number ?? '-' }} /
                            {{ $patientAdmission->bed?->bed_number ?? '-' }}</span></p>
                    <p><span class="info-label">{{ trans('AdminSb.reason_for_admission_colon') }}</span> <span
                            class="info-value">{{ Str::limit($patientAdmission->reason_for_admission, 50) ?? '-' }}</span>
                    </p>
                </div>
            </div>
        </div>
        @include('Dashboard.messages_alert')

        @if ($errors->vitalStore->any())
            <div class="alert alert-danger no-print">
                <p class="fw-bold">{{ trans('AdminSb.check_following_errors_vitals') }}</p>
                <ul>
                    @foreach ($errors->vitalStore->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @elseif($errors->any() && !session()->has('success'))
            <div class="alert alert-danger no-print">
                <p>{{ trans('AdminSb.generic_error_try_again') }}</p>
            </div>
        @endif

        <div class="collapse no-print {{ $errors->vitalStore->any() ? 'show' : '' }}" id="addVitalSignFormCollapse">
            <div class="form-section mb-4">
                <h3 class="form-section-title">{{ trans('AdminSb.add_new_vital_reading_form_title') }}</h3>
                <form action="{{ route('admin.vital_signs.store', $patientAdmission->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="vs_recorded_at" class="form-label">{{ trans('AdminSb.recorded_at') }} <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local"
                                class="form-control form-control-sm @error('recorded_at', 'vitalStore') is-invalid @enderror"
                                id="vs_recorded_at" name="recorded_at"
                                value="{{ old('recorded_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('recorded_at', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="vs_temperature" class="form-label">{{ trans('AdminSb.temperature_c') }}</label>
                            <input type="number" step="0.1"
                                class="form-control form-control-sm @error('temperature', 'vitalStore') is-invalid @enderror"
                                id="vs_temperature" name="temperature" value="{{ old('temperature') }}">
                            @error('temperature', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="vs_heart_rate" class="form-label">{{ trans('AdminSb.heart_rate_bpm') }}</label>
                            <input type="number"
                                class="form-control form-control-sm @error('heart_rate', 'vitalStore') is-invalid @enderror"
                                id="vs_heart_rate" name="heart_rate" value="{{ old('heart_rate') }}">
                            @error('heart_rate', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="vs_systolic_bp" class="form-label">{{ trans('AdminSb.systolic_pressure') }}</label>
                            <input type="number"
                                class="form-control form-control-sm @error('systolic_bp', 'vitalStore') is-invalid @enderror"
                                id="vs_systolic_bp" name="systolic_bp" value="{{ old('systolic_bp') }}">
                            @error('systolic_bp', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="vs_diastolic_bp"
                                class="form-label">{{ trans('AdminSb.diastolic_pressure') }}</label>
                            <input type="number"
                                class="form-control form-control-sm @error('diastolic_bp', 'vitalStore') is-invalid @enderror"
                                id="vs_diastolic_bp" name="diastolic_bp" value="{{ old('diastolic_bp') }}">
                            @error('diastolic_bp', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="vs_respiratory_rate"
                                class="form-label">{{ trans('AdminSb.respiratory_rate_rpm') }}</label>
                            <input type="number"
                                class="form-control form-control-sm @error('respiratory_rate', 'vitalStore') is-invalid @enderror"
                                id="vs_respiratory_rate" name="respiratory_rate" value="{{ old('respiratory_rate') }}">
                            @error('respiratory_rate', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="vs_oxygen_saturation"
                                class="form-label">{{ trans('AdminSb.oxygen_saturation_percent_short') }}</label>
                            <input type="number" step="0.1"
                                class="form-control form-control-sm @error('oxygen_saturation', 'vitalStore') is-invalid @enderror"
                                id="vs_oxygen_saturation" name="oxygen_saturation"
                                value="{{ old('oxygen_saturation') }}">
                            @error('oxygen_saturation', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="vs_pain_level" class="form-label">{{ trans('AdminSb.pain_level_scale') }}</label>
                            <input type="number"
                                class="form-control form-control-sm @error('pain_level', 'vitalStore') is-invalid @enderror"
                                id="vs_pain_level" name="pain_level" value="{{ old('pain_level') }}" min="0"
                                max="10">
                            @error('pain_level', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-9 mb-3">
                            <label for="vs_notes" class="form-label">{{ trans('AdminSb.notes') }}</label>
                            <textarea class="form-control form-control-sm @error('notes', 'vitalStore') is-invalid @enderror" id="vs_notes"
                                name="notes" rows="1">{{ old('notes') }}</textarea>
                            @error('notes', 'vitalStore')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse"
                            href="#addVitalSignFormCollapse">{{ trans('AdminSb.cancel') }}</button>
                        <button type="submit"
                            class="btn btn-primary btn-sm">{{ trans('AdminSb.save_reading') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light py-2 px-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-list-ol me-2"></i>
                    {{ trans('AdminSb.temporal_log_of_vitals') }}</h5>
            </div>
            <div class="card-body p-0">
                @if ($patientAdmission->vitalSigns->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover vitals-table mb-0">
                            <thead>
                                <tr>
                                    <th class="time-col">{{ trans('AdminSb.recorded_at') }}</th>
                                    <th>{{ trans('AdminSb.temperature') }}</th>
                                    <th>{{ trans('AdminSb.blood_pressure') }}</th>
                                    <th>{{ trans('AdminSb.heart_rate') }}</th>
                                    <th>{{ trans('AdminSb.respiratory_rate') }}</th>
                                    <th>{{ trans('AdminSb.oxygen_saturation') }}</th>
                                    <th>{{ trans('AdminSb.pain_level') }}</th>
                                    <th>{{ trans('AdminSb.notes') }}</th>
                                    <th>{{ trans('AdminSb.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patientAdmission->vitalSigns as $vital)
                                    <tr>
                                        <td class="time-col">{{ $vital->recorded_at->format('d M, Y H:i') }}</td>
                                        <td>{{ $vital->temperature ? $vital->temperature . '°C' : '-' }}</td>
                                        <td>{{ $vital->blood_pressure ?? '-' }}</td>
                                        <td>{{ $vital->heart_rate ? $vital->heart_rate . ' bpm' : '-' }}</td>
                                        <td>{{ $vital->respiratory_rate ? $vital->respiratory_rate . ' rpm' : '-' }}</td>
                                        <td>{{ $vital->oxygen_saturation ? $vital->oxygen_saturation . '%' : '-' }}</td>
                                        <td>{{ $vital->pain_level ?? '-' }}</td>
                                        <td>{{ $vital->notes ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.vital_signs.edit', $vital->id) }}"
                                                class="btn btn-sm btn-outline-primary btn-icon"
                                                title="{{ trans('AdminSb.edit_reading') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.vital_signs.destroy', $vital->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('{{ trans('AdminSb.confirm_delete_reading') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-icon"
                                                    title="{{ trans('AdminSb.delete_reading') }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted p-5">{{ trans('AdminSb.no_vital_signs_recorded_yet') }}</p>
                @endif
            </div>
        </div>

        @if (!empty($chartData) && isset($chartData['labels']) && count($chartData['labels']) > 1)
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="chart-container-wrapper">
                        <h6 class="text-center text-muted mb-3">{{ trans('AdminSb.temperature_chart') }}</h6>
                        <div class="chart-container"><canvas id="temperatureChart"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container-wrapper">
                        <h6 class="text-center text-muted mb-3">{{ trans('AdminSb.heart_rate_chart') }}</h6>
                        <div class="chart-container"><canvas id="heartRateChart"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container-wrapper">
                        <h6 class="text-center text-muted mb-3">{{ trans('AdminSb.blood_pressure_chart') }}</h6>
                        <div class="chart-container"><canvas id="bloodPressureChart"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container-wrapper">
                        <h6 class="text-center text-muted mb-3">{{ trans('AdminSb.oxygen_saturation_chart') }}</h6>
                        <div class="chart-container"><canvas id="oxygenSaturationChart"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container-wrapper">
                        <h6 class="text-center text-muted mb-3">{{ trans('AdminSb.respiratory_rate_chart') }}</h6>
                        <div class="chart-container"><canvas id="respiratoryRateChart"></canvas></div>
                    </div>
                </div>
            </div>
        @elseif(isset($chartData['labels']) && count($chartData['labels']) <= 1 && $patientAdmission->vitalSigns->isNotEmpty())
            <p class="text-center text-muted mt-4">{{ trans('AdminSb.at_least_two_readings_needed') }}</p>
        @endif
    </div>
@endsection

@section('js')
    {{-- تأكد من تحميل Chart.js إما هنا أو في التخطيط الرئيسي --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if (!empty($chartData) && isset($chartData['labels']) && count($chartData['labels']) > 1)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartData = @json($chartData); // استخدام chartData الممررة من المتحكم

                // نفس دالة createVitalSignChart ومكالمات إنشاء المخططات كما في الرد السابق
                function createVitalSignChart(canvasId, label, data, borderColor, yAxisLabel = '', suggestedMin =
                    undefined, suggestedMax = undefined) {
                    const ctx = document.getElementById(canvasId);
                    if (!ctx || !data || !data.some(val => val !== null && val !==
                            undefined)) { // تحقق إضافي من البيانات
                        console.warn(`Chart not rendered for ${canvasId}: No valid data or canvas not found.`);
                        const wrapper = ctx ? ctx.closest('.chart-container-wrapper') : document.querySelector(
                            `canvas#${canvasId}`)?.closest('.chart-container-wrapper');
                        if (wrapper) {
                            wrapper.innerHTML =
                                `<p class="text-center text-muted p-5">لا توجد بيانات كافية لعرض مخطط ${label}.</p>`;
                        }
                        return;
                    }
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
                                    suggestedMin: suggestedMin,
                                    suggestedMax: suggestedMax,
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
                                        maxTicksLimit: 10,
                                        font: {
                                            size: 10
                                        }
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
                                        usePointStyle: true,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    titleFont: {
                                        weight: 'bold'
                                    },
                                    bodySpacing: 5,
                                    padding: 10,
                                    cornerRadius: 4,
                                    callbacks: { // لتنسيق القيم في التلميح
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null) {
                                                label += context.parsed.y + (yAxisLabel.includes('%') ?
                                                    '%' : (yAxisLabel.includes('°C') ? '°C' : (
                                                        yAxisLabel.includes('bpm') ? ' bpm' : (
                                                            yAxisLabel.includes('rpm') ? ' rpm' : ''
                                                        ))));
                                            }
                                            return label;
                                        }
                                    }
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

                createVitalSignChart('temperatureChart', 'الحرارة', chartData.temperature, 'rgb(239, 68, 68)', '°C', 35,
                    42); // Red-500
                createVitalSignChart('heartRateChart', 'النبض', chartData.heartRate, 'rgb(59, 130, 246)', 'bpm', 40,
                    160); // Blue-500
                createVitalSignChart('oxygenSaturationChart', 'تشبع O2', chartData.oxygenSaturation,
                    'rgb(16, 185, 129)', '%', 85, 100); // Emerald-500
                createVitalSignChart('respiratoryRateChart', 'التنفس', chartData.respiratoryRate, 'rgb(245, 158, 11)',
                    'rpm', 10, 30); // Amber-500

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
                                    borderColor: 'rgb(217, 70, 239)', // Fuchsia-500
                                    backgroundColor: 'rgba(217, 70, 239, 0.1)',
                                    tension: 0.2,
                                    fill: false,
                                    pointBackgroundColor: 'rgb(217, 70, 239)',
                                    pointRadius: 3,
                                    pointHoverRadius: 6,
                                    borderWidth: 2
                                },
                                {
                                    label: 'انبساطي',
                                    data: chartData.bloodPressure.diastolic,
                                    borderColor: 'rgb(132, 204, 22)', // Lime-500
                                    backgroundColor: 'rgba(132, 204, 22, 0.1)',
                                    tension: 0.2,
                                    fill: false,
                                    pointBackgroundColor: 'rgb(132, 204, 22)',
                                    pointRadius: 3,
                                    pointHoverRadius: 6,
                                    borderWidth: 2
                                }
                            ]
                        },
                        options: { // نفس خيارات المخططات الأخرى
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
                                        maxTicksLimit: 10,
                                        font: {
                                            size: 10
                                        }
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
                                        usePointStyle: true,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    titleFont: {
                                        weight: 'bold'
                                    },
                                    bodySpacing: 5,
                                    padding: 10,
                                    cornerRadius: 4,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null) {
                                                label += context.parsed.y + ' mmHg';
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                } else if (bpCtx) { // إذا كان canvas موجوداً ولكن لا توجد بيانات لضغط الدم
                    const wrapper = bpCtx.closest('.chart-container-wrapper');
                    if (wrapper) wrapper.innerHTML =
                        `<p class="text-center text-muted p-5">لا توجد بيانات كافية لعرض مخطط ضغط الدم.</p>`;
                }
            });
        </script>
    @endif

    <script>
        // كود فتح نموذج الإضافة تلقائياً إذا كانت هناك أخطاء في vitalStore
        @if ($errors->vitalStore->any())
            var addVitalSignFormCollapse = document.getElementById('addVitalSignFormCollapse');
            if (addVitalSignFormCollapse && !bootstrap.Collapse.getInstance(addVitalSignFormCollapse)) {
                new bootstrap.Collapse(addVitalSignFormCollapse).show();
            } else if (addVitalSignFormCollapse && bootstrap.Collapse.getInstance(addVitalSignFormCollapse)) {
                bootstrap.Collapse.getInstance(addVitalSignFormCollapse).show();
            }
        @endif
    </script>
@endsection
