@extends('Dashboard.layouts.master')

@section('title')
    تعديل دفعة مخزون لـ: {{ $medication->name }} (دفعة: {{ $stock->batch_number ?: 'N/A' }})
@endsection

@section('css')
    @parent
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* ==========================================================================
           Global Variables & Base Styles for Admin Dashboard (Full)
           ========================================================================== */
        :root {
            --admin-primary: #4f46e5; /* بنفسجي جذاب (Indigo-600) */
            --admin-primary-dark: #4338ca; /* أغمق قليلاً للـ hover */
            --admin-primary-light: #c7d2fe;
            --admin-secondary: #10b981; /* أخضر زمردي (Emerald-600) */
            --admin-success: #22c55e; /* Green-500 */
            --admin-danger: #ef4444;  /* Red-500 */
            --admin-danger-dark: #c82333; /* أغمق للخطر */
            --admin-warning: #f59e0b; /* Amber-500 */
            --admin-info: #3b82f6;   /* Blue-500 */
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text: #1f2937; /* Gray-800 */
            --admin-text-secondary: #6b7280; /* Gray-500 */
            --admin-border-color: #e5e7eb; /* Gray-200 */
            --admin-input-border: #d1d5db; /* Gray-300 */
            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.5rem;
            --admin-radius-xl: 0.75rem;
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px -1px rgba(0, 0, 0, 0.07);
            --admin-shadow-md: 0 4px 8px -1px rgba(0, 0, 0, 0.07), 0 2px 6px -2px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --bs-primary-rgb: 79, 70, 229;
            --admin-success-rgb: 34,197,94;
            --admin-danger-rgb: 239,68,68;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827; --admin-card-bg: #1f2937; --admin-text: #e5e7eb;
                --admin-text-secondary: #9ca3af; --admin-border-color: #374151;
                --admin-input-border: #4b5563; --admin-primary: #6366f1;
                --admin-primary-dark: #4f46e5; --admin-secondary: #34d399;
            }
            .form-control, .form-select, .flatpickr-input, .select2-container--bootstrap-5 .select2-selection {
                background-color: #2d3748 !important;
                border-color: var(--admin-input-border) !important;
                color: var(--admin-text) !important;
            }
            .select2-container--bootstrap-5 .select2-dropdown { background-color: #2d3748; border-color: var(--admin-input-border); }
            .select2-container--bootstrap-5 .select2-results__option { color: var(--admin-text); }
            .select2-container--bootstrap-5 .select2-results__option--highlighted { background-color: var(--admin-primary) !important; }
            .card { border-color: var(--admin-border-color); background-color: var(--admin-card-bg); }
        }
        body.dark { /* ... (تطبيق متغيرات الوضع الداكن) ... */ }


        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); line-height: 1.6; }

        .form-container-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-xl);
            box-shadow: var(--admin-shadow-md);
            border: 1px solid var(--admin-border-color);
            margin-bottom: 2rem;
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        .card-header-custom {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white; padding: 1.25rem 1.5rem;
            border-bottom: none; border-radius: var(--admin-radius-xl) var(--admin-radius-xl) 0 0;
        }
        .card-header-custom .card-title { font-weight: 600; font-size: 1.2rem; margin-bottom:0; }
        .card-header-custom .card-title i { margin-left: 0.75rem; opacity: 0.9;}

        .card-body-custom { padding: 2rem; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 500; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--admin-text-secondary); }
        .form-control, .form-select, .flatpickr-input {
            display: block; width: 100%; padding: 0.75rem 1rem;
            font-size: 0.95rem; line-height: 1.6;
            color: var(--admin-text); background-color: var(--admin-card-bg);
            background-clip: padding-box; border: 1px solid var(--admin-input-border);
            appearance: none; border-radius: var(--admin-radius-md);
            box-shadow: var(--admin-shadow-xs);
            transition: border-color var(--admin-transition), box-shadow var(--admin-transition);
        }
        .form-control:focus, .form-select:focus, .flatpickr-input:focus {
            border-color: var(--admin-primary); outline: 0;
            box-shadow: var(--admin-shadow-xs), 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }
        .flatpickr-input[readonly] { background-color: var(--admin-card-bg) !important; }

        .select2-container--bootstrap-5 .select2-selection { /* ... (أنماط Select2 كما هي) ... */ }

        .form-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border-color); }
        .btn-submit-form, .btn-cancel-form, .btn-delete-form {
            padding: 0.75rem 1.5rem; border-radius: var(--admin-radius-md); font-weight: 600;
            transition: var(--admin-transition); border: none; font-size: 0.95rem;
            display: inline-flex; align-items: center; justify-content: center; text-decoration: none;
        }
        .btn-submit-form { background-color: var(--admin-primary); color: white; }
        .btn-submit-form:hover { background-color: var(--admin-primary-dark); transform: translateY(-2px); box-shadow: var(--admin-shadow-md); }
        .btn-cancel-form { background-color: #6c757d; color: white; }
        .btn-cancel-form:hover { background-color: #5a6268; transform: translateY(-2px); box-shadow: var(--admin-shadow-md); }
        .btn-delete-form { background-color: var(--admin-danger); color: white; }
        .btn-delete-form:hover { background-color: var(--admin-danger-dark, #c82333); transform: translateY(-2px); box-shadow: var(--admin-shadow-md); }
        .btn-submit-form i, .btn-cancel-form i, .btn-delete-form i { margin-left: 0.5rem; }

        .is-invalid { border-color: var(--admin-danger) !important; }
        .invalid-feedback { color: var(--admin-danger); font-size: 0.875em; display: block; margin-top: 0.25rem;}
        .was-validated .form-control:valid, .was-validated .form-select:valid { border-color: var(--admin-success) !important; }

        /* Modal Styles */
        .modal-header.bg-gradient-danger { background: linear-gradient(135deg, var(--admin-danger), var(--admin-danger-dark)); border-bottom: none; }
        .modal-title { font-weight: 600; }
        .modal-body .fa-archive { font-size: 3rem; color: var(--admin-danger); margin-bottom: 1rem; } /* أيقونة مختلفة للمخزون */
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
                    <h4 class="content-title mb-0 my-auto">إدارة مخزون الصيدلية</h4>
                    <span class="text-muted mt-0 tx-13">/ تعديل دفعة للدواء: <strong>{{ $medication->name }}</strong></span>
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
                    <h3 class="card-title mb-0" style="color: white"><i class="fas fa-edit"></i> تعديل دفعة المخزون لـ "{{ $medication->name }}" (دفعة: {{ $stock->batch_number ?: 'غير محدد' }})</h3>
                </div>
                <div class="card-body card-body-custom">
                    <form action="{{ route('pharmacy_manager.stocks.update', $stock->id) }}" method="POST" class="needs-validation" novalidate autocomplete="off">
                        @method('PUT')
                        @csrf
                        <div class="alert alert-info bg-light border-info text-info-emphasis mb-4">
                            <i class="fas fa-pills me-2"></i>
                            الدواء: <strong>{{ $medication->name }}</strong> ({{ $medication->generic_name }})
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_number_edit" class="form-label">رقم الدفعة</label>
                                    <input type="text" name="batch_number" id="batch_number_edit" class="form-control @error('batch_number') is-invalid @enderror" value="{{ old('batch_number', $stock->batch_number) }}">
                                    @error('batch_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expiry_date_edit" class="form-label">تاريخ انتهاء الصلاحية <span class="text-danger">*</span></label>
                                    <input type="text" name="expiry_date" id="expiry_date_edit" class="form-control flatpickr-date @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date', $stock->expiry_date->format('Y-m-d')) }}" required>
                                    @error('expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="initial_quantity_edit" class="form-label">الكمية الأولية <span class="text-danger">*</span></label>
                                    <input type="number" name="initial_quantity" id="initial_quantity_edit" class="form-control @error('initial_quantity') is-invalid @enderror" value="{{ old('initial_quantity', $stock->initial_quantity) }}" required min="0">
                                    @error('initial_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity_on_hand_edit" class="form-label">الكمية الحالية <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity_on_hand" id="quantity_on_hand_edit" class="form-control @error('quantity_on_hand') is-invalid @enderror" value="{{ old('quantity_on_hand', $stock->quantity_on_hand) }}" required min="0">
                                    @error('quantity_on_hand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost_price_per_unit_edit" class="form-label">سعر تكلفة الوحدة</label>
                                    <input type="text" name="cost_price_per_unit" id="cost_price_per_unit_edit" class="form-control @error('cost_price_per_unit') is-invalid @enderror" value="{{ old('cost_price_per_unit', $stock->cost_price_per_unit) }}" placeholder="0.00">
                                    @error('cost_price_per_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_name_edit" class="form-label">اسم المورد</label>
                                    <input type="text" name="supplier_name" id="supplier_name_edit" class="form-control @error('supplier_name') is-invalid @enderror" value="{{ old('supplier_name', $stock->supplier_name) }}">
                                    @error('supplier_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="received_date_edit" class="form-label">تاريخ استلام الدفعة</label>
                                    <input type="text" name="received_date" id="received_date_edit" class="form-control flatpickr-date @error('received_date') is-invalid @enderror" value="{{ old('received_date', $stock->received_date ? $stock->received_date->format('Y-m-d') : '') }}">
                                    @error('received_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="stock_notes_edit" class="form-label">ملاحظات على الدفعة</label>
                                    <textarea name="stock_notes" id="stock_notes_edit" class="form-control @error('stock_notes') is-invalid @enderror" rows="3">{{ old('stock_notes', $stock->stock_notes) }}</textarea>
                                    @error('stock_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div>
                                <button type="button" class="btn btn-delete-form" data-toggle="modal" data-target="#deleteStockModal{{ $stock->id }}">
                                    <i class="fas fa-trash-alt me-2"></i> حذف الدفعة
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('pharmacy_manager.medications.stocks.index', $medication->id) }}" class="btn btn-cancel-form">
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

    {{-- مودال تأكيد حذف دفعة المخزون --}}
    @include('Dashboard.PharmacyManager.Stocks.delete_confirm_modal', ['stock_for_modal' => $stock])

@endsection

@section('js')
    @parent
    {{-- <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script> --}} {{-- لا يوجد select2 هنا --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script> --}}
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <script>
        $(document).ready(function() {
            const flatpickrConfigEdit = {
                dateFormat: "Y-m-d",
                locale: "ar",
                allowInput: true,
                disableMobile: true
            };
            flatpickr("#expiry_date_edit", { ...flatpickrConfigEdit, minDate: "today" });
            flatpickr("#received_date_edit", { ...flatpickrConfigEdit, maxDate: "today" });

            (function () {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                         if (!form.id || !form.id.startsWith('confirmDeleteStockForm')) {
                            form.addEventListener('submit', function (event) {
                                const initialQty = parseInt($('#initial_quantity_edit').val());
                                const currentQty = parseInt($('#quantity_on_hand_edit').val());
                                if (!isNaN(initialQty) && !isNaN(currentQty) && currentQty > initialQty) {
                                     $('#quantity_on_hand_edit').addClass('is-invalid');
                                     $('#quantity_on_hand_edit').closest('.form-group').find('.invalid-feedback').text('الكمية الحالية لا يمكن أن تكون أكبر من الكمية الأولية.').show();
                                    event.preventDefault();
                                    event.stopPropagation();
                                }
                                if (!form.checkValidity()) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }
                                form.classList.add('was-validated');
                            }, false);
                        }
                    })
            })();

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

            // JS لمودال الحذف (تأثير التحميل)
            const confirmDeleteFormsStock = document.querySelectorAll('form[id^="confirmDeleteStockForm"]');
            confirmDeleteFormsStock.forEach(form => {
                 form.addEventListener('submit', function(e) {
                    const deleteBtn = form.querySelector('button[type="submit"]');
                    if (deleteBtn) {
                        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري الحذف...';
                        deleteBtn.disabled = true;
                    }
                });
            });
             document.querySelectorAll('.modal[id^="deleteStockModal"]').forEach(modalEl => {
                modalEl.addEventListener('hidden.bs.modal', function (event) {
                    const form = this.querySelector('form[id^="confirmDeleteStockForm"]');
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
