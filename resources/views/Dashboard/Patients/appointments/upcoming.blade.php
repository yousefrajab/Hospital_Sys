@extends('Dashboard.layouts.master') {{-- أو الـ layout الخاص بلوحة تحكم المريض --}}

@section('title', 'مواعيـدي القادمة')

@section('css')
    @parent
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- NotifIt CSS (إذا كنت ستعرض رسائل هنا) --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        /* --- المتغيرات الأساسية (Globals & Dark Mode) --- */
        :root {
            --primary-color: #4A90E2;
            /* لون أساسي جذاب */
            --primary-dark: #3A7BC8;
            --secondary-color: #4A4A4A;
            /* رمادي داكن للنصوص أو العناصر الثانوية */
            --accent-color: #50E3C2;
            /* لون مميز (تركواز) */
            --light-bg: #f9fbfd;
            /* خلفية فاتحة جداً للصفحة */
            --border-color: #e5e9f2;
            /* لون حدود ناعم */
            --white-color: #ffffff;
            --success-color: #2ecc71;
            /* أخضر للنجاح/التأكيد */
            --warning-color: #f39c12;
            /* برتقالي للتحذير */
            --danger-color: #e74c3c;
            /* أحمر للخطر/الإلغاء */
            --info-color: #3498db;
            /* أزرق للمعلومات */
            --text-dark: #34495e;
            /* لون نص داكن */
            --text-muted: #95a5a6;
            /* لون نص باهت */
            --card-shadow: 0 8px 25px rgba(140, 152, 164, 0.1);
            --admin-radius-md: 0.5rem;
            /* 8px */
            --admin-radius-lg: 0.75rem;
            /* 12px */
            --admin-transition: all 0.3s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --light-bg: #1a1d24;
                --white-color: #242930;
                --border-color: #374151;
                --text-dark: #e9ecef;
                --text-muted: #adb5bd;
                --primary-color: #5c9bff;
                --accent-color: #67e8f9;
            }

            .appointment-card {
                border-left-color: var(--accent-color) !important;
            }

            .appointment-card:hover {
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            }
        }

        /* كلاس .dark لتفعيل الوضع الداكن يدويًا */
        .dark body {
            /* ... (نفس متغيرات prefers-color-scheme: dark) ... */
        }


        body {
            background: var(--light-bg);
            font-family: 'Tajawal', sans-serif;
            color: var(--text-dark);
        }

        .appointments-page-container {
            padding: 1.5rem;
            max-width: 1200px;
            margin: auto;
        }

        .page-header-flex {
            /* لتنسيق هيدر الصفحة */
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title-flex {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
        }

        .page-title-flex i {
            font-size: 1.2em;
        }

        .page-actions .btn {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: var(--admin-radius-md);
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
        }

        .btn-outline-secondary-custom {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-secondary-custom:hover {
            background-color: var(--secondary-color);
            color: white;
        }


        .appointments-grid-patient {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            /* عرض أدنى أكبر قليلاً */
            gap: 1.75rem;
        }

        .appointment-card {
            background: var(--white-color);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--card-shadow);
            border-left: 5px solid var(--accent-color);
            /* لون مميز للمواعيد القادمة */
            padding: 0;
            transition: var(--admin-transition);
            display: flex;
            flex-direction: column;
            /* لترتيب الفوتر في الأسفل */
            overflow: hidden;
            /* لضمان احتواء العناصر الداخلية */
        }

        .appointment-card:hover {
            transform: translateY(-5px) scale(1.02);
            /* تأثير hover أكبر */
            box-shadow: 0 12px 35px rgba(var(--primary-color-rgb, 74, 144, 226), 0.2);
        }

        .appointment-card-header-patient {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .appointment-title-patient {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.25rem;
        }

        .appointment-date-patient {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--secondary-color);
            /* لون مختلف للتاريخ */
        }

        .appointment-status-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.35rem 0.8rem;
            border-radius: 50px;
            text-transform: uppercase;
        }

        .badge-confirmed-patient {
            background-color: rgba(var(--success-color-rgb, 46, 204, 113), 0.15);
            color: var(--success-color);
            border: 1px solid rgba(var(--success-color-rgb), 0.3);
        }

        .badge-pending-patient {
            background-color: rgba(var(--warning-color-rgb, 243, 156, 18), 0.15);
            color: var(--warning-color);
            border: 1px solid rgba(var(--warning-color-rgb), 0.3);
        }


        .appointment-card-body-patient {
            padding: 1.25rem;
            flex-grow: 1;
            /* لجعل الجسم يملأ المساحة المتبقية */
        }

        .appointment-details-list-patient {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .appointment-details-list-patient li {
            display: flex;
            align-items: flex-start;
            /* محاذاة للبداية للنصوص الطويلة */
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .appointment-details-list-patient li i {
            width: 22px;
            text-align: center;
            margin-left: 10px;
            /* RTL: margin-right */
            color: var(--text-muted);
            font-size: 1em;
            padding-top: 2px;
            /* لمحاذاة أفضل مع النص */
        }

        .appointment-details-list-patient li strong {
            font-weight: 500;
            color: var(--text-dark);
        }

        .appointment-details-list-patient li .detail-label {
            font-weight: 400;
            color: var(--text-muted);
            min-width: 70px;
            display: inline-block;
        }

        .appointment-card-footer-patient {
            background-color: #fdfdff;
            /* لون أفتح قليلاً جداً */
            padding: 0.75rem 1.25rem;
            border-top: 1px solid var(--border-color);
            border-radius: 0 0 var(--admin-radius-lg) var(--admin-radius-lg);
            text-align: left;
            /* الأزرار على اليسار في RTL */
        }

        .btn-appointment-action {
            font-size: 0.8rem;
            padding: 0.4rem 1rem;
            border-radius: var(--admin-radius-md);
            font-weight: 500;
            transition: var(--admin-transition);
        }

        .btn-outline-danger-custom {
            color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-outline-danger-custom:hover {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .no-appointments-patient {
            text-align: center;
            padding: 3rem 1rem;
            background: var(--white-color);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--card-shadow);
            color: var(--text-muted);
        }

        .no-appointments-patient i {
            font-size: 3rem;
            display: block;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: var(--primary-color);
        }

        .no-appointments-patient p {
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        /* ... (أنماط Pagination كما هي من الرد السابق) ... */
        :root {
            /* ... (متغيرات الألوان بصيغة RGB إذا احتجتها في JavaScript) ... */
            --success-color-rgb: 46, 204, 113;
            --warning-color-rgb: 243, 156, 18;
        }

        .badge-confirmed-patient {
            background-color: rgba(var(--success-color-rgb, 46, 204, 113), 0.15);
            color: var(--success-color);
            border: 1px solid rgba(var(--success-color-rgb), 0.3);
        }

        .badge-pending-patient {
            background-color: rgba(var(--warning-color-rgb, 243, 156, 18), 0.15);
            color: var(--warning-color);
            border: 1px solid rgba(var(--warning-color-rgb), 0.3);
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-calendar-check me-2 text-success"></i>مواعيـدي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ القادمة</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            @if (Route::has('patient.appointments.past'))
                <a href="{{ route('patient.appointments.past') }}" class="btn btn-outline-secondary btn-sm me-2"
                    style="border-radius:var(--admin-radius-md);">
                    <i class="fas fa-history me-1"></i> المواعيد السابقة
                </a>
            @endif
            @if (Route::has('patient.appointments.book'))
                {{-- أو اسم الـ route الخاص بـ Livewire --}}
                <a href="{{ route('patient.appointments.book') }}" class="btn btn-primary btn-sm"
                    style="border-radius:var(--admin-radius-md);">
                    <i class="fas fa-plus-circle me-1"></i> طلب موعد جديد
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="appointments-page-container">
        <div class="page-header-flex animate__animated animate__fadeInDown">
            <h1 class="page-title-flex"><i class="fas fa-business-time"></i> مواعيدك القادمة</h1>
        </div>

        @if (isset($appointments) && $appointments->isNotEmpty()) {{-- ** إضافة تحقق من وجود $appointments ** --}}
            <div class="appointments-grid-patient">
                @foreach ($appointments as $appointment)
                    <div class="appointment-card animate__animated animate__fadeInUp"
                        data-appointment-id="{{ $appointment->id }}" style="animation-delay: {{ $loop->index * 0.08 }}s;">
                        <div class="appointment-card-header-patient">
                            <div>
                                <h5 class="appointment-title-patient">
                                    @if ($appointment->section)
                                        {{ $appointment->section->name }} {{-- افترض أن name هنا ليس مترجمًا --}}
                                    @else
                                        موعد عام
                                    @endif
                                    {{-- إذا كان لديك حقل 'type' خاص بالموعد نفسه غير حالته --}}
                                    {{-- {{ $appointment->type_of_appointment ? ' - ' . $appointment->type_of_appointment : '' }} --}}
                                </h5>
                                <span class="appointment-date-patient">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{-- ** استخدام $appointment->appointment ** --}}
                                    {{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، j F Y') : 'تاريخ غير محدد' }}
                                    <i class="far fa-clock ms-2"></i>
                                    {{ $appointment->appointment ? $appointment->appointment->translatedFormat('h:i A') : 'وقت غير محدد' }}
                                </span>
                            </div>
                            @php
                                $statusText = $appointment->type; // ** استخدام $appointment->type مباشرة **
                                $statusBadgeClass = 'badge-secondary'; // افتراضي
                                if ($appointment->type == \App\Models\Appointment::STATUS_CONFIRMED) {
                                    // استخدام الثابت
                                    $statusBadgeClass = 'badge-confirmed-patient';
                                } elseif ($appointment->type == \App\Models\Appointment::STATUS_PENDING) {
                                    // استخدام الثابت
                                    $statusBadgeClass = 'badge-pending-patient';
                                }
                            @endphp
                            <span class="badge appointment-status-badge {{ $statusBadgeClass }}">
                                @if ($appointment->type == \App\Models\Appointment::STATUS_CONFIRMED)
                                    <i class="fas fa-check-circle me-1"></i>
                                @endif
                                @if ($appointment->type == \App\Models\Appointment::STATUS_PENDING)
                                    <i class="fas fa-hourglass-half me-1"></i>
                                @endif
                                {{ $statusText }} {{-- عرض النص مباشرة من $appointment->type --}}
                            </span>
                        </div>
                        <div class="appointment-card-body-patient">
                            <ul class="appointment-details-list-patient">
                                @if ($appointment->doctor)
                                    <li><i class="fas fa-user-md"></i> <span class="detail-label">الطبيب:</span>
                                        <strong>{{ $appointment->doctor->name }}</strong>
                                    </li> {{-- افترض أن name هنا ليس مترجمًا --}}
                                @endif
                                {{-- ملاحظة: $appointment->notes_by_patient و $appointment->notes_by_staff غير موجودة في $fillable لموديل Appointment الذي أرسلته --}}
                                {{-- إذا أضفتها، يمكنك عرضها هنا --}}
                                @if ($appointment->notes)
                                    {{-- استخدام الحقل العام 'notes' --}}
                                    <li><i class="fas fa-sticky-note"></i> <span class="detail-label">ملاحظات الموعد:</span>
                                        <strong>{{ Str::limit($appointment->notes, 120) }}</strong>
                                    </li>
                                @endif
                                {{-- <li><i class="fas fa-clock"></i> <span class="detail-label">المدة المتوقعة:</span>
                                    <strong>{{ $appointment->appointment_duration ?? 'غير محددة' }} دقيقة</strong>
                                </li> --}}
                                {{-- افترض وجود appointment_duration --}}
                            </ul>
                        </div>
                        <div class="appointment-card-footer-patient">
                            @if (
                                $appointment->type == \App\Models\Appointment::STATUS_PENDING ||
                                    $appointment->type == \App\Models\Appointment::STATUS_CONFIRMED)
                                <button type="button"
                                    class="btn btn-appointment-action btn-outline-danger-custom cancel-appointment-action-btn"
                                    {{-- ** تم تغيير الكلاس هنا ليكون أكثر تحديدًا ** --}} data-appointment-id="{{ $appointment->id }}"
                                    data-appointment-details="موعد لـ '{{ $appointment->name }}' مع د. {{ $appointment->doctor->name ?? 'غير محدد' }} بتاريخ {{ $appointment->appointment ? $appointment->appointment->translatedFormat('d M Y \ا\ل\س\ا\ع\ة H:i') : '' }}"
                                    title="إلغاء هذا الموعد">
                                    <i class="fas fa-times-circle"></i> إلغاء الموعد
                                </button>
                            @else
                                <span class="text-muted small">لا توجد إجراءات متاحة</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($appointments->hasPages())
                <div class="pagination-wrapper mt-4">
                    {{ $appointments->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="no-appointments-patient text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p>لا توجد لديك مواعيد قادمة حاليًا.</p>
                @if (Route::has('patient.appointments.book'))
                    {{-- أو اسم الـ route الخاص بـ Livewire --}}
                    <a href="{{ route('patient.appointments.book') }}" class="btn btn-lg btn-primary-custom mt-2">
                        <i class="fas fa-plus-circle me-2"></i> اطلب موعدًا جديدًا الآن
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    {{-- SweetAlert2 (موصى به للتأكيد) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true
        });

        $(document).ready(function() { // استخدام jQuery
            const csrfToken = $('meta[name="csrf-token"]').attr('content'); // استخدام jQuery لجلب التوكن

            function showAppNotification(message, type = 'info', timeout = 5000, position = 'bottom') {
                let iconClass = 'fas fa-info-circle';
                if (type === 'success') iconClass = 'fas fa-check-circle';
                else if (type === 'error') iconClass = 'fas fa-times-circle';
                else if (type === 'warning') iconClass = 'fas fa-exclamation-triangle';

                notif({
                    msg: `<div class="d-flex align-items-center"><i class='${iconClass} fa-lg me-2'></i><div>${message}</div></div>`,
                    type: type,
                    position: position,
                    width: "auto",
                    multiline: true,
                    autohide: true,
                    timeout: timeout,
                    zindex: 99999,
                    bgcolor: type === 'success' ? '#28a745' : (type === 'error' ? '#dc3545' : (type ===
                        'warning' ? '#ffc107' : '#17a2b8')),
                    color: "#ffffff",
                });
            }

            @if (session('success'))
                showAppNotification("{{ session('success') }}", "success");
            @endif
            @if (session('error'))
                showAppNotification("{{ session('error') }}", "error", 7000);
            @endif

            // --- التعامل مع زر إلغاء الموعد ---
            // استخدام event delegation إذا تم تحميل البطاقات ديناميكيًا لاحقًا
            // أو ربط مباشر إذا كانت كل البطاقات موجودة عند تحميل الصفحة
            $('.appointments-grid-patient').on('click', '.cancel-appointment-action-btn', function() {
                const appointmentId = $(this).data('appointment-id');
                const appointmentDetails = $(this).data('appointment-details');
                const $buttonElement = $(this); // الزر الذي تم النقر عليه (كائن jQuery)
                const $cardElement = $buttonElement.closest('.appointment-card');

                if (!appointmentId) {
                    console.error('Appointment ID not found on cancel button.');
                    return;
                }

                Swal.fire({
                    title: 'تأكيد إلغاء الموعد',
                    html: `هل أنت متأكد من رغبتك في إلغاء هذا الموعد؟<br><small class="text-muted">${appointmentDetails}</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // أحمر
                    cancelButtonColor: '#6c757d', // رمادي
                    confirmButtonText: '<i class="fas fa-trash-alt"></i> نعم، قم بالإلغاء',
                    cancelButtonText: '<i class="fas fa-times"></i> لا، تراجع',
                    customClass: {
                        confirmButton: 'btn btn-danger mx-1',
                        cancelButton: 'btn btn-secondary mx-1'
                    },
                    buttonsStyling: false,
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            // بناء الـ URL بشكل صحيح
                            const cancelUrl = "{{ url('patient/appointments') }}/" +
                                appointmentId + "/cancel-by-patient";

                            const response = await fetch(cancelUrl, {
                                method: 'PATCH', // أو 'DELETE' إذا كان الـ route كذلك
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                // body: JSON.stringify({ reason: 'سبب الإلغاء إذا أردت إضافته من مودال آخر' })
                            });
                            if (!response.ok) {
                                const errorData = await response.json();
                                throw new Error(errorData.message ||
                                    `فشل الإلغاء (حالة: ${response.status})`);
                            }
                            return await response.json();
                        } catch (error) {
                            Swal.showValidationMessage(`فشل طلب الإلغاء: ${error.message}`);
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const apiResult = result.value;
                        showAppNotification(apiResult.message || 'تم إلغاء الموعد بنجاح.',
                            'success');

                        // تحديث واجهة المستخدم للبطاقة الملغاة
                        if ($cardElement.length) {
                            $cardElement.attr('data-status', apiResult.new_status ||
                                'ملغي'); // تحديث data-status
                            $cardElement.css('border-left-color',
                                'var(--danger-color)'); // تغيير لون الشريط الجانبي

                            const $statusBadge = $cardElement.find('.appointment-status-badge');
                            if ($statusBadge.length) {
                                $statusBadge.removeClass(
                                        'badge-confirmed-patient badge-pending-patient')
                                    .addClass('badge-cancelled-past') // افترض أن لديك هذا الكلاس
                                    .html(
                                        `<i class="fas fa-ban me-1"></i> ${apiResult.new_status || 'ملغي'}`
                                    );
                            }
                            // استبدال زر الإلغاء بنص أو إخفاء قسم الفوتر
                            $buttonElement.closest('.appointment-card-footer-patient')
                                .html(
                                    '<span class="text-danger small fw-bold p-2">تم إلغاء هذا الموعد</span>'
                                );
                            // أو يمكنك إخفاء البطاقة بالكامل إذا أردت
                            // $cardElement.fadeOut(500, function() { $(this).remove(); });
                        }
                    }
                });
            });
        });
    </script>
@endsection
