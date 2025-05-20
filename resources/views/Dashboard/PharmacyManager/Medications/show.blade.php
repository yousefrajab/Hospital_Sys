@extends('Dashboard.layouts.master')

@php
    $medicationNameForTitle = $medication->name ?? 'غير متوفر';
@endphp

@section('title', 'تفاصيل الدواء: ' . $medicationNameForTitle)

@section('css')
    @parent
    {{-- Font Awesome (if not globally loaded in master) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" /> --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        :root {
            --admin-primary: #0d6efd;
            --admin-secondary: #6c757d;
            --admin-success: #198754;
            --admin-info: #0dcaf0;
            --admin-warning: #ffc107;
            --admin-danger: #dc3545;
            --admin-light: #f8f9fa;
            --admin-dark: #212529;
            --admin-body-bg: #f4f6f9;
            /* لون خلفية الصفحة */
            --admin-card-bg: #ffffff;
            --admin-card-border-color: #dee2e6;
            --admin-text-muted: #6c757d;
            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.5rem;
            --admin-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --admin-shadow-lg: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
            /* ظل أكثر بروزاً */
            --admin-transition: all 0.25s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-body-bg: #1a202c;
                --admin-card-bg: #2d3748;
                --admin-card-border-color: #4a5568;
                --admin-text-muted: #a0aec0;
                --admin-light: #2d3748;
                /* تعديل للداكن */
            }
        }

        body {
            background-color: var(--admin-body-bg);
        }

        .details-card {
            background-color: var(--admin-card-bg);
            border: 1px solid var(--admin-card-border-color);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow-lg);
            margin-bottom: 2rem;
            overflow: hidden;
            /* مهم للـ header */
        }

        .details-card-header {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-info) 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid transparent;
            /* لا حاجة للحد */
        }

        .details-card-header h3 {
            margin-bottom: 0;
            font-weight: 600;
            font-size: 1.5rem;
            /* عنوان أكبر */
            display: flex;
            align-items: center;
        }

        .details-card-header h3 i {
            margin-inline-end: 0.75rem;
            font-size: 1.8rem;
            /* أيقونة أكبر للعنوان */
            opacity: 0.9;
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 0.5em 0.9em;
            border-radius: var(--admin-radius-md);
            font-weight: 500;
        }

        .details-card-body {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--admin-dark);
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--admin-primary);
            display: inline-block;
            /* ليأخذ الـ border-bottom عرض النص فقط */
        }

        .section-title i {
            margin-inline-end: 0.5rem;
            color: var(--admin-primary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            /* شبكة مرنة */
            gap: 1.25rem;
            /* مسافات أكبر */
            margin-bottom: 1.5rem;
        }

        .info-item {
            background-color: var(--admin-light);
            padding: 1rem;
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-card-border-color);
            transition: var(--admin-transition);
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--admin-shadow);
        }

        .info-item-label {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: var(--admin-text-muted);
            margin-bottom: 0.35rem;
            font-weight: 500;
        }

        .info-item-label i {
            margin-inline-end: 0.5rem;
            color: var(--admin-primary);
            width: 18px;
            /* تحديد عرض للأيقونة لتناسق المحاذاة */
            text-align: center;
        }

        .info-item-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--admin-dark);
            word-break: break-word;
            /* لكسر النصوص الطويلة */
        }

        .stock-table {
            margin-top: 0.5rem;
        }

        .stock-table th {
            background-color: var(--admin-light) !important;
            /* تعديل مهم */
            color: var(--admin-dark);
            font-weight: 600;
            font-size: 0.9rem;
            text-align: center;
            vertical-align: middle;
        }

        .stock-table td {
            font-size: 0.9rem;
            text-align: center;
            vertical-align: middle;
        }

        /* تلوين صفوف المخزون */
        .stock-table .table-danger,
        .stock-table .table-danger>th,
        .stock-table .table-danger>td {
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }

        .stock-table .table-warning,
        .stock-table .table-warning>th,
        .stock-table .table-warning>td {
            background-color: #fff3cd !important;
            color: #856404 !important;
        }

        @media (prefers-color-scheme: dark) {
            .stock-table th {
                background-color: #374151 !important;
            }

            .stock-table .table-danger,
            .stock-table .table-danger>th,
            .stock-table .table-danger>td {
                background-color: #4b2d30 !important;
                color: #f8d7da !important;
            }

            .stock-table .table-warning,
            .stock-table .table-warning>th,
            .stock-table .table-warning>td {
                background-color: #4d4123 !important;
                color: #fff3cd !important;
            }
        }

        .btn-action-group {
            display: flex;
            gap: 0.75rem;
        }

        .btn-icon-text .fas,
        .btn-icon-text .far {
            margin-inline-end: 0.35rem;
        }

        .alert-custom-info {
            background-color: var(--admin-info-bg, #e0f7fa);
            /* لون أزرق فاتح جداً */
            color: var(--admin-info-text, #007bff);
            border-color: var(--admin-info-border, #b3e5fc);
            padding: 1rem;
            border-radius: var(--admin-radius-md);
        }

        @media (prefers-color-scheme: dark) {
            .alert-custom-info {
                background-color: #2c5282;
                color: #bee3f8;
                border-color: #4299e1;
            }
        }

        .no-stock-message i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-lg me-2 text-primary"></i> {{-- أيقونة معلومات --}}
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة المخزون الدوائي</h4>
                    <span class="text-muted mt-0 tx-13">/ تفاصيل الدواء:
                        <strong>{{ $medicationNameForTitle }}</strong></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content btn-action-group">
            <a href="{{ route('pharmacy_manager.medications.edit', $medication->id) }}"
                class="btn btn-outline-warning btn-sm btn-icon-text">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <a href="{{ route('pharmacy_manager.medications.index') }}"
                class="btn btn-outline-secondary btn-sm btn-icon-text">
                <i class="fas fa-arrow-left"></i> القائمة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row">
        <div class="col-lg-12">
            <div class="details-card animate__animated animate__fadeInUp">
                <div class="details-card-header">
                    <h3><i class="fas fa-capsules"></i> {{ $medicationNameForTitle }}</h3>
                    <span class="status-badge bg-{{ $medication->status ? 'success' : 'danger' }}">
                        {{ $medication->status ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>

                <div class="details-card-body">
                    <h4 class="section-title"><i class="fas fa-file-medical-alt"></i> معلومات الدواء الأساسية</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-tag"></i>الاسم التجاري</span>
                            <strong class="info-item-value">{{ $medication->name }}</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-flask-vial"></i>الاسم العلمي</span>
                            <strong class="info-item-value">{{ $medication->generic_name ?? '-' }}</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-tablets"></i>الشكل الصيدلاني</span>
                            <strong class="info-item-value">{{ $medication->dosage_form ?? '-' }}</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-weight-scale"></i>التركيز/القوة</span>
                            <strong class="info-item-value">{{ $medication->strength ?? '-' }}</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-bell"></i>حد إعادة الطلب</span>
                            <strong class="info-item-value">{{ $medication->minimum_stock_level }}</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="far fa-calendar-plus"></i>تاريخ الإضافة</span>
                            <strong
                                class="info-item-value">{{ $medication->created_at->translatedFormat('d F Y, H:i') }}</strong>
                        </div>
                        {{-- مثال لعرض حقل ملاحظات إذا كان موجودًا --}}
                        @if ($medication->notes)
                            <div class="info-item" style="grid-column: 1 / -1;"> {{-- يمتد على كامل العرض --}}
                                <span class="info-item-label"><i class="far fa-sticky-note"></i>ملاحظات إضافية</span>
                                <p class="info-item-value" style="white-space: pre-wrap;">{{ $medication->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <hr class="my-4"> {{-- فاصل بصري أوضح --}}

                    <h4 class="section-title"><i class="fas fa-boxes-stacked"></i> المخزون المتوفر (الدفعات النشطة)</h4>
                    @if ($medication->relationLoaded('stocks') && $medication->stocks->where('quantity_on_hand', '>', 0)->count() > 0)
                        <div class="table-responsive shadow-sm"
                            style="border-radius: var(--admin-radius-md); overflow: hidden;">
                            <table class="table table-sm table-bordered stock-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الدفعة</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th>الكمية الحالية</th>
                                        <th>أُضيفت بتاريخ</th>
                                        <th>ملاحظات الدفعة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($medication->stocks->where('quantity_on_hand', '>', 0)->sortBy('expiry_date') as $index => $stock)
                                        <tr
                                            class="{{ $stock->expiry_date->isPast() ? 'table-danger' : ($stock->expiry_date->isBefore(now()->addMonths(config('pharmacy.expiry_warning_months', 3))) ? 'table-warning' : '') }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stock->batch_number ?? 'N/A' }}</td>
                                            <td>
                                                {{ $stock->expiry_date->translatedFormat('M Y') }}
                                                @if ($stock->expiry_date->isPast())
                                                    <i class="fas fa-exclamation-triangle text-danger ms-1"
                                                        title="منتهي الصلاحية"></i>
                                                @elseif($stock->expiry_date->isBefore(now()->addMonths(config('pharmacy.expiry_warning_months', 3))))
                                                    <i class="fas fa-exclamation-circle text-warning ms-1"
                                                        title="قارب على الانتهاء"></i>
                                                @endif
                                            </td>
                                            <td><strong>{{ $stock->quantity_on_hand }}</strong></td>
                                            <td>{{ $stock->created_at->translatedFormat('d M Y') }}</td>
                                            <td>{{ $stock->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="form-text text-muted mt-2 d-block">
                            <span class="badge bg-danger-soft me-1"> </span> منتهي الصلاحية
                            <span class="badge bg-warning-soft ms-2 me-1"> </span> قارب على الانتهاء (خلال
                            {{ config('pharmacy.expiry_warning_months', 3) }} أشهر)
                        </small>
                    @else
                        <div class="alert alert-custom-info text-center no-stock-message">
                            <i class="fas fa-box-open d-block"></i>
                            <h5 class="mb-1">لا يوجد مخزون مسجل أو متوفر حاليًا لهذا الدواء.</h5>
                            <p class="mb-0">يمكنك إضافة دفعات جديدة من صفحة إدارة المخزون.</p>
                        </div>
                    @endif

                    <div class="text-center mt-4">
                        {{-- <a href="{{ route('pharmacy_manager.stocks.index', ['medication_id' => $medication->id]) }}"
                            class="btn btn-primary btn-sm btn-icon-text">
                            <i class="fas fa-tasks"></i> إدارة دفعات المخزون
                        </a> --}}
                        {{-- <a href="{{ route('pharmacy_manager.medications.stocks.create', ['medication_id' => $medication->id]) }}"
                            class="btn btn-success btn-sm btn-icon-text ms-2">
                            <i class="fas fa-plus-circle"></i> إضافة دفعة جديدة
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        $(function() {
            // Tooltips for expiry icons (if Bootstrap JS is loaded)
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // NotifIt messages
            @if (session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "bottom-right",
                    autohide: true,
                    timeout: 5000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                    type: "error",
                    position: "bottom-right",
                    autohide: true,
                    timeout: 7000
                });
            @endif
        });
    </script>
@endsection
