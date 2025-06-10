@extends('Dashboard.layouts.master')
@section('title', 'البحث عن الأدوية والمخزون')

@section('css')
    @parent
    <link href="{{ URL::asset('dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            /* ... (نفس متغيرات CSS من الرد السابق) ... */
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-danger-rgb: 220, 53, 69;
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-warning-rgb: 255, 193, 7;
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-info-rgb: 13, 202, 240;
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.65rem;
            --bs-card-box-shadow: 0 0.1rem 0.9rem rgba(58, 59, 69, 0.08);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .card-custom {
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            border: 1px solid var(--bs-border-color);
            background-color: #fff;
            margin-bottom: 1.5rem;
        }

        .card-custom .card-header {
            background-color: rgb(var(--bs-light-rgb));
            border-bottom: 1px solid var(--bs-border-color);
            padding: 1rem 1.25rem;
        }

        .card-custom .card-title {
            font-weight: 600;
            color: var(--bs-primary);
            margin-bottom: 0;
        }

        .card-custom .card-title i {
            margin-left: 0.5rem;
        }

        .table-medications thead th {
            background-color: rgba(var(--bs-primary-rgb), 0.07);
            color: var(--bs-primary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            border-bottom-width: 2px !important;
            white-space: nowrap;
            text-align: right;
        }

        .table-medications tbody td {
            vertical-align: middle;
            padding: 0.7rem 1rem;
            font-size: 0.875rem;
            border-top: 1px solid var(--bs-border-color);
            text-align: right;
        }

        .table-medications tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.03);
        }

        .stock-status-badge {
            padding: 0.3em 0.7em;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 500;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }

        .stock-status-danger {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
            border: 1px solid rgba(var(--bs-danger-rgb), 0.2);
        }

        .stock-status-warning {
            background-color: rgba(var(--bs-warning-rgb), 0.15);
            color: #a17d06;
            border: 1px solid rgba(var(--bs-warning-rgb), 0.25);
        }

        .stock-status-success {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: rgb(var(--bs-success-rgb));
            border: 1px solid rgba(var(--bs-success-rgb), 0.2);
        }

        .stock-status-secondary {
            background-color: rgba(var(--bs-secondary-rgb), 0.1);
            color: rgb(var(--bs-secondary-rgb));
            border: 1px solid rgba(var(--bs-secondary-rgb), 0.2);
        }

        .stock-status-dark {
            background-color: rgba(var(--bs-dark-rgb), 0.1);
            color: rgb(var(--bs-dark-rgb));
            border: 1px solid rgba(var(--bs-dark-rgb), 0.2);
        }


        .filter-form .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.2rem;
            color: #6c757d;
            font-weight: 500;
        }

        .filter-form .form-control,
        .filter-form .form-select {
            font-size: 0.85rem;
            padding: 0.45rem 0.8rem;
        }

        .filter-form .btn {
            font-size: 0.85rem;
            padding: 0.45rem 1rem;
        }

        .filter-form .select2-container--bootstrap-5 .select2-selection--single {
            min-height: calc(1.5em + (0.45rem * 2) + (1px * 2) + 2px);
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }


        .empty-state {
            text-align: center;
            padding: 2rem;
            background-color: rgb(var(--bs-light-rgb));
            border-radius: var(--bs-card-border-radius);
            border: 1px dashed var(--bs-border-color);
        }

        .empty-state i {
            font-size: 2.5rem;
            color: var(--bs-border-color);
            margin-bottom: 1rem;
            display: block;
        }

        .pagination .page-link {
            font-size: 0.85rem;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-capsules text-primary me-2"></i>الأدوية والمخزون
                </h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">بحث واستعلام</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="card card-custom filter-card" data-aos="fade-down">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>فلترة الأدوية</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('pharmacy_employee.medications.search') }}">
                <div class="row g-2 align-items-end"> {{-- g-2 لتقليل المسافة بين عناصر الفلتر --}}
                    <div class="col-lg-3 col-md-6">
                        <label for="search_med_term_pharma" class="form-label">اسم/علمي/باركود:</label>
                        <input type="text" name="search_medication_term" id="search_med_term_pharma" class="form-control"
                            placeholder="مصطلح البحث..." value="{{ $request->input('search_medication_term') }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="category_filter_pharma_search" class="form-label">التصنيف:</label>
                        <select name="category_filter" id="category_filter_pharma_search" class="form-select select2-filter"
                            data-placeholder="الكل">
                            <option value="">الكل</option>
                            @if (isset($categories))
                                @foreach ($categories as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $request->category_filter == $key ? 'selected' : '' }}>{{ $value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="stock_status_filter_search" class="form-label">حالة المخزون:</label>
                        <select name="stock_status_filter" id="stock_status_filter_search"
                            class="form-select select2-filter" data-placeholder="الكل">
                            @if (isset($stockStatusOptions))
                                @foreach ($stockStatusOptions as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $request->stock_status_filter == $key ? 'selected' : '' }}>{{ $value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="expiry_status_filter_search" class="form-label">حالة الصلاحية:</label>
                        <select name="expiry_status_filter" id="expiry_status_filter_search"
                            class="form-select select2-filter" data-placeholder="الكل">
                            @if (isset($expiryStatusOptions))
                                @foreach ($expiryStatusOptions as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $request->expiry_status_filter == $key ? 'selected' : '' }}>{{ $value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> بحث</button>
                        @if (request()->hasAny(['search_medication_term', 'category_filter', 'stock_status_filter', 'expiry_status_filter']))
                            <a href="{{ route('pharmacy_employee.medications.search') }}"
                                class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                                <i class="fas fa-eraser me-1"></i> مسح
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header">
            <h4 class="card-title">قائمة الأدوية (@if (isset($medications) && $medications->total() > 0)
                    {{ $medications->total() }}
                @else
                    0
                @endif)</h4>
        </div>
        <div class="card-body p-0">
            @if (isset($medications) && $medications->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-medications table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الدواء</th>
                                <th>الاسم العلمي</th>
                                <th>الباركود</th>
                                <th class="text-center">الكمية المتاحة</th>
                                <th class="text-center">أقرب تاريخ انتهاء</th>
                                <th class="text-center">حالة المخزون</th>
                                {{-- <th class="text-center">الإجراء</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medications as $medication)
                                <tr>
                                    <td>{{ $loop->iteration + $medications->firstItem() - 1 }}</td>
                                    <td>
                                        <strong>{{ $medication->name }}</strong>
                                        <small class="d-block text-muted">{{ $medication->strength ?: '' }}
                                            {{ $medication->dosage_form ? (isset(\App\Models\Medication::getCommonDosageForms()[$medication->dosage_form]) ? \App\Models\Medication::getCommonDosageForms()[$medication->dosage_form] : $medication->dosage_form) : '' }}</small>
                                    </td>
                                    <td>{{ $medication->generic_name ?: '-' }}</td>
                                    <td>{{ $medication->barcode ?: '-' }}</td>
                                    <td class="text-center">
                                        <strong
                                            class="text-primary">{{ $medication->total_available_quantity ?? 0 }}</strong>
                                        <small
                                            class="text-muted d-block">({{ $medication->unit_of_measure ? (isset(\App\Models\Medication::getCommonUnitsOfMeasure()[$medication->unit_of_measure]) ? \App\Models\Medication::getCommonUnitsOfMeasure()[$medication->unit_of_measure] : $medication->unit_of_measure) : '' }})</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($medication->nearest_expiry_date)
                                            <span
                                                class="stock-status-badge stock-status-{{ $medication->expiry_status_class ?? 'secondary' }}"
                                                data-bs-toggle="tooltip" title="أقرب تاريخ انتهاء صلاحية للكميات المتاحة">
                                                {{ \Carbon\Carbon::parse($medication->nearest_expiry_date)->translatedFormat('M Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="stock-status-badge stock-status-{{ $medication->stock_status_class ?? 'secondary' }}">
                                            {{ $medication->stock_status_text ?? 'غير معروف' }}
                                        </span>
                                    </td>
                                    {{-- <td class="text-center">
                                        <a href="{{ route('pharmacy_employee.medications.stocks.index', $medication->id) }}"
                                            class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                            title="عرض دفعات المخزون التفصيلية">
                                            <i class="fas fa-layer-group"></i> <span
                                                class="d-none d-md-inline">الدفعات</span>
                                        </a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($medications->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $medications->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @else
                <div class="empty-state p-5">
                    <i class="fas fa-tablets fa-3x"></i>
                    <h5 class="mt-3">لا توجد أدوية تطابق معايير البحث.</h5>
                    <p class="text-muted">حاول تعديل الفلاتر أو إضافة أدوية جديدة (إذا كنت المسؤول).</p>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 700,
                once: true,
                offset: 30
            });

            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "الكل",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                dropdownParent: $(this).closest('.card-body') // أو الحاوية المناسبة
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // NotifIt messages
            @if (session('success'))
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>`,
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 5000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>`,
                    type: "error",
                    position: "top-center",
                    autohide: true,
                    timeout: 7000
                });
            @endif
        });
    </script>
@endsection
