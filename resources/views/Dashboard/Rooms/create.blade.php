@extends('Dashboard.layouts.master')
@section('title', 'إضافة غرفة جديدة')

@section('css')
    @parent {{-- استيراد CSS الأساسي من الـ layout --}}
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        /* استخدام نفس متغيرات التصميم لتوحيد المظهر */
        :root {
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981;
            --admin-success: #22c55e;
            --admin-danger: #ef4444;
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text: #111827;
            --admin-text-secondary: #6b7280;
            --admin-border-color: #e5e7eb;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.75rem;
            --admin-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.3s ease-in-out;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #1f2937; --admin-card-bg: #374151; --admin-text: #f9fafb;
                --admin-text-secondary: #9ca3af; --admin-border-color: #4b5563;
                --admin-primary: #6366f1; --admin-primary-dark: #4f46e5;
            }
            .form-control, .form-select, .select2-container--bootstrap-5 .select2-selection {
                background-color: #2d3748 !important;
                border-color: var(--admin-border-color) !important;
                color: var(--admin-text) !important;
            }
            .select2-container--bootstrap-5 .select2-dropdown {
                background-color: #2d3748;
                border-color: var(--admin-border-color);
            }
            .select2-container--bootstrap-5 .select2-results__option { color: var(--admin-text); }
            .select2-container--bootstrap-5 .select2-results__option--highlighted { background-color: var(--admin-primary) !important; }
            .form-label { color: var(--admin-text-secondary); }
        }

        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }

        .form-container-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border-color);
            transition: var(--admin-transition);
        }
        .form-container-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
        .card-header-custom {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
            border-radius: var(--admin-radius-lg) var(--admin-radius-lg) 0 0;
        }
        .card-header-custom .card-title { font-weight: 600; font-size: 1.2rem; }
        .card-header-custom i { margin-left: 0.5rem; } /* RTL: margin-right */

        .form-label { font-weight: 500; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--admin-text-secondary); }
        .form-control, .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            transition: var(--admin-transition);
            background-color: var(--admin-card-bg); /* للخلفية في الوضع الفاتح */
            color: var(--admin-text); /* لون النص في الوضع الفاتح */
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }

        .select2-container--bootstrap-5 .select2-selection {
            border-radius: var(--admin-radius-md) !important;
            border: 1px solid var(--admin-border-color) !important;
            padding: 0.47rem 0.75rem !important;
            height: calc(1.5em + 1.3rem + 2px) !important; /* ليتناسب مع ارتفاع الحقول الأخرى */
            background-color: var(--admin-card-bg);
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: var(--admin-text) !important;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--admin-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15) !important;
        }

        .btn-submit-form, .btn-cancel-form {
            padding: 0.75rem 1.5rem;
            border-radius: var(--admin-radius-md);
            font-weight: 600;
            transition: var(--admin-transition);
            border: none;
            font-size: 0.95rem;
        }
        .btn-submit-form { background-color: var(--admin-primary); color: white; }
        .btn-submit-form:hover { background-color: var(--admin-primary-dark); transform: translateY(-2px); box-shadow: var(--admin-shadow); }
        .btn-cancel-form { background-color: #6c757d; color: white; } /* رمادي داكن للإلغاء */
        .btn-cancel-form:hover { background-color: #5a6268; transform: translateY(-2px); box-shadow: var(--admin-shadow); }

        .is-invalid { border-color: var(--admin-danger) !important; }
        .invalid-feedback { color: var(--admin-danger); font-size: 0.85em; display: block; margin-top: 0.25rem;}
        .was-validated .form-control:valid, .was-validated .form-select:valid { border-color: var(--admin-success) !important; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-door-plus fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة الغرف</h4>
                    <span class="text-muted mt-0 tx-13">/ إضافة غرفة جديدة</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة لقائمة الغرف
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center animate__animated animate__fadeInUp">
        <div class="col-lg-10 col-md-12">
            <div class="form-container-card">
                <div class="card-header card-header-custom">
                    <h3 class="card-title mb-0"><i class="fas fa-plus-circle"></i> إضافة غرفة جديدة</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.rooms.store') }}" method="POST" class="needs-validation" novalidate autocomplete="off">
                        @csrf
                        <div class="row g-3">
                            {{-- القسم --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section_id" class="form-label">القسم <span class="text-danger">*</span></label>
                                    <select name="section_id" id="section_id" class="form-select select2 @error('section_id') is-invalid @enderror" required data-placeholder="-- اختر القسم --">
                                        <option value=""></option> {{-- للخيار الافتراضي لـ placeholder --}}
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }} {{-- هنا نفترض أن name هو عمود عادي في sections --}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- رقم/اسم الغرفة --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_number" class="form-label">رقم/اسم الغرفة <span class="text-danger">*</span></label>
                                    <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror" value="{{ old('room_number') }}" placeholder="مثال: 101 أو غرفة الفحص أ" required>
                                    @error('room_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- نوع الغرفة --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type" class="form-label">نوع الغرفة <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select select2 @error('type') is-invalid @enderror" required data-placeholder="-- اختر النوع --">
                                        <option value=""></option>
                                        @foreach($roomTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- تخصيص الجنس --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_type" class="form-label">تخصيص الجنس <span class="text-danger">*</span></label>
                                    <select name="gender_type" id="gender_type" class="form-select select2 @error('gender_type') is-invalid @enderror" required data-placeholder="-- اختر --">
                                        <option value=""></option>
                                        @foreach($genderTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('gender_type', 'any') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('gender_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الحالة الأولية --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status" class="form-label">الحالة الأولية <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select select2 @error('status') is-invalid @enderror" required data-placeholder="-- اختر الحالة --">
                                        <option value=""></option>
                                        @foreach($initialRoomStatuses as $key => $value)
                                            <option value="{{ $key }}" {{ old('status', 'available') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- الطابق --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="floor" class="form-label">الطابق</label>
                                    <input type="text" name="floor" id="floor" class="form-control @error('floor') is-invalid @enderror" value="{{ old('floor') }}" placeholder="مثال: الأول، G, 3">
                                    @error('floor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- ملاحظات --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="أي معلومات إضافية عن الغرفة...">{{ old('notes') }}</textarea>
                                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-center">
                            <button type="submit" class="btn btn-submit-form">
                                <i class="fas fa-save me-2"></i> حفظ الغرفة
                            </button>
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-cancel-form ms-2">
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
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true
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
