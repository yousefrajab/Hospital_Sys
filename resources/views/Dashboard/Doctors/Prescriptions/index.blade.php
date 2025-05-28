@extends('Dashboard.layouts.master')
@section('title', 'قائمة وصفاتي الطبية')

@section('css')
    @parent
    <link href="{{ URL::asset('dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    {{-- Updated --}}
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            /* #4361ee */
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-primary-dark: #3a58d8;
            --bs-secondary-rgb: 108, 117, 125;
            /* #6c757d */
            --bs-secondary: rgb(var(--bs-secondary-rgb));
            --bs-success-rgb: 25, 135, 84;
            /* #198754 */
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-info-rgb: 13, 202, 240;
            /* #0dcaf0 */
            --bs-info: rgb(var(--bs-info-rgb));
            --bs-warning-rgb: 255, 193, 7;
            /* #ffc107 */
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-danger-rgb: 220, 53, 69;
            /* #dc3545 */
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-light-rgb: 248, 249, 250;
            /* #f8f9fa */
            --bs-light: rgb(var(--bs-light-rgb));
            --bs-dark-rgb: 33, 37, 41;
            /* #212529 */
            --bs-dark: rgb(var(--bs-dark-rgb));
            --bs-body-bg: #f4f6f9;
            /* خلفية أفتح قليلاً */
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.5rem;
            --bs-card-box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.075);
            --admin-transition: all 0.2s ease-in-out;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bs-body-bg);
        }

        .card {
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            border: 1px solid var(--bs-border-color);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            /* خلفية بيضاء للهيدر */
            border-bottom: 1px solid var(--bs-border-color);
            padding: 1rem 1.25rem;
        }

        .card-title {
            font-weight: 600;
            color: var(--bs-dark);
        }

        .table thead th {
            background-color: var(--bs-light);
            color: var(--bs-dark);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom-width: 2px;
            white-space: nowrap;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.04);
        }

        .patient-avatar-sm-table {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 8px;
            /* RTL */
        }

        .patient-name-table a {
            color: var(--bs-primary);
            font-weight: 500;
            text-decoration: none;
        }

        .patient-name-table a:hover {
            text-decoration: underline;
            color: var(--bs-primary-dark);
        }

        .status-badge {
            padding: 0.35em 0.7em;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .status-new {
            background-color: rgba(var(--bs-info-rgb), 0.15);
            color: rgb(var(--bs-info-rgb));
            border: 1px solid rgba(var(--bs-info-rgb), 0.2);
        }

        .status-approved {
            background-color: rgba(var(--bs-primary-rgb), 0.15);
            color: var(--bs-primary);
            border: 1px solid rgba(var(--bs-primary-rgb), 0.2);
        }

        .status-dispensed {
            background-color: rgba(var(--bs-success-rgb), 0.15);
            color: rgb(var(--bs-success-rgb));
            border: 1px solid rgba(var(--bs-success-rgb), 0.2);
        }

        .status-partially_dispensed {
            background-color: rgba(var(--bs-warning-rgb), 0.2);
            color: #a17d06;
            border: 1px solid rgba(var(--bs-warning-rgb), 0.3);
        }

        /* لون أغمق قليلاً */
        .status-cancelled_by_doctor,
        .status-cancelled_by_pharmacist,
        .status-cancelled_by_patient {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
            border: 1px solid rgba(var(--bs-danger-rgb), 0.2);
        }

        .status-on_hold {
            background-color: rgba(var(--bs-secondary-rgb), 0.15);
            color: rgb(var(--bs-secondary-rgb));
            border: 1px solid rgba(var(--bs-secondary-rgb), 0.2);
        }

        .status-expired {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }


        .action-buttons .btn {
            margin: 0 2px;
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        .action-buttons .btn i {
            font-size: 0.9rem;
        }

        .empty-state-container {
            text-align: center;
            padding: 2.5rem 1rem;
            background-color: var(--bs-light);
            border-radius: var(--bs-card-border-radius);
            border: 1px dashed var(--bs-border-color);
        }

        .empty-state-container i {
            font-size: 3rem;
            color: var(--bs-border-color);
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state-container h5 {
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 0.5rem;
        }

        .empty-state-container p {
            color: var(--bs-body-color);
            font-size: 0.9rem;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            font-size: 0.9rem;
        }

        .filter-card .btn {
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-file-medical-alt text-primary me-2"></i>الوصفات
                    الطبية</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">قائمة وصفاتي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('doctor.patients.search_for_prescription') }}" class="btn btn-primary ripple shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> إنشاء وصفة جديدة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="card filter-card" data-aos="fade-down">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter text-primary me-2"></i>فلترة الوصفات</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('prescriptions.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label for="search_prescription_input" class="form-label">بحث برقم الوصفة / اسم المريض:</label>
                        <input type="text" name="search_prescription" id="search_prescription_input" class="form-control"
                            placeholder="أدخل رقم أو اسم..." value="{{ $request->search_prescription }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="status_filter_select" class="form-label">حالة الوصفة:</label>
                        <select name="status_filter" id="status_filter_select" class="form-select select2-filter"
                            data-placeholder="جميع الحالات">
                            <option value="">جميع الحالات</option>
                            @if (isset($prescriptionStatuses) && count($prescriptionStatuses) > 0)
                                @foreach ($prescriptionStatuses as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $request->status_filter == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="date_from_input" class="form-label">من تاريخ:</label>
                        <input type="text" name="date_from" id="date_from_input" class="form-control flatpickr-date"
                            placeholder="YYYY-MM-DD" value="{{ $request->date_from }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="date_to_input" class="form-label">إلى تاريخ:</label>
                        <input type="text" name="date_to" id="date_to_input" class="form-control flatpickr-date"
                            placeholder="YYYY-MM-DD" value="{{ $request->date_to }}">
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>
                            تطبيق</button>
                        @if (request()->hasAny(['search_prescription', 'status_filter', 'date_from', 'date_to']))
                            <a href="{{ route('prescriptions.index') }}"
                                class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                                <i class="fas fa-eraser me-1"></i> مسح الفلتر
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0"><i class="fas fa-list-alt me-2"></i>قائمة الوصفات الطبية <span
                    class="badge bg-primary rounded-pill ms-2">{{ $prescriptions->total() }}</span></h4>
            {{-- يمكن إضافة زر تصدير هنا --}}
        </div>
        <div class="card-body p-0"> {{-- إزالة الحشو من card-body ليلتصق الجدول بالهيدر --}}
            @if ($prescriptions->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-striped data-table"> {{-- استخدام data-table --}}
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم الوصفة</th>
                                <th style="min-width: 150px;">اسم المريض</th>
                                <th>تاريخ الوصفة</th>
                                <th>الحالة</th>
                                <th class="text-center">عدد الأدوية</th>
                                <th class="text-center" style="min-width: 180px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prescriptions as $prescription)
                                <tr>
                                    <td>{{ $loop->iteration + $prescriptions->firstItem() - 1 }}</td>
                                    <td><strong>{{ $prescription->prescription_number }}</strong></td>
                                    <td class="patient-name-table">
                                        @if ($prescription->patient)
                                            <img src="{{ $prescription->patient->image ? asset('Dashboard/img/patients/' . $prescription->patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png') }}"
                                                alt="" class="patient-avatar-sm-table">
                                            <a href="{{ route('admin.Patients.show', $prescription->patient_id) }}"
                                                target="_blank" data-bs-toggle="tooltip" title="عرض ملف المريض (للمشرف)">
                                                {{ $prescription->patient->name }}
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">مريض محذوف</span>
                                        @endif
                                    </td>
                                    <td>{{ $prescription->prescription_date->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @php
                                            $statusKey = $prescription->status;
                                            $statusText =
                                                $prescriptionStatuses[$statusKey] ??
                                                ucfirst(str_replace('_', ' ', $statusKey));
                                            $statusBadgeClass = 'status-' . str_replace('_', '-', $statusKey);
                                        @endphp
                                        <span class="status-badge {{ $statusBadgeClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $prescription->items_count ?? $prescription->items()->count() }}</td>
                                    <td class="text-center action-buttons">
                                        <a href="{{ route('prescriptions.show', $prescription->id) }}"
                                            class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                            title="عرض تفاصيل الوصفة">
                                            <i class="fas fa-eye"></i> <span class="d-none d-md-inline">عرض</span>
                                        </a>

                                        @php
                                            $editableStatuses = [
                                                \App\Models\Prescription::STATUS_NEW,
                                                \App\Models\Prescription::STATUS_APPROVED,
                                            ];
                                        @endphp
                                        @if (in_array($prescription->status, $editableStatuses))
                                            <a href="{{ route('prescriptions.edit', $prescription->id) }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                title="تعديل الوصفة">
                                                <i class="fas fa-edit"></i> <span class="d-none d-md-inline">تعديل</span>
                                            </a>
                                        @endif

                                        @php
                                            $cancellableStatuses = [
                                                \App\Models\Prescription::STATUS_NEW,
                                                \App\Models\Prescription::STATUS_APPROVED,
                                                \App\Models\Prescription::STATUS_ON_HOLD, // يمكن إلغاء وصفة معلقة
                                            ];
                                        @endphp
                                        @if (in_array($prescription->status, $cancellableStatuses))
                                            <form action="{{ route('prescriptions.destroy', $prescription->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من رغبتك في إلغاء هذه الوصفة؟ قد لا يمكن التراجع عن هذا الإجراء.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip" title="إلغاء الوصفة">
                                                    <i class="fas fa-times-circle"></i> <span
                                                        class="d-none d-md-inline">إلغاء</span>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 px-3 pb-3">
                    {{ $prescriptions->links() }} </div>
            @else
                <div class="empty-state-container p-4">
                    <i class="fas fa-file-export fa-3x"></i>
                    <h5 class="mt-3">لا توجد وصفات طبية لعرضها</h5>
                    <p class="text-muted">لم تقم بإنشاء أي وصفات بعد، أو أن نتائج الفلترة فارغة.</p>
                    @if (!request()->hasAny(['search_prescription', 'status_filter', 'date_from', 'date_to']))
                        <a href="{{ route('doctor.patients.search_for_prescription') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus-circle me-1"></i> ابدأ بإنشاء وصفة جديدة
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>


    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 700,
                once: true,
                offset: 30
            });

            $('.select2-filter').select2({ // كلاس خاص لفلاتر select2 لتجنب التعارض
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                dropdownParent: $(this).closest('.card-body') // أو .filter-card إذا كان مباشرًا
            });

            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                locale: "ar",
                allowInput: true
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // NotifIt messages
            @if (session('success'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>",
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 5000,
                    zindex: 99999
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>",
                    type: "error",
                    position: "top-center",
                    autohide: true,
                    timeout: 7000,
                    zindex: 99999
                });
            @endif
        });
    </script>
@endsection
