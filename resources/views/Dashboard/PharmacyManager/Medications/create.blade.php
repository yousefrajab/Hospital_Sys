@extends('Dashboard.layouts.master')
@section('title', 'إضافة دواء جديد')

@section('css')
    @parent
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
    <style>
        /* --- نفس متغيرات CSS وأنماط البطاقات والفورم التي استخدمناها سابقًا --- */
        :root { /* ... (نفس متغيرات :root من ملف index.blade.php للأدوية) ... */
            --admin-primary: #4f46e5; --admin-primary-dark: #4338ca; --admin-secondary: #10b981;
            --admin-success: #22c55e; --admin-danger: #ef4444; --admin-warning: #f59e0b;
            --admin-info: #3b82f6; --admin-bg: #f8f9fc; --admin-card-bg: #ffffff;
            --admin-text: #1f2937; --admin-text-secondary: #6b7280; --admin-border-color: #e5e7eb;
            --admin-input-border: #d1d5db; --admin-radius-md: 0.375rem; --admin-radius-lg: 0.5rem;
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px -1px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --bs-primary-rgb: 79, 70, 229;
        }
        @media (prefers-color-scheme: dark) { /* ... (أنماط الوضع الداكن) ... */ }
        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }
        .card { background-color: var(--admin-card-bg); border-radius: var(--admin-radius-lg); box-shadow: var(--admin-shadow); border: 1px solid var(--admin-border-color); margin-bottom: 1.5rem; }
        .card-header { background-color: transparent; border-bottom: 1px solid var(--admin-border-color); padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center;}
        .card-header .card-title { font-weight: 600; color: var(--admin-text); margin-bottom: 0; font-size: 1.1rem; }
        .card-header .card-title i { margin-left: 0.5rem; color: var(--admin-primary); }
        .form-label { font-weight: 500; margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--admin-text-secondary); }
        .form-control, .form-select {
            border-radius: var(--admin-radius-md); border: 1px solid var(--admin-input-border);
            padding: 0.65rem 1rem; font-size: 0.9rem; transition: var(--admin-transition);
            background-color: var(--admin-card-bg); color: var(--admin-text);
        }
        .form-control:focus, .form-select:focus { border-color: var(--admin-primary); box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.15); outline:0; }
        .select2-container--bootstrap-5 .select2-selection { /* ... (أنماط Select2 كما هي) ... */ }
        .btn-submit-form, .btn-cancel-form { padding: 0.75rem 1.5rem; border-radius: var(--admin-radius-md); font-weight: 600; transition: var(--admin-transition); border: none; font-size: 0.95rem; }
        .btn-submit-form { background-color: var(--admin-primary); color: white; }
        .btn-submit-form:hover { background-color: var(--admin-primary-dark); transform: translateY(-2px); }
        .btn-cancel-form { background-color: #6c757d; color: white; }
        .btn-cancel-form:hover { background-color: #5a6268; transform: translateY(-2px); }
        .is-invalid { border-color: var(--admin-danger) !important; }
        .invalid-feedback { color: var(--admin-danger); font-size: 0.8em; display: block; margin-top: 0.2rem;}
        .was-validated .form-control:valid, .was-validated .form-select:valid { border-color: var(--admin-success) !important; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-pills fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة الأدوية</h4>
                    <span class="text-muted mt-0 tx-13">/ إضافة دواء جديد</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('pharmacy_manager.medications.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة لقائمة الأدوية
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card animate__animated animate__fadeInUp">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-plus-circle me-2"></i> بيانات الدواء الجديد</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pharmacy_manager.medications.store') }}" method="POST" class="needs-validation" novalidate autocomplete="off">
                        @csrf
                        <div class="row g-3">
                            {{-- اسم الدواء --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">اسم الدواء (التجاري/الأساسي) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="مثال: Panadol Extra">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الاسم العلمي --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="generic_name" class="form-label">الاسم العلمي (Generic)</label>
                                    <input type="text" name="generic_name" id="generic_name" class="form-control @error('generic_name') is-invalid @enderror" value="{{ old('generic_name') }}" placeholder="مثال: Paracetamol, Caffeine">
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
                                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الشركة المصنعة --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="manufacturer" class="form-label">الشركة المصنعة</label>
                                    <input type="text" name="manufacturer" id="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer') }}" placeholder="اسم الشركة">
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
                                            <option value="{{ $key }}" {{ old('dosage_form') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('dosage_form') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- التركيز --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="strength" class="form-label">التركيز/القوة</label>
                                    <input type="text" name="strength" id="strength" class="form-control @error('strength') is-invalid @enderror" value="{{ old('strength') }}" placeholder="مثال: 500mg, 10%">
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
                                            <option value="{{ $key }}" {{ old('unit_of_measure') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_of_measure') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الباركود --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="barcode" class="form-label">الباركود (UPC/EAN)</label>
                                    <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}">
                                    @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12"><hr class="my-3"></div>

                            {{-- حد الطلب الأدنى --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="minimum_stock_level" class="form-label">حد الطلب الأدنى للمخزون <span class="text-danger">*</span></label>
                                    <input type="number" name="minimum_stock_level" id="minimum_stock_level" class="form-control @error('minimum_stock_level') is-invalid @enderror" value="{{ old('minimum_stock_level', 10) }}" required min="0">
                                    @error('minimum_stock_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الحد الأقصى للمخزون --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maximum_stock_level" class="form-label">الحد الأقصى للمخزون (اختياري)</label>
                                    <input type="number" name="maximum_stock_level" id="maximum_stock_level" class="form-control @error('maximum_stock_level') is-invalid @enderror" value="{{ old('maximum_stock_level') }}" min="0">
                                    @error('maximum_stock_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- سعر الشراء --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purchase_price" class="form-label">سعر الشراء للوحدة (اختياري)</label>
                                    <input type="text" name="purchase_price" id="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price') }}" placeholder="0.00">
                                    @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- سعر البيع --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selling_price" class="form-label">سعر البيع للوحدة (اختياري)</label>
                                    <input type="text" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price') }}" placeholder="0.00">
                                    @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12"><hr class="my-3"></div>

                            {{-- يتطلب وصفة --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-block">هل يتطلب وصفة طبية؟ <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="requires_prescription" id="requires_prescription_yes" value="1" {{ old('requires_prescription', '1') == '1' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="requires_prescription_yes">نعم</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="requires_prescription" id="requires_prescription_no" value="0" {{ old('requires_prescription') == '0' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="requires_prescription_no">لا</label>
                                    </div>
                                    @error('requires_prescription') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الحالة --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label d-block">حالة الدواء <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status_active">نشط</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ old('status') == '0' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status_inactive">غير نشط</label>
                                    </div>
                                    @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الوصف --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">وصف الدواء</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- موانع الاستعمال --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contraindications" class="form-label">موانع الاستعمال</label>
                                    <textarea name="contraindications" id="contraindications" class="form-control @error('contraindications') is-invalid @enderror" rows="3">{{ old('contraindications') }}</textarea>
                                    @error('contraindications') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- الآثار الجانبية --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="side_effects" class="form-label">الآثار الجانبية الشائعة</label>
                                    <textarea name="side_effects" id="side_effects" class="form-control @error('side_effects') is-invalid @enderror" rows="3">{{ old('side_effects') }}</textarea>
                                    @error('side_effects') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-submit-form">
                                <i class="fas fa-save me-2"></i> حفظ الدواء
                            </button>
                            <a href="{{ route('pharmacy_manager.medications.index') }}" class="btn btn-cancel-form ms-2">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </a>
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
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
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
