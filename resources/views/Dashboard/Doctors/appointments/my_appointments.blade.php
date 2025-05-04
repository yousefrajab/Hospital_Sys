{{-- resources/views/Dashboard/Doctors/appointments/my_appointments.blade.php --}}
@extends('Dashboard.layouts.master') {{-- تأكد من اسم الـ Layout الصحيح --}}

{{-- ========================== CSS Section ========================== --}}
@section('css')
    {{-- استيراد مكتبات CSS أساسية --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- (اختياري) Flatpickr للفلترة بالتاريخ --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css">
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- (اختياري) SweetAlert2 CSS --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> --}}
    {{-- (اختياري) NotifIt CSS (إذا لم يكن محملًا في الـ layout) --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        /* --- الأنماط كما هي في الردود السابقة --- */
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #4A4A4A;
            --accent-color: #50E3C2;
            --light-bg: #f9fbfd;
            --border-color: #e5e9f2;
            --white-color: #ffffff;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #3498db;
            --text-dark: #34495e;
            --text-muted: #95a5a6;
            --card-shadow: 0 8px 25px rgba(140, 152, 164, 0.1);
        }

        body {
            background: var(--light-bg);
            font-family: 'Cairo', sans-serif;
        }

        .appointments-container {
            padding: 1.5rem;
        }

        .appointments-header {
            margin-bottom: 2rem;
        }

        .appointments-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .appointments-title i {
            color: var(--primary-color);
        }

        .filter-controls {
            background: var(--white-color);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 1rem;
        }

        .filter-group {
            flex: 1 1 200px;
        }

        .filter-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 100;
            color: var(--text-muted);
            margin-bottom: 0.3rem;
        }

        .form-control-filter,
        .form-select-filter {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            width: 30%;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control-filter:focus,
        .form-select-filter:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
            outline: none;
        }

        .btn-filter {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.55rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            background-color: #3a7bc8;
            transform: translateY(-1px);
        }

        .appointments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .appointment-card {
            background: var(--white-color);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border-left: 5px solid;
            padding: 1.25rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(140, 152, 164, 0.2);
        }

        .appointment-card[data-status="مؤكد"] {
            border-left-color: var(--success-color);
        }

        .appointment-card[data-status="غير مؤكد"] {
            border-left-color: var(--warning-color);
        }

        .appointment-card[data-status="منتهي"] {
            border-left-color: var(--text-muted);
        }

        .appointment-card[data-status="ملغي"] {
            border-left-color: var(--danger-color);
        }

        .appointment-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .patient-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .patient-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            background-color: var(--light-bg);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .patient-name {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 1.05rem;
            margin-bottom: 2px;
        }

        .patient-contact {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .appointment-status {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-confirmed {
            background-color: rgba(46, 204, 113, 0.1);
            color: #208e4c;
        }

        .status-pending {
            background-color: rgba(243, 156, 18, 0.1);
            color: #c87f0a;
        }

        .status-completed {
            background-color: rgba(149, 165, 166, 0.1);
            color: #708090;
        }

        .status-cancelled {
            background-color: rgba(231, 76, 60, 0.1);
            color: #c0392b;
        }

        .appointment-card-body {
            margin-bottom: 1.25rem;
            flex-grow: 1;
        }

        .appointment-time {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1rem;
        }

        .appointment-time i {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        .appointment-notes {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.6;
            background-color: var(--light-bg);
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            max-height: 100px;
            overflow-y: auto;
        }

        .appointment-notes strong {
            color: var(--secondary-color);
        }

        .appointment-card-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.5rem;
            border-top: 1px solid var(--border-color);
            padding-top: 1rem;
            margin-top: auto;
        }

        .appointment-action-btn {
            background: none;
            border: 1px solid;
            border-radius: 50px;
            padding: 5px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-confirm {
            border-color: var(--success-color);
            color: var(--success-color);
        }

        .btn-confirm:hover {
            background-color: var(--success-color);
            color: white;
        }

        .btn-cancel-app {
            border-color: var(--danger-color);
            color: var(--danger-color);
        }

        .btn-cancel-app:hover {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-complete {
            border-color: var(--info-color);
            color: var(--info-color);
        }

        .btn-complete:hover {
            background-color: var(--info-color);
            color: white;
        }

        .btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            box-shadow: none;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .page-link {
            color: var(--primary-color);
        }

        .page-link:hover {
            color: var(--secondary-color);
        }

        .no-appointments {
            text-align: center;
            padding: 3rem 1rem;
            background: var(--white-color);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            color: var(--text-muted);
        }

        .no-appointments i {
            font-size: 3rem;
            display: block;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-appointments p {
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Spinner Styling */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: .2em;
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
@endsection

@section('title')
    مواعيـدي
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">المواعيد</h4> <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ مواعيدي
                    المحجوزة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

{{-- ====================== HTML Content Section ===================== --}}
@section('content')
    @include('Dashboard.messages_alert')

    <div class="appointments-container">
        <div class="appointments-header">
            <h1 class="appointments-title"><i class="fas fa-calendar-check"></i> مواعيدي المؤكدة القادمة</h1>
        </div>

        {{-- قسم الفلترة والبحث (يمكن الإبقاء عليه أو حذفه) --}}
        <div class="filter-controls animate__animated animate__fadeIn mb-4">
            <div class="filter-group"> <label for="patientSearch">البحث عن مريض</label> <input type="text"
                    id="patientSearch" class="form-control-filter" placeholder="ادخل اسم المريض..."> </div>

        </div>

        {{-- عرض المواعيد --}}
        <div class="appointments-grid">
            @forelse ($appointments as $appointment)
                {{-- data-status لا يزال مفيداً لتلوين الخط --}}
                <div class="appointment-card animate__animated animate__fadeInUp"
                    data-appointment-id="{{ $appointment->id }}" data-status="{{ $appointment->type }}"
                    style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <div class="appointment-card-header">
                        <div class="patient-info">
                            <div class="patient-avatar"> {{ mb_substr($appointment->name, 0, 1) }} </div>
                            <div>
                                <h5 class="patient-name">{{ $appointment->name }}</h5>
                                <span class="patient-contact"><i class="fas fa-phone-alt fa-xs"></i>
                                    {{ $appointment->phone ?: 'غير متوفر' }}</span>
                            </div>
                        </div>
                        {{-- عرض شارة "مؤكد" --}}
                        <span class="appointment-status status-confirmed">
                            <i class="fas fa-check-circle"></i> مؤكد
                        </span>
                    </div>
                    <div class="appointment-card-body">
                        <div class="appointment-time">
                            <i class="fas fa-clock"></i>
                            <span>{{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : 'غير محدد' }}</span>
                        </div>
                        @if ($appointment->notes)
                            <div class="appointment-notes">
                                <strong><i class="fas fa-sticky-note"></i> ملاحظات:</strong>
                                <p class="mb-0">{{ $appointment->notes }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="appointment-card-footer">
                        {{-- *** الأزرار المتاحة للطبيب للموعد المؤكد *** --}}
                        <button type="button" class="appointment-action-btn btn-cancel-app"> <i class="fas fa-times"></i>
                            إلغاء </button>
                        <button type="button" class="appointment-action-btn btn-complete"> <i
                                class="fas fa-user-check"></i> اكتمل </button>
                        {{-- *** تم حذف زر التأكيد *** --}}
                    </div>
                </div>
            @empty
                <div class="no-appointments col-span-full"> <i class="fas fa-calendar-check"></i>
                    <p>لا توجد مواعيد مؤكدة قادمة حالياً.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($appointments->hasPages())
            <div class="pagination-wrapper mt-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
@endsection
{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    {{-- مكتبة الإشعارات (يجب أن تكون محملة) --}}
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    {{-- SweetAlert2 (اختياري) --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- تهيئة Flatpickr لفلتر التاريخ ---
            if (document.getElementById('dateFilter')) {
                flatpickr("#dateFilter", {
                    dateFormat: "Y-m-d",
                    locale: "ar",
                    altInput: true,
                    altFormat: "j F Y"
                });
            }

            // --- CSRF Token ---
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.error('CSRF Token not found!');
            } // تنبيه إذا لم يوجد التوكن

            // --- دالة عامة لإرسال طلب تغيير الحالة ---
            async function updateAppointmentStatus(appointmentId, action) {
                const urlMap = {
                    confirm: "{{ route('doctor.appointments.confirm', ':id') }}",
                    cancel: "{{ route('doctor.appointments.cancel', ':id') }}",
                    complete: "{{ route('doctor.appointments.complete', ':id') }}"
                };
                // التأكد من وجود action صالح
                if (!urlMap[action]) {
                    console.error('Invalid action provided:', action);
                    return;
                }
                const url = urlMap[action].replace(':id', appointmentId);

                // --- استخدام نافذة التأكيد الافتراضية ---
                const messages = {
                    confirm: 'هل أنت متأكد من تأكيد هذا الموعد؟',
                    cancel: 'هل أنت متأكد من إلغاء هذا الموعد؟ (قد تحتاج لإبلاغ المريض)',
                    complete: 'هل تأكد من اكتمال هذا الموعد؟'
                };
                if (!confirm(messages[action])) {
                    return; // الخروج إذا ألغى المستخدم
                }
                // --- نهاية نافذة التأكيد ---


                // --- إظهار مؤشر التحميل ---
                const card = document.querySelector(
                    `.appointment-card[data-appointment-id="${appointmentId}"]`);
                const footer = card ? card.querySelector('.appointment-card-footer') : null;
                const originalFooterContent = footer ? footer.innerHTML : '';
                if (footer) {
                    footer.innerHTML =
                        '<div class="spinner-border spinner-border-sm text-primary mx-auto" role="status"><span class="visually-hidden">جاري...</span></div>'; // توسيط المؤشر
                }


                try {
                    const response = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        // body: JSON.stringify({ }) // إرسال بيانات إضافية إذا لزم الأمر
                    });

                    const result = await response.json();

                    if (response.ok) {
                        console.log('Success:', result.message);
                        updateCardUI(result.appointment_id, result.new_status); // تحديث الواجهة
                        showNotification(result.message, 'success', 4000); // رسالة نجاح
                    } else {
                        console.error('Error Response:', result.message);
                        showNotification(`خطأ: ${result.message || 'فشل تحديث حالة الموعد.'}`, 'error', 6000);
                        if (footer) footer.innerHTML = originalFooterContent; // إعادة الأزرار عند الخطأ
                    }

                } catch (error) {
                    console.error('Network/Fetch Error:', error);
                    showNotification('حدث خطأ في الاتصال بالخادم.', 'error', 6000);
                    if (footer) footer.innerHTML = originalFooterContent; // إعادة الأزرار عند الخطأ
                }
            } // نهاية updateAppointmentStatus

            // --- ربط الأحداث باستخدام Event Delegation ---
            const grid = document.querySelector('.appointments-grid');
            if (grid) {
                grid.addEventListener('click', function(event) {
                    const button = event.target.closest('.appointment-action-btn');
                    if (!button || button.disabled) return; // الخروج إذا لم يكن زرًا فعالاً

                    const card = button.closest('.appointment-card');
                    const appointmentId = card ? card.getAttribute('data-appointment-id') :
                        null; // استخدام getAttribute

                    if (!appointmentId) {
                        console.error('Could not find appointment ID!');
                        return;
                    }

                    // تحديد الإجراء بناءً على كلاس الزر
                    let action = null;
                    if (button.classList.contains('btn-confirm')) {
                        action = 'confirm';
                    } else if (button.classList.contains('btn-cancel-app')) {
                        action = 'cancel';
                    } else if (button.classList.contains('btn-complete')) {
                        action = 'complete';
                    }

                    if (action) {
                        // تم نقل نافذة التأكيد لداخل الدالة updateAppointmentStatus (باستخدام confirm() حالياً)
                        updateAppointmentStatus(appointmentId, action);
                    }
                });
            } else {
                console.warn("Element with class '.appointments-grid' not found.");
            } // نهاية ربط الأحداث

            // --- دالة تحديث واجهة البطاقة ---
            function updateCardUI(appointmentId, newStatus) {
                /* ... الكود كما هو في الرد السابق ... */
                const card = document.querySelector(`.appointment-card[data-appointment-id="${appointmentId}"]`);
                if (!card) return;
                card.dataset.status = newStatus;
                const statusBadge = card.querySelector('.appointment-status');
                let statusBadgeClass = '';
                let statusText = newStatus;
                if (newStatus === 'مؤكد') {
                    card.style.borderLeftColor = 'var(--success-color)';
                    statusBadgeClass = 'status-confirmed';
                } else if (newStatus === 'غير مؤكد') {
                    card.style.borderLeftColor = 'var(--warning-color)';
                    statusBadgeClass = 'status-pending';
                } else if (newStatus === 'منتهي') {
                    card.style.borderLeftColor = 'var(--text-muted)';
                    statusBadgeClass = 'status-completed';
                } else if (newStatus === 'ملغي') {
                    card.style.borderLeftColor = 'var(--danger-color)';
                    statusBadgeClass = 'status-cancelled';
                }
                if (statusBadge) {
                    statusBadge.textContent = statusText;
                    statusBadge.className = `appointment-status ${statusBadgeClass}`;
                }
                const footer = card.querySelector('.appointment-card-footer');
                if (footer) {
                    let newButtons = '';
                    if (newStatus === 'غير مؤكد') {
                        newButtons =
                            `<button type="button" class="appointment-action-btn btn-confirm"><i class="fas fa-check"></i> تأكيد</button> <button type="button" class="appointment-action-btn btn-cancel-app"><i class="fas fa-times"></i> إلغاء</button>`;
                    } else if (newStatus === 'مؤكد') {
                        newButtons =
                            `<button type="button" class="appointment-action-btn btn-cancel-app"><i class="fas fa-times"></i> إلغاء</button> <button type="button" class="appointment-action-btn btn-complete"><i class="fas fa-user-check"></i> اكتمل</button>`;
                    } else if (newStatus === 'منتهي') {
                        newButtons =
                            `<button class="appointment-action-btn btn-disabled" disabled><i class="fas fa-history"></i> منتهي</button>`;
                    } else if (newStatus === 'ملغي') {
                        newButtons =
                            `<button class="appointment-action-btn btn-disabled" disabled><i class="fas fa-ban"></i> ملغي</button>`;
                    }
                    footer.innerHTML = newButtons;
                }
            }

            // --- دالة عرض الإشعارات ---
            function showNotification(message, type = 'info', timeout = 5000) {
                /* ... الكود كما هو ... */
                if (typeof notif !== 'undefined') {
                    let options = {
                        msg: message,
                        type: type,
                        position: "top-center",
                        width: "auto",
                        multiline: true,
                        autohide: true,
                        timeout: timeout,
                        opacity: 0.95,
                        fade: true,
                        clickable: true,
                        icon: true,
                        bgcolor: type === 'success' ? '#20a36a' : (type === 'error' ? '#e8565f' : (type ===
                            'warning' ? '#ffb648' : '#508ff4')),
                        color: "#ffffff",
                        animation: 'fadeInDown',
                    };
                    notif(options);
                } else {
                    alert(message.replace(/<br>/g, '\n').replace(/<b>|<\/b>/g, ''));
                }
            }

            // --- كود فلترة الواجهة الأمامية ---
            const searchInput = document.getElementById('patientSearch');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilterInput = document.getElementById('dateFilter'); // حقل فلتر التاريخ
            const appointmentCards = document.querySelectorAll('.appointment-card');

            function applyFilters() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const statusValue = statusFilter ? statusFilter.value : '';
                const dateValue = dateFilterInput ? dateFilterInput.value : ''; // قيمة التاريخ المختار YYYY-MM-DD

                appointmentCards.forEach(card => {
                    const patientName = card.querySelector('.patient-name')?.textContent.toLowerCase() ||
                        '';
                    const cardStatus = card.dataset.status || '';
                    // الحصول على تاريخ الموعد من النص الظاهر (أو يمكنك إضافة data attribute للتاريخ)
                    const cardDateText = card.querySelector('.appointment-time span')?.textContent || '';
                    // استخلاص التاريخ YYYY-MM-DD من النص (يتطلب تعديلاً حسب التنسيق الدقيق)
                    // هذا الجزء قد يكون معقداً وغير دقيق، يفضل الفلترة في الـ Backend
                    let cardDate = '';
                    const dateMatch = cardDateText.match(
                        /(\d{4}) (\p{L}+) (\d{1,2})/u); // محاولة استخلاص السنة والشهر واليوم
                    if (dateMatch) {
                        // ستحتاج لتحويل الشهر العربي لرقم ثم بناء التاريخ YYYY-MM-DD
                        // هذا مثال مبسط جداً وقد لا يعمل لكل الحالات
                        // const month = convertArabicMonthToNumber(dateMatch[2]);
                        // cardDate = `${dateMatch[1]}-${String(month).padStart(2,'0')}-${String(dateMatch[3]).padStart(2,'0')}`;
                    }


                    let showCard = true;

                    if (searchTerm && !patientName.includes(searchTerm)) showCard = false;
                    if (statusValue && cardStatus !== statusValue) showCard = false;
                    // (اختياري) فلترة بالتاريخ (غير دقيقة في الواجهة الأمامية)
                    // if (dateValue && cardDate !== dateValue) showCard = false;

                    // إظهار/إخفاء مع تأثير
                    if (showCard) {
                        card.style.display = '';
                        card.classList.remove('animate__fadeOut');
                        card.classList.add('animate__fadeInUp');
                    } else {
                        card.classList.remove('animate__fadeInUp');
                        card.classList.add('animate__fadeOut');
                        // تأخير الإخفاء الفعلي للسماح بانتهاء الحركة
                        setTimeout(() => {
                            if (!card.style.display == '') card.style.display = 'none';
                        }, 500); // 500ms مدة الحركة
                    }
                });
                // إظهار رسالة إذا لم توجد نتائج (اختياري)
                const visibleCards = document.querySelectorAll('.appointment-card:not([style*="display: none"])')
                    .length;
                const noResultsMessage = document.getElementById('no-filter-results'); // تحتاج لإضافة هذا العنصر
                if (noResultsMessage) noResultsMessage.style.display = visibleCards === 0 ? 'block' : 'none';

            } // نهاية applyFilters

            if (searchInput) searchInput.addEventListener('input', applyFilters);
            if (statusFilter) statusFilter.addEventListener('change', applyFilters);
            // ربط فلتر التاريخ (إذا استخدمته)
            if (dateFilterInput && dateFilterInput._flatpickr) { // التحقق من تهيئة flatpickr
                dateFilterInput._flatpickr.config.onChange.push(function(selectedDates, dateStr, instance) {
                    // الفلترة هنا تتم في الواجهة وقد لا تكون دقيقة
                    // applyFilters();
                    // الأفضل: إرسال طلب فلترة للخادم عند تغيير التاريخ
                    console.log("Date filter changed (client-side filter might be inaccurate):", dateStr);
                    alert("فلترة التاريخ المتقدمة تتطلب تحديث من الخادم.");
                });
            }


        }); // نهاية DOMContentLoaded

    </script>
@endsection
