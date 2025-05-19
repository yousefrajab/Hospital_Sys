@extends('Dashboard.layouts.master')

@section('title')
    مخزون الدواء: {{ $medication->name }}
@endsection

@section('css')
    @parent
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        /* --- نفس متغيرات CSS وأنماط البطاقات والجداول والفورمات التي اتفقنا عليها --- */
        :root {
            /* ... (متغيراتك) ... */
        }

        body {
            /* ... */
        }

        .card {
            /* ... */
        }

        .card-header {
            /* ... */
        }

        .table thead th {
            /* ... */
        }

        .table td,
        .table th {
            /* ... */
        }

        .badge {
            /* ... */
        }

        .badge-light-success {
            background-color: rgba(var(--admin-success-rgb, 34, 197, 94), 0.1);
            color: #166534 !important;
            border: 1px solid rgba(var(--admin-success-rgb), 0.2);
        }

        .badge-light-warning {
            background-color: rgba(var(--admin-warning-rgb, 245, 158, 11), 0.15);
            color: #78350f !important;
            border: 1px solid rgba(var(--admin-warning-rgb), 0.3);
        }

        .badge-light-danger {
            background-color: rgba(var(--admin-danger-rgb, 239, 68, 68), 0.1);
            color: #991b1b !important;
            border: 1px solid rgba(var(--admin-danger-rgb), 0.2);
        }

        .action-buttons .btn {
            /* ... */
        }

        .form-control,
        .form-select {
            /* ... */
        }

        .select2-container--bootstrap-5 .select2-selection {
            /* ... */
        }

        .pagination-container {
            /* ... */
        }

        .page-item .page-link {
            /* ... */
        }

        .page-item.active .page-link {
            /* ... */
        }

        .page-item.disabled .page-link {
            /* ... */
        }

        /* أنماط خاصة ببطاقة ملخص المخزون */
        .stock-summary-card-revised {
            background-color: var(--admin-card-bg, #ffffff);
            border-radius: var(--admin-radius-lg, 0.5rem);
            /* استخدام radius من متغيراتك */
            padding: 1.25rem;
            /* تقليل الحشو قليلاً */
            margin-bottom: 1.5rem;
            box-shadow: var(--admin-shadow-md, 0 4px 8px rgba(0, 0, 0, 0.07));
            border: 1px solid var(--admin-border-color, #e5e7eb);
        }

        .dark .stock-summary-card-revised {
            background-color: var(--admin-card-bg) !important;
            border-color: var(--admin-border-color) !important;
        }

        .summary-header-revised {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            /* تقليل الحشو السفلي */
            border-bottom: 1px solid var(--admin-border-color, #e5e7eb);
        }

        .summary-header-revised .icon-wrapper-revised {
            background-color: rgba(var(--bs-primary-rgb, 79, 70, 229), 0.1);
            color: var(--admin-primary, #4f46e5);
            width: 45px;
            /* تصغير الأيقونة قليلاً */
            height: 45px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            /* تصغير حجم الأيقونة داخل الدائرة */
            margin-left: 0.75rem;
            /* RTL: margin-right */
        }

        .summary-header-revised h4 {
            font-size: 1.1rem;
            /* تصغير حجم الخط قليلاً */
            font-weight: 600;
            color: var(--admin-text, #1f2937);
            margin-bottom: 0.1rem;
        }

        .summary-header-revised .medication-name-revised {
            font-size: 0.95rem;
            /* تصغير اسم الدواء */
            font-weight: 700;
            color: var(--admin-primary, #4f46e5);
        }

        .summary-grid-revised.row {
            /* لتطبيق المسافات بشكل صحيح مع Bootstrap row */
            margin-right: -0.75rem;
            /* تعويض g-3 */
            margin-left: -0.75rem;
            /* تعويض g-3 */
        }

        .summary-grid-revised>[class*="col-"] {
            /* استهداف الأعمدة داخل الصف */
            padding-right: 0.75rem;
            /* جزء من g-3 */
            padding-left: 0.75rem;
            /* جزء من g-3 */
        }

        .summary-item-revised {
            background-color: var(--admin-bg, #f8f9fc);
            /* لون خلفية أفتح للعناصر */
            padding: 1rem;
            /* زيادة الحشو الداخلي للعناصر */
            border-radius: var(--admin-radius-md, 0.375rem);
            display: flex;
            /* استخدام Flexbox لمحاذاة الأيقونة والمحتوى */
            align-items: center;
            /* محاذاة رأسية */
            gap: 0.75rem;
            /* مسافة بين الأيقونة والمحتوى */
            border: 1px solid var(--admin-border-color, #e5e7eb);
            height: 100%;
            /* لجعل جميع البطاقات بنفس الارتفاع في الصف */
            transition: var(--admin-transition);
        }

        .summary-item-revised:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .dark .summary-item-revised {
            background-color: #2d3748;
        }

        .item-icon-revised {
            width: 40px;
            /* حجم أيقونة العنصر */
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            /* حجم الأيقونة داخل الدائرة */
            flex-shrink: 0;
            /* منع الأيقونة من التقلص */
        }

        /* كلاسات الألوان للأيقونات (يمكنك تعريف المزيد) */
        .bg-success-soft {
            background-color: rgba(var(--admin-success-rgb), 0.1);
        }

        .text-success {
            color: var(--admin-success) !important;
        }

        .bg-warning-soft {
            background-color: rgba(var(--admin-warning-rgb), 0.15);
        }

        .text-warning {
            color: var(--admin-warning) !important;
        }

        .bg-info-soft {
            background-color: rgba(var(--admin-info-rgb), 0.1);
        }

        .text-info {
            color: var(--admin-info) !important;
        }

        .bg-secondary-soft {
            background-color: rgba(108, 117, 125, 0.1);
        }

        /* لون ثانوي ناعم */
        .text-secondary {
            color: #6c757d !important;
        }

        /* يمكنك إضافة ألوان أخرى مثل: */
        /* .bg-light-grey { background-color: #e9ecef; } */
        /* .text-dark-grey { color: #495057; } */


        .item-content-revised {
            text-align: right;
            /* محاذاة النص لليمين */
            flex-grow: 1;
            /* لجعل المحتوى يأخذ المساحة المتبقية */
        }

        .item-content-revised .label {
            display: block;
            font-size: 0.75rem;
            /* تصغير الليبل */
            color: var(--admin-text-secondary, #6b7280);
            margin-bottom: 0.15rem;
            font-weight: 500;
        }

        .item-content-revised .value {
            font-size: 1.15rem;
            /* تصغير القيمة قليلاً */
            font-weight: 700;
            color: var(--admin-text, #1f2937);
            display: block;
            line-height: 1.2;
            /* تحسين تباعد الأسطر */
        }

        .item-content-revised .value.low-stock-value {
            /* تم تغيير اسم الكلاس */
            color: var(--admin-warning, #f59e0b);
        }

        .item-content-revised .value.low-stock-value i {
            font-size: 0.8em;
            /* تصغير أيقونة التحذير */
        }

        .item-content-revised .unit-label {
            font-size: 0.7rem;
            /* تصغير وحدة القياس */
            color: var(--admin-text-muted, #9ca3af);
            display: block;
            margin-top: 0.1rem;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-boxes-stacked fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة مخزون الصيدلية</h4>
                    <span class="text-muted mt-0 tx-13">/ دفعات الدواء: <strong>{{ $medication->name }}</strong></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('pharmacy_manager.medications.stocks.create', $medication->id) }}"
                class="btn btn-primary ripple">
                <i class="fas fa-plus-circle me-1"></i> إضافة دفعة جديدة
            </a>
            <a href="{{ route('pharmacy_manager.medications.index') }}" class="btn btn-outline-secondary btn-sm ms-2"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة لقائمة الأدوية
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    {{-- بطاقة ملخص المخزون للدواء الحالي --}}
    {{-- بطاقة ملخص المخزون للدواء الحالي (بتصميم محسن ومتقارب) --}}
    <div class="card stock-summary-card-revised animate__animated animate__fadeInDown">
        <div class="summary-header-revised">
            <div class="icon-wrapper-revised">
                <i class="fas fa-tablets"></i>
            </div>
            <div>
                <h4>ملخص مخزون الدواء</h4>
                <span class="medication-name-revised">{{ $medication->name }}</span>
            </div>
        </div>
        <div class="summary-grid-revised row g-3"> {{-- استخدام row و g-3 (gap) من Bootstrap --}}
            <div class="col-md-4 col-sm-6">
                <div class="summary-item-revised">
                    <div class="item-icon-revised bg-success-soft">
                        <i class="fas fa-cubes text-success"></i>
                    </div>
                    <div class="item-content-revised">
                        <span class="label">إجمالي المتوفر (صالح)</span>
                        <span class="value">{{ $totalQuantityOnHand ?? 0 }}</span>
                        <span class="unit-label">{{ $medication->unit_of_measure ?? 'وحدة' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="summary-item-revised">
                    <div class="item-icon-revised {{ $medication->is_low_stock ? 'bg-warning-soft' : 'bg-info-soft' }}">
                        <i
                            class="fas fa-thermometer-half {{ $medication->is_low_stock ? 'text-warning' : 'text-info' }}"></i>
                    </div>
                    <div class="item-content-revised">
                        <span class="label">حد الطلب الأدنى</span>
                        <span class="value {{ $medication->is_low_stock ? 'low-stock-value' : '' }}">
                            {{ $medication->minimum_stock_level }}
                            @if ($medication->is_low_stock)
                                <i class="fas fa-exclamation-triangle ms-1" title="المخزون منخفض!"></i>
                            @endif
                        </span>
                        <span class="unit-label">{{ $medication->unit_of_measure ?? 'وحدة' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="summary-item-revised">
                    <div class="item-icon-revised bg-secondary-soft"> {{-- استخدام لون مختلف --}}
                        <i class="fas fa-flask text-secondary"></i>
                    </div>
                    <div class="item-content-revised">
                        <span class="label">الاسم العلمي</span>
                        <span class="value">{{ $medication->generic_name ?: '-' }}</span>
                        <span class="unit-label"> </span> {{-- للمحاذاة الرأسية إذا لزم الأمر --}}
                    </div>
                </div>
            </div>
            {{-- يمكنك إضافة الباركود كعنصر رابع إذا أردت --}}
            <div class="col-md-4 col-sm-6">
                <div class="summary-item-revised">
                    <div class="item-icon-revised bg-light-grey">
                        <i class="fas fa-barcode text-dark-grey"></i>
                    </div>
                    <div class="item-content-revised">
                        <span class="label">باركود</span>
                        <span class="value">{{ $medication->barcode ?: '-' }}</span>
                        <span class="unit-label"> </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- بطاقة الفلترة لدفعات المخزون --}}
    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>فلترة البحث عن الدفعات</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pharmacy_manager.medications.stocks.index', $medication->id) }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="batch_search" class="form-label">بحث برقم الدفعة:</label>
                        <input type="text" name="batch_search" id="batch_search" class="form-control"
                            placeholder="أدخل رقم الدفعة..." value="{{ $request->batch_search }}">
                    </div>
                    <div class="col-md-4">
                        <label for="supplier_search" class="form-label">بحث باسم المورد:</label>
                        <input type="text" name="supplier_search" id="supplier_search" class="form-control"
                            placeholder="أدخل اسم المورد..." value="{{ $request->supplier_search }}">
                    </div>
                    <div class="col-md-4">
                        <label for="expired_filter" class="form-label">حالة الصلاحية:</label>
                        <select name="expired_filter" id="expired_filter" class="form-select select2"
                            data-placeholder="الكل">
                            @foreach ($expiryFilterOptions as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $request->expired_filter == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> تطبيق
                            الفلتر</button>
                        @if (request()->hasAny(['batch_search', 'supplier_search', 'expired_filter']))
                            <a href="{{ route('pharmacy_manager.medications.stocks.index', $medication->id) }}"
                                class="btn btn-outline-secondary"><i class="fas fa-eraser me-1"></i> مسح الفلتر</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- بطاقة جدول دفعات المخزون --}}
    <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-archive me-2"></i>دفعات المخزون للدواء: {{ $medication->name }}
                ({{ $stocks->total() }} دفعة)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>رقم الدفعة</th>
                            <th>الكمية الأولية</th>
                            <th>الكمية الحالية</th>
                            <th>تاريخ الصلاحية</th>
                            <th>سعر التكلفة/وحدة</th>
                            <th>المورد</th>
                            <th>تاريخ الاستلام</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $index => $stock)
                            <tr>
                                <td>{{ $stocks->firstItem() + $index }}</td>
                                <td class="fw-bold">{{ $stock->batch_number ?: '-' }}</td>
                                <td>{{ $stock->initial_quantity }}</td>
                                <td>{{ $stock->quantity_on_hand }}</td>
                                <td>
                                    {{ $stock->expiry_date->format('Y-m-d') }}
                                    @if ($stock->is_expired)
                                        <span class="badge badge-light-danger ms-1">منتهية</span>
                                    @elseif($stock->is_expiring_soon)
                                        <span class="badge badge-light-warning ms-1">قريبة من الانتهاء</span>
                                    @else
                                        <span class="badge badge-light-success ms-1">سارية</span>
                                    @endif
                                </td>
                                <td>{{ $stock->cost_price_per_unit !== null ? number_format($stock->cost_price_per_unit, 2) : '-' }}
                                </td>
                                <td>{{ $stock->supplier_name ?: '-' }}</td>
                                <td>{{ $stock->received_date ? $stock->received_date->format('Y-m-d') : '-' }}</td>
                                <td class="text-center action-buttons">
                                    {{-- لا يوجد Route::resource لـ stocks، الروابط محددة بشكل منفصل --}}
                                    {{-- <a href="{{ route('pharmacy_manager.stocks.show', $stock->id) }}" class="btn btn-sm btn-outline-success" title="عرض التفاصيل"><i class="fas fa-eye"></i></a> --}}
                                    <a href="{{ route('pharmacy_manager.stocks.edit', $stock->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="تعديل الدفعة"><i
                                            class="fas fa-edit"></i></a>
                                    <form action="{{ route('pharmacy_manager.stocks.destroy', $stock->id) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف دفعة المخزون هذه؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="حذف الدفعة"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i><br>
                                    لا توجد دفعات مخزون مسجلة لهذا الدواء حاليًا.
                                    @if (!request()->hasAny(['batch_search', 'supplier_search', 'expired_filter']))
                                        <br> <a
                                            href="{{ route('pharmacy_manager.medications.stocks.create', $medication->id) }}"
                                            class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i> أضف دفعة
                                            جديدة</a>
                                    @else
                                        <br> <span class="text-muted">حاول تعديل معايير الفلترة.</span>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($stocks->hasPages())
                <div class="mt-3 d-flex justify-content-center pagination-container">
                    {{ $stocks->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('#expired_filter').select2({
                placeholder: $(this).data('placeholder') || "الكل",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                language: "ar",
                minimumResultsForSearch: Infinity // إذا كانت الخيارات قليلة
            });

            // إظهار رسائل NotifIt
            @if (session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "bottom",
                    autohide: true,
                    timeout: 5000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                    type: "error",
                    position: "bottom",
                    autohide: true,
                    timeout: 7000
                });
            @endif
        });
    </script>
@endsection
