@extends('Dashboard.layouts.master')

{{-- ========================== CSS Section ========================== --}}
@section('css')
    {{-- استيراد المكتبات الأساسية (تأكد من أنها محملة في الـ layout أو هنا) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- Notify CSS (موجود في الكود الأصلي) --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- يمكنك إضافة Select2 أو Flatpickr هنا إذا احتجت لفلترة متقدمة لاحقاً --}}

    {{-- *** أنماط البطاقات والتصميم الجديد *** --}}
    <style>
        :root {
            /* استخدام نفس لوحة الألوان لتوحيد الهوية */
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

        .admin-appointments-container {
            padding: 1.5rem;
        }

        /* كلاس مختلف للحاوية */
        .page-title-container {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            color: var(--primary-color);
        }

        /* شبكة البطاقات */
        .admin-appointments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        /* تصميم بطاقة الموعد للأدمن (مشابه لبطاقة الطبيب) */
        .admin-appointment-card {
            background: var(--white-color);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border-left: 5px solid;
            /* تحديد اللون حسب الحالة */
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .admin-appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(140, 152, 164, 0.15);
        }

        .card-main-info {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border-color);
            flex-grow: 1;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            /* محاذاة للبداية */
            margin-bottom: 0.8rem;
        }

        .info-icon {
            width: 25px;
            text-align: center;
            margin-left: 10px;
            color: var(--primary-color);
            opacity: 0.9;
            font-size: 1.1em;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .info-details {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-dark);
            word-break: break-word;
        }

        .info-value.doctor-name,
        .info-value.section-name {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .notes-section {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--border-color);
        }

        .notes-section .info-label {
            font-size: 0.9rem;
        }

        .notes-section .info-value {
            font-size: 0.9rem;
            line-height: 1.6;
            max-height: 80px;
            overflow-y: auto;
            color: #555;
        }

        .card-actions {
            background-color: var(--light-bg);
            padding: 0.75rem 1.25rem;
            border-radius: 0 0 12px 12px;
            display: flex;
            justify-content: flex-end;
            /* الأزرار على اليسار في RTL */
            gap: 0.5rem;
        }

        .action-btn {
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-approve {
            background-color: var(--success-color);
            color: white;
        }

        .btn-approve:hover {
            background-color: #25a25a;
            box-shadow: 0 2px 5px rgba(40, 167, 69, 0.3);
        }

        .btn-delete {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
        }

        /* Placeholder لعدم وجود مواعيد */
        .no-appointments-admin {
            text-align: center;
            padding: 3rem 1rem;
            background: var(--white-color);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            color: var(--text-muted);
            grid-column: 1 / -1;
            /* لتمتد عبر الأعمدة */
        }

        .no-appointments-admin i {
            font-size: 3rem;
            display: block;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: var(--warning-color);
        }

        .no-appointments-admin p {
            font-size: 1.1rem;
            font-weight: 500;
        }
    </style>
@endsection

@section('title')
    المواعيد غير المؤكدة
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">المواعيد</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المواعيد
                    غير المؤكدة</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            {{-- أزرار للانتقال بين قوائم المواعيد --}}

            <a href="{{ route('admin.appointments.index2') }}" class="btn btn-outline-success btn-sm mr-2"><i
                    class="fas fa-check me-1"></i> المؤكدة</a>
            <a href="{{ route('admin.completed') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-history me-1"></i>
                المنتهية</a>
            <a href="{{ route('admin.cancelled') }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-times me-1"></i>
                الملغاة</a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="admin-appointments-container">
        <div class="page-title-container">
            <h1 class="page-title"><i class="fas fa-clock"></i> المواعيد بانتظار التأكيد</h1>
            {{-- يمكنك إضافة زر للانتقال لقائمة المواعيد المؤكدة هنا --}}
            {{-- <a href="{{ route('admin.appointments.confirmed') }}" class="btn btn-outline-success btn-sm">عرض المؤكدة</a> --}}
        </div>

        {{-- شبكة عرض البطاقات --}}
        <div class="admin-appointments-grid">
            @forelse($appointments as $appointment)
                <div class="admin-appointment-card animate__animated animate__fadeInUp"
                    style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <div class="card-main-info">
                        {{-- معلومات المريض --}}
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-user-injured"></i></span>
                            <div class="info-details">
                                <span class="info-label">اسم المريض</span>
                                <span class="info-value">{{ $appointment->name }}</span>
                            </div>
                        </div>
                        {{-- الإيميل والهاتف --}}
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-at"></i></span>
                            <div class="info-details">
                                <span class="info-label">البريد الإلكتروني</span>
                                <span class="info-value">{{ $appointment->email ?: '-' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-phone-alt"></i></span>
                            <div class="info-details">
                                <span class="info-label">الهاتف</span>
                                <span class="info-value">{{ $appointment->phone ?: '-' }}</span>
                            </div>
                        </div>
                        {{-- القسم والطبيب --}}
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-sitemap"></i></span>
                            <div class="info-details">
                                <span class="info-label">القسم</span>
                                <span class="info-value section-name">{{ $appointment->section->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-user-md"></i></span>
                            <div class="info-details">
                                <span class="info-label">الطبيب</span>
                                <span class="info-value doctor-name">{{ $appointment->doctor->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                        {{-- وقت الموعد المطلوب --}}
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-calendar-alt"></i></span>
                            <div class="info-details">
                                <span class="info-label">تاريخ ووقت الموعد المطلوب</span>
                                <span
                                    class="info-value font-weight-bold text-primary">{{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : 'غير محدد' }}</span>
                            </div>
                        </div>
                        {{-- الملاحظات --}}
                        @if ($appointment->notes)
                            <div class="notes-section">
                                <div class="info-label"><i class="fas fa-sticky-note"></i> ملاحظات المريض:</div>
                                <p class="info-value notes-text">{{ $appointment->notes }}</p>
                            </div>
                        @endif
                    </div>
                    {{-- أزرار الإجراءات --}}
                    <div class="card-actions">
                        <button class="action-btn btn-approve" data-toggle="modal"
                            data-target="#approval{{ $appointment->id }}"><i class="fas fa-check"></i> تأكيد الموعد
                        </button>
                        <button class="action-btn btn-delete" data-toggle="modal"
                            data-target="#Deleted{{ $appointment->id }}"><i class="fas fa-trash-alt"></i> حذف الطلب
                        </button>
                    </div>
                </div>
                {{-- تضمين المودالات هنا داخل الحلقة --}}
                @include('Dashboard.appointments.approval')
                @include('Dashboard.appointments.delete')
            @empty
                {{-- رسالة عند عدم وجود مواعيد --}}
                <div class="no-appointments-admin">
                    <i class="fas fa-inbox"></i>
                    <p>لا توجد مواعيد بانتظار التأكيد حالياً.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination (إذا كنت تستخدمه) --}}
        {{-- <div class="pagination-wrapper mt-4"> {{ $appointments->links() ?? '' }} </div> --}}

    </div>
@endsection

{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    @parent {{-- مهم للحفاظ على سكربتات الـ layout --}}
    {{-- استيراد مكتبات JS الضرورية (إذا لم تكن محملة في الـ layout) --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/jquery/jquery.min.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script> {{-- تأكد من وجود هذا الملف --}}
    {{-- (اختياري) Flatpickr JS للفلترة --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    {{-- كود مخصص لهذه الصفحة --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تهيئة Flatpickr لفلتر التاريخ (اختياري)
            if (document.getElementById('dateFilter')) {
                flatpickr("#dateFilter", {
                    dateFormat: "Y-m-d",
                    locale: "ar",
                    altInput: true,
                    altFormat: "j F Y"
                });
            }

            // (اختياري) كود فلترة الواجهة الأمامية الأساسي (يمكن حذفه إذا كانت الفلترة تتم بالـ Backend)
            const searchInput = document.getElementById('patientSearch');
            const statusFilter = document.getElementById(
            'statusFilter'); // على الرغم من أن الحالة هنا دائماً غير مؤكد
            const appointmentCards = document.querySelectorAll('.admin-appointment-card');

            function applyFilters() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
                appointmentCards.forEach(card => {
                    const patientNameElement = card.querySelector(
                        '.info-value:not(.doctor-name):not(.section-name)'); // محاولة تحديد اسم المريض
                    const patientName = patientNameElement ? patientNameElement.textContent.toLowerCase() :
                        '';
                    let showCard = true;
                    if (searchTerm && !patientName.includes(searchTerm)) showCard = false;

                    if (showCard) {
                        card.style.display = '';
                        card.classList.remove('animate__fadeOut');
                        card.classList.add('animate__fadeInUp');
                    } else {
                        card.classList.remove('animate__fadeInUp');
                        card.classList.add('animate__fadeOut');
                        setTimeout(() => {
                            if (!card.style.display == '') card.style.display = 'none';
                        }, 500);
                    }
                });
                // إظهار رسالة إذا لم توجد نتائج (اختياري)
            }
            if (searchInput) searchInput.addEventListener('input', applyFilters);
            // لا حاجة لربط statusFilter هنا لأننا نعرض غير المؤكدة فقط

            // التأكد من عمل أزرار فتح المودالات (تعتمد على Bootstrap JS المحمل في الـ layout)
            console.log('Admin appointments page loaded.');

        }); // نهاية DOMContentLoaded
    </script>
@endsection
