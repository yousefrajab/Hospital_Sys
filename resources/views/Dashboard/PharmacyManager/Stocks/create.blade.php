@extends('Dashboard.layouts.master')

@section('title')
    إضافة دفعة مخزون لـ: {{ $medication->name }}
@endsection

@section('css')
    @parent
    {{-- NotifIt للإشعارات --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- Select2 للـ dropdowns (إذا كنت ستستخدمه لحقول أخرى مثل الموردين لاحقًا) --}}
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- Flatpickr للتاريخ --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* ==========================================================================
           Global Variables & Base Styles for Admin Dashboard (Full)
           ========================================================================== */
        :root {
            --admin-primary: #4f46e5; /* بنفسجي جذاب (Indigo-600) */
            --admin-primary-dark: #4338ca; /* أغمق قليلاً للـ hover */
            --admin-primary-light: #c7d2fe; /* أفتح للتلميحات أو الخلفيات الناعمة */

            --admin-secondary: #10b981; /* أخضر زمردي (Emerald-600) */
            --admin-secondary-dark: #059669;
            --admin-secondary-light: #a7f3d0;

            --admin-accent: #f59e0b; /* برتقالي/أصفر (Amber) - للتحذيرات أو التمييز */
            --admin-accent-dark: #d97706;

            --admin-success: #22c55e; /* أخضر للنجاح (Green-500) */
            --admin-danger: #ef4444;  /* أحمر للخطر (Red-500) */
            --admin-danger-dark: #c82333; /* أغمق للخطر */
            --admin-warning: #facc15; /* أصفر للتحذيرات (Yellow-400) */
            --admin-info: #3b82f6;   /* أزرق للمعلومات (Blue-500) */

            --admin-bg: #f8f9fc;             /* خلفية الصفحة الرئيسية (Gray-50) */
            --admin-card-bg: #ffffff;        /* خلفية البطاقات، الجداول، المودالات */
            --admin-sidebar-bg: #2d3748;     /* مثال لخلفية الشريط الجانبي (داكن) */

            --admin-text: #1f2937;           /* لون النص الأساسي (Gray-800) */
            --admin-text-light: #f9fafb;     /* لون النص على خلفيات داكنة (Gray-50) */
            --admin-text-secondary: #6b7280; /* لون النص الثانوي (Gray-500) */
            --admin-text-muted: #9ca3af;     /* نص باهت أو تلميحات (Gray-400) */

            --admin-border-color: #e5e7eb;   /* لون الحدود العام (Gray-200) */
            --admin-input-border: #d1d5db;   /* لون حدود حقول الإدخال (Gray-300) */

            --admin-radius-sm: 0.25rem;      /* 4px */
            --admin-radius-md: 0.375rem;     /* 6px */
            --admin-radius-lg: 0.5rem;       /* 8px */
            --admin-radius-xl: 0.75rem;      /* 12px */
            --admin-radius-full: 9999px;     /* للحواف الدائرية بالكامل */

            --admin-shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px -1px rgba(0, 0, 0, 0.07);
            --admin-shadow-md: 0 4px 8px -1px rgba(0, 0, 0, 0.07), 0 2px 6px -2px rgba(0, 0, 0, 0.07);
            --admin-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);

            --admin-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);

            --bs-primary-rgb: 79, 70, 229; /* لـ Bootstrap 5 shadow/focus colors */
            --admin-success-rgb: 34,197,94;
            --admin-danger-rgb: 239,68,68;
            --admin-warning-rgb: 245,158,11;
            --admin-info-rgb: 59,130,246;
        }

        /* === Dark Mode Styles === */
        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827; --admin-card-bg: #1f2937; --admin-text: #e5e7eb;
                --admin-text-secondary: #9ca3af; --admin-border-color: #374151;
                --admin-input-border: #4b5563; --admin-primary: #6366f1;
                --admin-primary-dark: #4f46e5; --admin-secondary: #34d399;
            }
            .form-control, .form-select, .select2-container--bootstrap-5 .select2-selection, .flatpickr-input {
                background-color: #2d3748 !important;
                border-color: var(--admin-input-border) !important;
                color: var(--admin-text) !important;
            }
            .select2-container--bootstrap-5 .select2-dropdown { background-color: #2d3748; border-color: var(--admin-input-border); }
            .select2-container--bootstrap-5 .select2-results__option { color: var(--admin-text); }
            .select2-container--bootstrap-5 .select2-results__option--highlighted { background-color: var(--admin-primary) !important; }
            .card { border-color: var(--admin-border-color); background-color: var(--admin-card-bg); }
        }
        /* يمكنك أيضًا إضافة كلاس .dark على body لتفعيل الوضع الداكن يدويًا */
        body.dark { /* ... (تطبيق متغيرات الوضع الداكن) ... */ }


        /* === Base Styles === */
        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); line-height: 1.6; }

        /* --- أنماط البطاقة الرئيسية للفورم --- */
        .form-container-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-xl); /* استخدام radius أكبر للبطاقة الرئيسية */
            box-shadow: var(--admin-shadow-md);
            border: 1px solid var(--admin-border-color);
            margin-bottom: 2rem;
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        .card-header-custom {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
            border-radius: var(--admin-radius-xl) var(--admin-radius-xl) 0 0;
        }
        .card-header-custom .card-title { font-weight: 600; font-size: 1.2rem; margin-bottom:0; }
        .card-header-custom .card-title i { margin-left: 0.75rem; opacity: 0.9;} /* RTL: margin-right */

        .card-body-custom { padding: 2rem; }

        /* --- أنماط حقول النموذج --- */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 500; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--admin-text-secondary); }
        .form-control, .form-select {
            display: block; width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem; line-height: 1.6;
            color: var(--admin-text); background-color: var(--admin-card-bg);
            background-clip: padding-box; border: 1px solid var(--admin-input-border);
            appearance: none; border-radius: var(--admin-radius-md);
            box-shadow: var(--admin-shadow-xs);
            transition: border-color var(--admin-transition), box-shadow var(--admin-transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--admin-primary);
            outline: 0;
            box-shadow: var(--admin-shadow-xs), 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }
        textarea.form-control { min-height: 90px; }
        .flatpickr-input { background-color: var(--admin-card-bg) !important; } /* للتأكد أن flatpickr يتبع الثيم */


        /* Select2 Styles */
        .select2-container--bootstrap-5 .select2-selection--single {
            border: 1px solid var(--admin-input-border) !important;
            border-radius: var(--admin-radius-md) !important;
            padding: 0.55rem 0.75rem !important; /* تعديل ليتناسب مع ارتفاع form-control */
            min-height: calc(1.6em + (0.75rem * 2) + 2px) !important;
            font-size: 0.95rem !important;
            display: flex; align-items: center;
            background-color: var(--admin-card-bg) !important;
            color: var(--admin-text) !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: var(--admin-text) !important;
            line-height: 1.6 !important;
            padding-left: 0 !important; padding-right: 0 !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.6em + (0.75rem * 2) - 2px) !important;
            top: 1px !important;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--admin-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25) !important;
        }
        /* ... (بقية أنماط Select2 إذا احتجت) ... */

        /* --- أنماط أزرار الإجراءات --- */
        .form-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border-color); }
        .btn-submit-form, .btn-cancel-form {
            padding: 0.75rem 1.5rem; border-radius: var(--admin-radius-md); font-weight: 600;
            transition: var(--admin-transition); border: none; font-size: 0.95rem;
            display: inline-flex; align-items: center; justify-content: center; text-decoration: none;
        }
        .btn-submit-form { background-color: var(--admin-primary); color: white; }
        .btn-submit-form:hover { background-color: var(--admin-primary-dark); transform: translateY(-2px); box-shadow: var(--admin-shadow-md); }
        .btn-cancel-form { background-color: #6c757d; color: white; }
        .btn-cancel-form:hover { background-color: #5a6268; transform: translateY(-2px); box-shadow: var(--admin-shadow-md); }
        .btn-submit-form i, .btn-cancel-form i { margin-left: 0.5rem; /* RTL: margin-right */ }

        /* أنماط التحقق */
        .is-invalid { border-color: var(--admin-danger) !important; }
        .invalid-feedback { color: var(--admin-danger); font-size: 0.875em; display: block; margin-top: 0.25rem;}
        .was-validated .form-control:valid, .was-validated .form-select:valid { border-color: var(--admin-success) !important; }

        /* Breadcrumb */
        .breadcrumb-header { margin-bottom: 1.5rem; padding: 0.75rem 0; border-bottom: 1px solid var(--admin-border-color); }
        .breadcrumb-header .content-title { font-size: 1.25rem; font-weight: 600; color: var(--admin-text); }
        .breadcrumb-header .text-muted { color: var(--admin-text-secondary) !important; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-dolly-flatbed fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto" >إدارة مخزون الصيدلية</h4>
                    <span class="text-muted mt-0 tx-13">/ إضافة دفعة جديدة للدواء: <strong>{{ $medication->name }}</strong></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('pharmacy_manager.medications.stocks.index', $medication->id) }}" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة لمخزون الدواء
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="form-container-card animate__animated animate__fadeInUp">
                <div class="card-header card-header-custom">
                    <h3 class="card-title mb-0" style="color: white"><i class="fas fa-plus-circle"></i> إضافة دفعة مخزون جديدة لـ "{{ $medication->name }}"</h3>
                </div>
                <div class="card-body card-body-custom">
                    <form action="{{ route('pharmacy_manager.medications.stocks.store', $medication->id) }}" method="POST" class="needs-validation" novalidate autocomplete="off">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_number" class="form-label">رقم الدفعة</label>
                                    <input type="text" name="batch_number" id="batch_number" class="form-control @error('batch_number') is-invalid @enderror" value="{{ old('batch_number') }}" placeholder="رقم أو كود الدفعة (اختياري)">
                                    @error('batch_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expiry_date" class="form-label">تاريخ انتهاء الصلاحية <span class="text-danger">*</span></label>
                                    <input type="text" name="expiry_date" id="expiry_date" class="form-control flatpickr-date @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}" required placeholder="YYYY-MM-DD">
                                    @error('expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="initial_quantity" class="form-label">الكمية الأولية <span class="text-danger">*</span></label>
                                    <input type="number" name="initial_quantity" id="initial_quantity" class="form-control @error('initial_quantity') is-invalid @enderror" value="{{ old('initial_quantity') }}" required min="1">
                                    @error('initial_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity_on_hand" class="form-label">الكمية الحالية <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity_on_hand" id="quantity_on_hand" class="form-control @error('quantity_on_hand') is-invalid @enderror" value="{{ old('quantity_on_hand') }}" required min="0">
                                    @error('quantity_on_hand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="form-text text-muted">عادةً ما تكون هذه الكمية هي نفسها الكمية الأولية عند الإضافة.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost_price_per_unit" class="form-label">سعر تكلفة الوحدة</label>
                                    <input type="text" name="cost_price_per_unit" id="cost_price_per_unit" class="form-control @error('cost_price_per_unit') is-invalid @enderror" value="{{ old('cost_price_per_unit') }}" placeholder="0.00">
                                    @error('cost_price_per_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_name" class="form-label">اسم المورد</label>
                                    <input type="text" name="supplier_name" id="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" value="{{ old('supplier_name') }}">
                                    @error('supplier_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="received_date" class="form-label">تاريخ استلام الدفعة</label>
                                    <input type="text" name="received_date" id="received_date" class="form-control flatpickr-date @error('received_date') is-invalid @enderror" value="{{ old('received_date') }}" placeholder="YYYY-MM-DD">
                                    @error('received_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="stock_notes" class="form-label">ملاحظات على الدفعة</label>
                                    <textarea name="stock_notes" id="stock_notes" class="form-control @error('stock_notes') is-invalid @enderror" rows="3">{{ old('stock_notes') }}</textarea>
                                    @error('stock_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('pharmacy_manager.medications.stocks.index', $medication->id) }}" class="btn btn-cancel-form">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-submit-form">
                                <i class="fas fa-plus-circle me-2"></i> إضافة الدفعة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2 (إذا كان هناك أي حقول select2 في هذا الفورم)
            // $('.select2').select2({ /* ... */ });

            // تهيئة Flatpickr لحقول التاريخ
            const flatpickrConfig = {
                dateFormat: "Y-m-d",
                locale: "ar",
                allowInput: true,
                disableMobile: true
            };
            flatpickr("#expiry_date", { ...flatpickrConfig, minDate: "today" });
            flatpickr("#received_date", { ...flatpickrConfig, maxDate: "today" });

            // التحقق من صحة النموذج
            (function () {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if ($('#initial_quantity').val() && !$('#quantity_on_hand').val()) {
                                $('#quantity_on_hand').val($('#initial_quantity').val());
                            }
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
            })();

            $('#initial_quantity').on('input', function() {
                $('#quantity_on_hand').val($(this).val());
            });

            // عرض رسائل NotifIt
            @if (session('success'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "bottom", autohide: true, timeout: 5000 });
            @endif
            @if (session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "bottom", autohide: true, timeout: 7000 });
            @endif
            @if ($errors->any())
                let errorMsg = "<strong><i class='fas fa-times-circle me-2'></i> يرجى تصحيح الأخطاء التالية:</strong><ul class='mb-0 ps-3 mt-1' style='list-style-type: none; padding-right: 0;'>";
                @foreach ($errors->all() as $error)
                    errorMsg += "<li>- {{ $error }}</li>";
                @endforeach
                errorMsg += "</ul>";
                notif({ msg: errorMsg, type: "error", position: "bottom", multiline: true, autohide: false });
            @endif
        });
    </script>
@endsection
