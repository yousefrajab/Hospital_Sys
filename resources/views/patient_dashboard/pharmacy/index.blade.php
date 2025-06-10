@extends('Dashboard.layouts.master') {{-- استخدام نفس الـ layout الرئيسي --}}
@section('title', 'قائمة وصفاتي الطبية')

@section('css')
    @parent
    <link href="{{ URL::asset('dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-primary-dark: #3a58d8;
            --bs-secondary-rgb: 108, 117, 125;
            --bs-secondary: rgb(var(--bs-secondary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-info-rgb: 13, 202, 240;
            --bs-info: rgb(var(--bs-info-rgb));
            --bs-warning-rgb: 255, 193, 7;
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-danger-rgb: 220, 53, 69;
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-light-rgb: 248, 249, 250;
            --bs-light: rgb(var(--bs-light-rgb));
            --bs-dark-rgb: 33, 37, 41;
            --bs-dark: rgb(var(--bs-dark-rgb));
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.5rem;
            --bs-card-box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.075);
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

        .status-badge {
            padding: 0.4em 0.8em; /* تعديل الحشو ليكون أوضح قليلاً */
            border-radius: 50px;
            font-size: 0.8rem; /* تعديل حجم الخط */
            font-weight: 500;
            letter-spacing: 0.3px;
            min-width: 110px; /* لضمان عرض كافٍ للنصوص الأطول */
            text-align: center;
            display: inline-block;
        }

        /* تحسين ألوان الحالات لتكون أكثر وضوحًا وتوافقًا */
        .status-new { background-color: rgba(var(--bs-info-rgb), 0.15); color: rgb(var(--bs-info-rgb)); border: 1px solid rgba(var(--bs-info-rgb), 0.3); }
        .status-pending_review { background-color: rgba(var(--bs-warning-rgb), 0.2); color: #a17d06; border: 1px solid rgba(var(--bs-warning-rgb), 0.4); }
        .status-approved { background-color: rgba(var(--bs-primary-rgb), 0.15); color: var(--bs-primary); border: 1px solid rgba(var(--bs-primary-rgb), 0.3); }
        .status-ready_for_pickup { background-color: #e2f0d9; color: #548235; border: 1px solid #c5e0b4;} /* أخضر فاتح مميز لجاهزة للاستلام */
        .status-processing { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5;} /* أصفر فاتح للمعالجة */
        .status-dispensed { background-color: rgba(var(--bs-success-rgb), 0.15); color: rgb(var(--bs-success-rgb)); border: 1px solid rgba(var(--bs-success-rgb), 0.3); }
        .status-partially_dispensed { background-color: rgba(255, 165, 0, 0.15); color: #cc8400; border: 1px solid rgba(255, 165, 0, 0.3); } /* برتقالي فاتح */
        .status-cancelled_by_doctor,
        .status-cancelled_by_pharmacist,
        .status-cancelled_by_patient { background-color: rgba(var(--bs-danger-rgb), 0.1); color: rgb(var(--bs-danger-rgb)); border: 1px solid rgba(var(--bs-danger-rgb), 0.2); }
        .status-on_hold { background-color: rgba(var(--bs-secondary-rgb), 0.15); color: rgb(var(--bs-secondary-rgb)); border: 1px solid rgba(var(--bs-secondary-rgb), 0.2); }
        .status-expired { background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;}
        .status-refill_requested { background-color: #ffe6cc; color: #c45100; border: 1px solid #ffdab3; } /* لون لطلب إعادة الصرف */


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

        .empty-state-container i.empty-icon { /* كلاس مخصص لأيقونة الحالة الفارغة */
            font-size: 3.5rem; /* تكبير الأيقونة */
            color: var(--bs-primary); /* لون أساسي للأيقونة */
            opacity: 0.6;
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state-container h5 {
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 0.5rem;
        }

        .empty-state-container p {
            color: var(--bs-secondary); /* استخدام لون ثانوي للنص */
            font-size: 0.95rem; /* تكبير الخط قليلاً */
        }

        .filter-card .form-control,
        .filter-card .form-select {
            font-size: 0.9rem;
        }

        .filter-card .btn {
            font-size: 0.9rem;
        }

        /* لتاريخ الوصفة بخط أوضح */
        .prescription-date {
            font-weight: 500;
            color: #555;
        }
        .doctor-name {
            font-weight: 500;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                {{-- أيقونة قسم الصيدلية للمريض --}}
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-pills text-primary me-2"></i>وصفاتي الطبية</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">قائمة الوصفات</span>
            </div>
        </div>
        {{-- يمكن إضافة زر "طلب مساعدة من الصيدلية" أو "معلومات التواصل بالصيدلية" هنا إذا أردت --}}
        {{-- <div class="d-flex my-xl-auto right-content">
            <button class="btn btn-outline-primary ripple shadow-sm">
                <i class="fas fa-headset me-1"></i> مساعدة من الصيدلية
            </button>
        </div> --}}
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert') {{-- لعرض رسائل النجاح والخطأ --}}

    {{-- قسم الفلاتر --}}
    <div class="card filter-card" data-aos="fade-down">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter text-primary me-2"></i>فرز وعرض الوصفات</h5>
        </div>
        <div class="card-body">
            {{-- افترض أن المسار للفورم هو نفس الصفحة الحالية (patient.pharmacy.index) --}}
            <form method="GET" action="{{ route('patient.pharmacy.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label for="status_filter_select" class="form-label">عرض حسب حالة الوصفة:</label>
                        <select name="status_filter" id="status_filter_select" class="form-select select2-filter"
                            data-placeholder="جميع الحالات">
                            <option value="">جميع الحالات</option>
                            {{-- ستحتاج لتمرير $prescriptionStatuses من الكنترولر --}}
                            @if (isset($prescriptionStatuses) && count($prescriptionStatuses) > 0)
                                @foreach ($prescriptionStatuses as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ request('status_filter') == $key ? 'selected' : '' }}>{{ $value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="date_from_input" class="form-label">من تاريخ:</label>
                        <input type="text" name="date_from" id="date_from_input" class="form-control flatpickr-date"
                            placeholder="اختر تاريخ البداية" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="date_to_input" class="form-label">إلى تاريخ:</label>
                        <input type="text" name="date_to" id="date_to_input" class="form-control flatpickr-date"
                            placeholder="اختر تاريخ النهاية" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>
                            تطبيق</button>
                        @if (request()->hasAny(['status_filter', 'date_from', 'date_to']))
                            <a href="{{ route('patient.pharmacy.index') }}"
                                class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                                <i class="fas fa-eraser me-1"></i> مسح الفلتر
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- قسم عرض الوصفات --}}
    <div class="card mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0"><i class="fas fa-list-ul me-2"></i>قائمة وصفاتي <span
                    class="badge bg-primary rounded-pill ms-2">{{ $prescriptions->total() }}</span></h4>
        </div>
        <div class="card-body p-0">
            @if ($prescriptions->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="min-width: 130px;">رقم الوصفة</th>
                                <th style="min-width: 180px;">الطبيب</th>
                                <th style="min-width: 120px;">تاريخ الوصفة</th>
                                <th style="min-width: 150px;">الحالة</th>
                                <th class="text-center" style="min-width: 120px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prescriptions as $prescription)
                                <tr>
                                    <td>{{ $loop->iteration + $prescriptions->firstItem() - 1 }}</td>
                                    <td><strong>{{ $prescription->prescription_number }}</strong></td>
                                    <td class="doctor-name">
                                        {{-- أيقونة الطبيب إذا أردت --}}
                                        <i class="fas fa-user-md text-muted me-1"></i>
                                        {{ $prescription->doctor ? $prescription->doctor->name : 'طبيب غير محدد' }}
                                    </td>
                                    <td class="prescription-date">
                                        {{ $prescription->prescription_date->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        @php
                                            // استخدام الـ accessor إذا كان متاحاً، أو بناء الـ class يدوياً
                                            $statusKey = $prescription->status;
                                            $statusText = $prescription->status_display ?? (method_exists($prescription, 'getStatusDisplayAttribute') ? $prescription->status_display : ($prescriptionStatuses[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey))));
                                            $statusBadgeClass = 'status-' . str_replace('_', '-', $statusKey);
                                            if(property_exists($prescription, 'status_badge_class')) { // إذا كان الـ accessor status_badge_class موجود
                                                $statusBadgeClass = $prescription->status_badge_class;
                                            }
                                        @endphp
                                        <span class="status-badge {{ $statusBadgeClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="text-center action-buttons">
                                        <a href="{{ route('patient.pharmacy.show',$prescription->id) }}"
                                            class="btn btn-sm btn-primary-transparent" {{-- استخدام كلاس شفاف --}}
                                            data-bs-toggle="tooltip" title="عرض تفاصيل الوصفة">
                                            <i class="fas fa-eye"></i> <span class="d-none d-md-inline">التفاصيل</span>
                                        </a>
                                        {{-- زر طلب إعادة الصرف (سيتم تفعيله بناءً على شروط) --}}
                                        @if ($prescription->can_request_refill) {{-- افترض أن لديك accessor 'can_request_refill' في موديل Prescription --}}
                                            <form action="{{ route('patient.pharmacy.request-refill', $prescription->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success-transparent" data-toggle="tooltip" title="طلب إعادة صرف هذه الوصفة">
                                                    <i class="fas fa-redo-alt"></i> <span class="d-none d-md-inline">إعادة صرف</span>
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
                    {{ $prescriptions->appends(request()->query())->links() }} {{-- links() مع appends للحفاظ على الفلاتر --}}
                </div>
            @else
                <div class="empty-state-container p-5" data-aos="zoom-in">
                    <i class="fas fa-file-prescription empty-icon"></i> {{-- أيقونة مناسبة --}}
                    <h5 class="mt-3">لا توجد وصفات طبية تطابق بحثك</h5>
                    <p class="text-muted">
                        @if (request()->hasAny(['status_filter', 'date_from', 'date_to']))
                            يرجى المحاولة مرة أخرى بمعايير بحث مختلفة أو قم بإزالة الفلاتر لعرض جميع وصفاتك.
                        @else
                            ليس لديك أي وصفات طبية مسجلة في النظام حتى الآن.
                        @endif
                    </p>
                    @if (request()->hasAny(['status_filter', 'date_from', 'date_to']))
                        <a href="{{ route('patient.pharmacy.index') }}" class="btn btn-outline-secondary mt-2">
                            <i class="fas fa-list-ul me-1"></i> عرض جميع الوصفات
                        </a>
                    @endif
                    {{-- يمكنك إضافة رابط "كيف أحصل على وصفة؟" أو "تواصل مع طبيبك" --}}
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
                duration: 600, // مدة أقصر قليلاً للتحريك
                once: true,
                offset: 20
            });

            $('.select2-filter').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                // dropdownParent: $(this).closest('.filter-card .card-body') // تأكد من أن هذا يعمل
            });

            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                locale: "ar",
                allowInput: true, // للسماح بالكتابة المباشرة
                altInput: true, // لعرض صيغة ألطف للمستخدم
                altFormat: "j F, Y"
            });

            // تفعيل الـ Tooltips من Bootstrap 5
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // NotifIt messages (نفس الكود الذي أرسلته، فهو ممتاز)
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
