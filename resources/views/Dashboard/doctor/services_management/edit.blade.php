@extends('Dashboard.layouts.master')

@section('title', 'تعديل بيانات الخدمة الطبية')

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        /* --- Root Variables & Basic Styling (نفس الأنماط من create.blade.php) --- */
        :root {
            --bs-primary-rgb: 59, 130, 246;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-primary-darker: rgb(37, 99, 235);
            --bs-success-rgb: 16, 185, 129;
            --bs-danger-rgb: 239, 68, 68;
            --bs-info-rgb: 59, 130, 246;
            --bs-gray-100: #f8f9fa;
            --bs-gray-200: #e9ecef;
            --bs-gray-700: #4a5568;
            --bs-gray-800: #2d3748;
            --bs-body-bg: #f7fafc;
            --bs-border-color: var(--bs-gray-200);
            --bs-card-border-radius: 0.75rem;
            --bs-card-box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --bs-card-cap-bg: #fff;
            --bs-card-cap-padding-y: 1.25rem;
            --bs-card-cap-padding-x: 1.5rem;
        }
        body { font-family: 'Cairo', 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: var(--bs-gray-700); }
        .card {
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            border: 1px solid var(--bs-border-color);
            margin-bottom: 2rem;
        }
        .card-header.card-header-form {
            background: var(--bs-gray-100);
            border-bottom: 1px solid var(--bs-border-color);
            padding: var(--bs-card-cap-padding-y) var(--bs-card-cap-padding-x);
        }
        .card-title-enhanced { font-weight: 700; font-size:1.2rem; margin-bottom: 0; display: flex; align-items: center; color: var(--bs-gray-800);}
        .card-title-enhanced i { font-size: 1.1em; margin-right: 0.65rem; color: var(--bs-primary); }

        .form-group.floating-label-group { position: relative; margin-bottom: 2rem; }
        .form-group.floating-label-group .form-control.floating-input,
        .form-group.floating-label-group .form-control.floating-textarea {
            height: auto;
            padding: 1.25rem .9rem .6rem;
            line-height: 1.5;
            border-radius: .45rem;
            border: 1px solid var(--bs-border-color);
            background-clip: padding-box;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .form-group.floating-label-group .form-control.floating-textarea { min-height: 120px; }
        .form-group.floating-label-group .floating-label {
            position: absolute; top: 0; left: 0; right: auto; height: 100%;
            padding: .9rem .9rem; pointer-events: none; border: 1px solid transparent;
            transform-origin: 0 0; transition: opacity .15s ease-in-out,transform .15s ease-in-out;
            color: #858796; font-size: 0.95rem; opacity: .75; display: flex; align-items: center;
        }
        .form-group.floating-label-group .floating-input:not(:placeholder-shown) ~ .floating-label,
        .form-group.floating-label-group .floating-textarea:not(:placeholder-shown) ~ .floating-label,
        .form-group.floating-label-group .floating-input:focus ~ .floating-label,
        .form-group.floating-label-group .floating-textarea:focus ~ .floating-label {
            opacity: .75; transform: scale(.80) translateY(-.8rem) translateX(.2rem);
            background-color: #fff; padding: 0 .3rem; height: auto; font-weight: 500;
        }
         .form-group.floating-label-group .floating-input:focus,
         .form-group.floating-label-group .floating-textarea:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb),.25);
            background-color: #fff;
        }
        .form-group.floating-label-group .floating-input.is-invalid ~ .floating-label,
        .form-group.floating-label-group .floating-textarea.is-invalid ~ .floating-label { color: var(--bs-danger); }
        .form-group.floating-label-group .floating-input.is-invalid,
        .form-group.floating-label-group .floating-textarea.is-invalid { border-color: var(--bs-danger); }
        .form-group.floating-label-group .floating-input.is-invalid:focus,
        .form-group.floating-label-group .floating-textarea.is-invalid:focus { box-shadow: 0 0 0 0.25rem rgba(var(--bs-danger-rgb),.25); }

        .form-group.floating-label-group .input-group .floating-input { border-top-left-radius: 0; border-bottom-left-radius: 0; }
        .form-group.floating-label-group .input-group .floating-label { left: auto; right: 45px; }

        .input-group-text.icon-prepend {
            background-color: var(--bs-gray-100); border: 1px solid var(--bs-border-color);
            border-right: 0; border-top-left-radius: .45rem; border-bottom-left-radius: .45rem;
            padding: 0 .9rem; color: var(--bs-primary);
        }
        .form-switch.form-switch-lg { padding-left: 3em; }
        .form-switch.form-switch-lg .form-check-input {
            width: 2.5em; height: 2em; margin-right: 6em; margin-top: -0em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba(0,0,0,0.25)'/%3e%3c/svg%3e");
        }
        .form-switch.form-switch-lg .form-check-input:checked {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
            background-color: var(--bs-success); border-color: var(--bs-success);
        }
        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-darker) 100%);
            border: none; color: white; font-weight: 600; padding: 0.65rem 1.5rem;
            border-radius: 50px; transition: all 0.3s ease;
        }
        .btn-primary-gradient:hover {
             background: linear-gradient(135deg, var(--bs-primary-darker) 0%, var(--bs-primary) 100%);
             box-shadow: 0 4px 15px rgba(var(--bs-primary-rgb), 0.3);
             transform: translateY(-2px);
        }
        .btn-outline-secondary.ripple { border-radius: 50px; padding: 0.65rem 1.5rem; font-weight: 500; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between py-3">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                 <h4 class="content-title mb-0 my-auto">
                    <i class="fas fa-edit fa-lg text-primary me-2"></i>
                    <span class="fw-bold">تعديل بيانات الخدمة</span>
                </h4>
            </div>
             <p class="text-muted mt-1 mb-0 tx-13">تحديث تفاصيل الخدمة: {{ $service->name }}</p>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <a href="{{ route('doctor.services_management.index') }}" class="btn btn-outline-secondary btn-sm ripple" data-aos="fade-left" data-aos-delay="100">
                <i class="fas fa-arrow-left me-1"></i> العودة إلى قائمة الخدمات
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="card" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header card-header-form">
                    <h4 class="card-title-enhanced"><i class="fas fa-pencil-alt"></i>تعديل خدمة: {{ $service->name }}</h4>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('doctor.services_management.update', $service->id) }}" method="post" class="needs-validation" novalidate autocomplete="off">
                        @csrf
                        @method('PUT') {{-- أو PATCH --}}

                        <!-- Service Name Field -->
                        <div class="form-group floating-label-group" data-aos="fade-up" data-aos-delay="300">
                            {{-- إذا كنت لا تستخدم الترجمة، يمكنك استخدام $service->name مباشرة --}}
                            {{-- إذا كنت تستخدم الترجمة، $service->getTranslation('name', app()->getLocale()) --}}
                            <input type="text" name="name" id="service_name" class="form-control floating-input @error('name') is-invalid @enderror"
                                   value="{{ old('name', $service->name) }}" placeholder=" " required>
                            <label for="service_name" class="floating-label">
                                <i class="fas fa-tag fa-xs me-2"></i>{{ trans('Services.name') }} <span class="text-danger">*</span>
                            </label>
                            @error('name')
                                <div class="invalid-feedback mt-1 ps-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price Field -->
                        <div class="form-group floating-label-group" data-aos="fade-up" data-aos-delay="400">
                             <div class="input-group">
                                <span class="input-group-text icon-prepend"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" name="price" id="service_price" class="form-control floating-input @error('price') is-invalid @enderror"
                                   value="{{ old('price', $service->price) }}" placeholder=" " min="0" step="0.01" required>
                                <label for="service_price" class="floating-label">
                                    {{ trans('Services.price') }} <span class="text-danger">*</span>
                                </label>
                            </div>
                            @error('price')
                                <div class="invalid-feedback mt-1 ps-1 d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="form-group floating-label-group" data-aos="fade-up" data-aos-delay="500">
                            {{-- إذا كنت لا تستخدم الترجمة، يمكنك استخدام $service->description مباشرة --}}
                            {{-- إذا كنت تستخدم الترجمة، $service->getTranslation('description', app()->getLocale()) --}}
                            <textarea class="form-control floating-textarea @error('description') is-invalid @enderror" name="description" id="service_description"
                                      rows="4" placeholder=" ">{{ old('description', $service->description) }}</textarea>
                            <label for="service_description" class="floating-label">
                                <i class="fas fa-file-alt fa-xs me-2"></i>{{ trans('Services.description') }} (اختياري)
                            </label>
                            @error('description')
                                <div class="invalid-feedback mt-1 ps-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Switch -->
                        <div class="form-group" data-aos="fade-up" data-aos-delay="600">
                            <label class="form-label fw-bold  d-block ">حالة الخدمة <span class="text-danger">*</span></label>
                            <div class="form-check form-switch form-switch-lg">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="form-check-input @error('status') is-invalid @enderror" id="service_status_edit"
                                       name="status" value="1" role="switch" {{ old('status', $service->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="service_status_edit" id="service_status_label_edit">
                                    {{ old('status', $service->status) ? trans('doctors.Enabled') : trans('doctors.Not_enabled') }}
                                </label>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 my-lg-5">

                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-end" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{ route('doctor.services_management.index') }}" class="btn btn-outline-secondary ripple px-4">
                                <i class="fas fa-times me-2"></i>إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary-gradient ripple px-5">
                                <i class="fas fa-save me-2"></i> حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 700,
                once: true,
                offset: 50
            });

            // Bootstrap 5 form validation
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

            // Update status label text and color on change for edit form
            const statusCheckboxEdit = document.getElementById('service_status_edit');
            const statusLabelEdit = document.getElementById('service_status_label_edit');

            function updateStatusLabelEdit() {
                if (statusCheckboxEdit.checked) {
                    statusLabelEdit.textContent = "{{ trans('doctors.Enabled') }}";
                    statusLabelEdit.classList.remove('text-danger-emphasis');
                    statusLabelEdit.classList.add('text-success-emphasis');
                } else {
                    statusLabelEdit.textContent = "{{ trans('doctors.Not_enabled') }}";
                    statusLabelEdit.classList.remove('text-success-emphasis');
                    statusLabelEdit.classList.add('text-danger-emphasis');
                }
            }
            if (statusCheckboxEdit) {
                statusCheckboxEdit.addEventListener('change', updateStatusLabelEdit);
                updateStatusLabelEdit(); // Initial state
            }

            // NotifIt for session messages
            @if (session('success'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>", type: "success", position: "top-center", autohide: true, timeout: 5000});
            @endif
            @if (session('error'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>",type: "error",position: "top-center",autohide: true,timeout: 7000});
            @endif
        });
    </script>
@endsection
