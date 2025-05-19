@extends('Dashboard.layouts.master')
@section('title', 'تعديل بيانات الدواء: ' . ($medication->name ?? 'دواء غير معروف'))

@section('css')
    @parent {{-- لاستيراد CSS الأساسي من الـ layout --}}
    {{-- NotifIt للإشعارات --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- Select2 للـ dropdowns --}}
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        /* --- متغيرات CSS العامة (يفضل وضعها في ملف CSS مركزي أو layout) --- */
        :root {
            --admin-primary: #4f46e5; /* Indigo-600 */
            --admin-primary-dark: #4338ca; /* Indigo-700 */
            --admin-primary-light: #c7d2fe; /* Indigo-200 */
            --admin-secondary: #10b981; /* Emerald-600 */
            --admin-success: #22c55e; /* Green-500 */
            --admin-danger: #ef4444;  /* Red-500 */
            --admin-danger-dark: #c82333; /* أغمق للخطر */
            --admin-warning: #f59e0b; /* Amber-500 */
            --admin-info: #3b82f6;   /* Blue-500 */
            --admin-bg: #f8f9fc; /* Gray-50 */
            --admin-card-bg: #ffffff; /* White */
            --admin-text: #1f2937; /* Gray-800 */
            --admin-text-secondary: #6b7280; /* Gray-500 */
            --admin-border-color: #e5e7eb; /* Gray-200 */
            --admin-input-border: #d1d5db; /* Gray-300 */
            --admin-radius-sm: 0.25rem;  /* 4px */
            --admin-radius-md: 0.375rem; /* 6px */
            --admin-radius-lg: 0.5rem;   /* 8px */
            --admin-radius-xl: 0.75rem;  /* 12px */
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px -1px rgba(0, 0, 0, 0.07);
            --admin-shadow-md: 0 4px 8px -1px rgba(0, 0, 0, 0.07), 0 2px 6px -2px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --bs-primary-rgb: 79, 70, 229; /* لـ Bootstrap 5 shadow/focus colors */
            --admin-success-rgb: 34,197,94;
            --admin-danger-rgb: 239,68,68;
        }

        /* --- أنماط الوضع الداكن (Dark Mode) --- */
        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827; --admin-card-bg: #1f2937; --admin-text: #e5e7eb;
                --admin-text-secondary: #9ca3af; --admin-border-color: #374151;
                --admin-input-border: #4b5563; --admin-primary: #6366f1;
                --admin-primary-dark: #4f46e5; --admin-secondary: #34d399;
            }
            /* ... (تجاوزات الألوان للوضع الداكن كما في الردود السابقة) ... */
        }
        /* يمكنك إضافة كلاس .dark على body لتفعيل الوضع الداكن يدويًا */
        body.dark { /* ... (تطبيق متغيرات الوضع الداكن) ... */ }


        /* --- أنماط عامة للصفحة --- */
        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }

        /* --- أنماط البطاقة الرئيسية للفورم --- */
        .form-container-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow-md); /* ظل أكبر قليلاً */
            border: 1px solid var(--admin-border-color);
            margin-bottom: 2rem; /* مسافة سفلية أكبر */
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        .card-header-custom {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid transparent; /* لإزالة أي خط سفلي من Bootstrap */
            border-radius: var(--admin-radius-lg) var(--admin-radius-lg) 0 0; /* تطبيق الحواف الدائرية للهيدر */
        }
        .card-header-custom .card-title { font-weight: 600; font-size: 1.2rem; margin-bottom:0; }
        .card-header-custom .card-title i { margin-left: 0.75rem; /* RTL: margin-right */ opacity: 0.9;}

        .card-body-custom { padding: 2rem; } /* حشو أكبر لجسم البطاقة */

        /* --- أنماط حقول النموذج --- */
        .form-group { margin-bottom: 1.25rem; } /* تقليل المسافة قليلاً */
        .form-label { display: block; font-weight: 500; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--admin-text-secondary); }
        .form-control, .form-select {
            display: block; width: 100%;
            padding: 0.75rem 1rem; /* حشو موحد */
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
        textarea.form-control { min-height: 90px; /* ارتفاع أدنى للـ textarea */ }

        /* Select2 Styles */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: var(--admin-radius-md) !important;
            border: 1px solid var(--admin-input-border) !important;
            padding: 0.55rem 0.75rem !important; /* تعديل ليتناسب مع ارتفاع form-control */
            min-height: calc(1.6em + (0.75rem * 2) + 2px) !important; /* حساب الارتفاع */
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
            height: calc(1.6em + (0.75rem * 2) - 2px) !important; /* تعديل ارتفاع السهم */
            top: 1px !important; /* محاذاة أفضل للسهم */
        }
        /* ... (بقية أنماط Select2 كما هي) ... */


        /* --- أنماط أزرار الإجراءات --- */
        .form-actions {
            display: flex;
            justify-content: space-between; /* لتوزيع الأزرار */
            align-items: center;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--admin-border-color);
        }
        .btn-submit-form, .btn-cancel-form, .btn-delete-form {
            padding: 0.75rem 1.5rem;
            border-radius: var(--admin-radius-md);
            font-weight: 600;
            transition: var(--admin-transition);
            border: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .btn-submit-form { background-color: var(--admin-primary); color: white; }
        .btn-submit-form:hover { background-color: var(--admin-primary-dark); transform: translateY(-2px); box-shadow: var(--admin-shadow-md); }
        .btn-cancel-form { background-color: #6c757d; color: white; } /* Gray-600 */
        .btn-cancel-form:hover { background-color: #5a6268; transform: translateY(-2px); box-shadow: var(--admin-shadow-md); }
        .btn-delete-form { background-color: var(--admin-danger); color: white; }
        .btn-delete-form:hover { background-color: var(--admin-danger-dark, #c82333); transform: translateY(-2px); box-shadow: var(--admin-shadow-md); }
        .btn-submit-form i, .btn-cancel-form i, .btn-delete-form i { margin-left: 0.5rem; /* RTL: margin-right */ }

        /* أنماط التحقق */
        .is-invalid { border-color: var(--admin-danger) !important; }
        .invalid-feedback { color: var(--admin-danger); font-size: 0.875em; display: block; margin-top: 0.25rem;}
        .was-validated .form-control:valid, .was-validated .form-select:valid { border-color: var(--admin-success) !important; }

        /* أنماط المودال (مشابهة لما أرسلته سابقًا) */
        .modal-header.bg-gradient-danger { background: linear-gradient(135deg, var(--admin-danger), var(--admin-danger-dark)); border-bottom: none; }
        .modal-title { font-weight: 600; }
        .modal-body .fa-pills { font-size: 3rem; color: var(--admin-danger); margin-bottom: 1rem; }
        .btn-close-white { filter: brightness(0) invert(1); }
        .modal-footer { border-top: none; }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-edit fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة الأدوية</h4>
                    <span class="text-muted mt-0 tx-13">/ تعديل دواء: {{ $medication->name }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('pharmacy_manager.medications.show', $medication->id) }}" class="btn btn-outline-info btn-sm me-2" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-eye me-1"></i> عرض الدواء
            </a>
            <a href="{{ route('pharmacy_manager.medications.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة للقائمة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="form-container-card animate__animated animate__fadeInUp">
                <div class="card-header card-header-custom">
                    <h3 class="card-title mb-0"><i class="fas fa-capsules"></i> تعديل بيانات الدواء: {{ $medication->name }}</h3>
                </div>
                <div class="card-body card-body-custom">
                    <form action="{{ route('pharmacy_manager.medications.update', $medication->id) }}" method="POST" class="needs-validation" novalidate autocomplete="off">
                        @method('PUT')
                        @csrf
                        <div class="row g-3">
                            {{-- اسم الدواء --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">اسم الدواء (التجاري/الأساسي) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $medication->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الاسم العلمي --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="generic_name" class="form-label">الاسم العلمي (Generic)</label>
                                    <input type="text" name="generic_name" id="generic_name" class="form-control @error('generic_name') is-invalid @enderror" value="{{ old('generic_name', $medication->generic_name) }}">
                                    @error('generic_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- التصنيف --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category" class="form-label">تصنيف الدواء</label>
                                    <select name="category" id="category" class="form-select select2 @error('category') is-invalid @enderror" data-placeholder="-- اختر التصنيف --">
                                        <option value=""></option>
                                        @foreach($categories as $key => $value)
                                            <option value="{{ $key }}" {{ old('category', $medication->category) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الشركة المصنعة --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="manufacturer" class="form-label">الشركة المصنعة</label>
                                    <input type="text" name="manufacturer" id="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer', $medication->manufacturer) }}">
                                    @error('manufacturer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الشكل الصيدلاني --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dosage_form" class="form-label">الشكل الصيدلاني</label>
                                    <select name="dosage_form" id="dosage_form" class="form-select select2 @error('dosage_form') is-invalid @enderror" data-placeholder="-- اختر الشكل --">
                                        <option value=""></option>
                                        @foreach($dosage_forms as $key => $value)
                                            <option value="{{ $key }}" {{ old('dosage_form', $medication->dosage_form) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('dosage_form') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- التركيز --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="strength" class="form-label">التركيز/القوة</label>
                                    <input type="text" name="strength" id="strength" class="form-control @error('strength') is-invalid @enderror" value="{{ old('strength', $medication->strength) }}">
                                    @error('strength') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- وحدة القياس --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit_of_measure" class="form-label">وحدة القياس الأساسية</label>
                                    <select name="unit_of_measure" id="unit_of_measure" class="form-select select2 @error('unit_of_measure') is-invalid @enderror" data-placeholder="-- اختر الوحدة --">
                                        <option value=""></option>
                                        @foreach($units_of_measure as $key => $value)
                                            <option value="{{ $key }}" {{ old('unit_of_measure', $medication->unit_of_measure) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_of_measure') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الباركود --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="barcode" class="form-label">الباركود (UPC/EAN)</label>
                                    <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $medication->barcode) }}">
                                    @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12"><hr class="my-3"></div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="minimum_stock_level" class="form-label">حد الطلب الأدنى للمخزون <span class="text-danger">*</span></label>
                                    <input type="number" name="minimum_stock_level" id="minimum_stock_level" class="form-control @error('minimum_stock_level') is-invalid @enderror" value="{{ old('minimum_stock_level', $medication->minimum_stock_level) }}" required min="0">
                                    @error('minimum_stock_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maximum_stock_level" class="form-label">الحد الأقصى للمخزون</label>
                                    <input type="number" name="maximum_stock_level" id="maximum_stock_level" class="form-control @error('maximum_stock_level') is-invalid @enderror" value="{{ old('maximum_stock_level', $medication->maximum_stock_level) }}" min="0">
                                    @error('maximum_stock_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purchase_price" class="form-label">سعر الشراء للوحدة</label>
                                    <input type="text" name="purchase_price" id="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price', $medication->purchase_price) }}" placeholder="0.00">
                                    @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selling_price" class="form-label">سعر البيع للوحدة</label>
                                    <input type="text" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', $medication->selling_price) }}" placeholder="0.00">
                                    @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12"><hr class="my-3"></div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-block">هل يتطلب وصفة طبية؟ <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="requires_prescription" id="requires_prescription_yes_edit" value="1" {{ old('requires_prescription', $medication->requires_prescription) == 1 ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="requires_prescription_yes_edit">نعم</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="requires_prescription" id="requires_prescription_no_edit" value="0" {{ old('requires_prescription', $medication->requires_prescription) == 0 ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="requires_prescription_no_edit">لا</label>
                                    </div>
                                    @error('requires_prescription') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-block">حالة الدواء <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_active_edit" value="1" {{ old('status', $medication->status) == 1 ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status_active_edit">نشط</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_inactive_edit" value="0" {{ old('status', $medication->status) == 0 ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status_inactive_edit">غير نشط</label>
                                    </div>
                                    @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description_edit" class="form-label">وصف الدواء</label>
                                    <textarea name="description" id="description_edit" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $medication->description) }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contraindications_edit" class="form-label">موانع الاستعمال</label>
                                    <textarea name="contraindications" id="contraindications_edit" class="form-control @error('contraindications') is-invalid @enderror" rows="3">{{ old('contraindications', $medication->contraindications) }}</textarea>
                                    @error('contraindications') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="side_effects_edit" class="form-label">الآثار الجانبية الشائعة</label>
                                    <textarea name="side_effects" id="side_effects_edit" class="form-control @error('side_effects') is-invalid @enderror" rows="3">{{ old('side_effects', $medication->side_effects) }}</textarea>
                                    @error('side_effects') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-delete-form" data-toggle="modal" data-target="#deleteMedicationModal{{ $medication->id }}">
                                    <i class="fas fa-trash-alt me-2"></i> حذف الدواء
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('pharmacy_manager.medications.index') }}" class="btn btn-cancel-form">
                                    <i class="fas fa-times me-2"></i> إلغاء
                                </a>
                                <button type="submit" class="btn btn-submit-form ms-2">
                                    <i class="fas fa-save me-2"></i> حفظ التعديلات
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال تأكيد حذف الدواء --}}
    @include('Dashboard.PharmacyManager.Medications.delete_confirm_modal', ['medication_for_modal' => $medication])

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
            $('#category, #dosage_form, #unit_of_measure').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                language: "ar"
            });

            // التحقق من صحة النموذج (Bootstrap 5)
            (function () {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        // استثناء فورم الحذف من التحقق هذا إذا كان منفصلاً وله ID
                         if (!form.id || !form.id.startsWith('confirmDeleteMedicationForm')) {
                            form.addEventListener('submit', function (event) {
                                if (!form.checkValidity()) {
                                    event.preventDefault()
                                    event.stopPropagation()
                                }
                                form.classList.add('was-validated')
                            }, false)
                        }
                    })
            })();

            // عرض رسائل NotifIt
            @if (session('success'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "bottom", autohide: true, timeout: 5000 });
            @endif
            @if (session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "bottom", autohide: true, timeout: 7000 });
            @endif
            @if ($errors->any())
                let errorMsgHtml = "<strong><i class='fas fa-times-circle me-2'></i> يرجى تصحيح الأخطاء التالية:</strong><ul class='mb-0 ps-3 mt-1' style='list-style-type: none; padding-right: 0;'>";
                @foreach ($errors->all() as $error)
                    errorMsgHtml += "<li>- {{ $error }}</li>";
                @endforeach
                errorMsgHtml += "</ul>";
                notif({ msg: errorMsgHtml, type: "error", position: "bottom", multiline: true, autohide: false });
            @endif

            // (اختياري) JavaScript لمودال الحذف (إذا أردت إضافة تأثيرات عند الإرسال)
            const confirmDeleteForms = document.querySelectorAll('form[id^="confirmDeleteMedicationForm"]');
            confirmDeleteForms.forEach(form => {
                 form.addEventListener('submit', function(e) {
                    const deleteBtn = form.querySelector('button[type="submit"]');
                    if (deleteBtn) {
                        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري الحذف...';
                        deleteBtn.disabled = true;
                    }
                });
            });
             document.querySelectorAll('.modal[id^="deleteMedicationModal"]').forEach(modalEl => {
                modalEl.addEventListener('hidden.bs.modal', function (event) {
                    const form = this.querySelector('form[id^="confirmDeleteMedicationForm"]');
                    if (form) {
                        const deleteBtn = form.querySelector('button[type="submit"]');
                        if (deleteBtn) {
                            deleteBtn.innerHTML = '<i class="fas fa-trash-alt me-2"></i> نعم، احذف';
                            deleteBtn.disabled = false;
                        }
                    }
                })
            });
        });
    </script>
@endsection
