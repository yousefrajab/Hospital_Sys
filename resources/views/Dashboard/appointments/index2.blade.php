@extends('Dashboard.layouts.master')

{{-- ========================== CSS Section ========================== --}}
@section('css')
    {{-- استيراد المكتبات الأساسية --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- Notify CSS (إذا كنت ستعرض رسائل هنا) --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- Flatpickr CSS (إذا أضفت فلتر تاريخ) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css">

    {{-- *** نفس أنماط البطاقات من صفحة مواعيد الطبيب *** --}}
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

        /* فلترة وبحث (اختياري) */
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
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.3rem;
        }

        .form-control-filter,
        .form-select-filter {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            width: 100%;
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

        /* تلوين حسب الحالة (سيتم تطبيق success هنا لأنها مواعيد مؤكدة) */
        .admin-appointment-card[data-status="مؤكد"] {
            border-left-color: var(--success-color);
        }

        .admin-appointment-card[data-status="غير مؤكد"] {
            border-left-color: var(--warning-color);
        }

        .admin-appointment-card[data-status="منتهي"] {
            border-left-color: var(--text-muted);
        }

        .admin-appointment-card[data-status="ملغي"] {
            border-left-color: var(--danger-color);
        }

        .card-main-info {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border-color);
            flex-grow: 1;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
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

        /* لا نحتاج قسم ملاحظات هنا غالباً */

        .card-actions {
            background-color: #f8f9fa;
            padding: 0.75rem 1.25rem;
            border-radius: 0 0 12px 12px;
            display: flex;
            justify-content: flex-end;
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

        /* يمكنك إضافة أزرار أخرى هنا إذا لزم الأمر (مثل عرض التفاصيل الكاملة) */
        .btn-details {
            background-color: var(--info-color);
            color: white;
        }

        .btn-details:hover {
            background-color: #2980b9;
            box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
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
        }

        .no-appointments-admin i {
            font-size: 3rem;
            display: block;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: var(--success-color);
        }

        /* تغيير لون الأيقونة */
        .no-appointments-admin p {
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Pagination */
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
    </style>
@endsection

@section('title')
    المواعيد المؤكدة
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">المواعيد</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المواعيد
                    المؤكدة</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-primary btn-sm"> {{-- زر للعودة لغير المؤكدة --}}
                <i class="fas fa-clock me-1"></i> عرض غير المؤكدة
            </a>
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
            <h1 class="page-title"><i class="fas fa-calendar-check"></i> المواعيد المؤكدة</h1>
        </div>

        {{-- قسم الفلترة والبحث (يمكن تفعيله لاحقاً) --}}
        {{-- <div class="filter-controls animate__animated animate__fadeIn mb-4"> ... </div> --}}

        <div class="admin-appointments-grid">
            @forelse ($appointments as $appointment)
                <div class="admin-appointment-card animate__animated animate__fadeInUp"
                    data-status="{{ $appointment->type }}" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <div class="card-main-info">
                        {{-- معلومات المريض --}}
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-user"></i></span>
                            <div class="info-details">
                                <span class="info-label">اسم المريض</span>
                                <span class="info-value">{{ $appointment->name }}</span>
                            </div>
                        </div>
                        {{-- القسم والطبيب --}}
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-sitemap"></i></span>
                            <div class="info-details">
                                <span class="info-label">القسم</span>
                                <span class="info-value section-name">{{ $appointment->section->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-user-md"></i></span>
                            <div class="info-details">
                                <span class="info-label">الطبيب</span>
                                <span class="info-value doctor-name">{{ $appointment->doctor->name ?? '-' }}</span>
                            </div>
                        </div>
                        {{-- وقت الموعد المؤكد --}}
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-calendar-check"></i></span>
                            <div class="info-details">
                                <span class="info-label">تاريخ ووقت الموعد المؤكد</span>
                                <span
                                    class="info-value font-weight-bold text-success">{{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : '-' }}</span>
                            </div>
                        </div>
                        {{-- الهاتف (اختياري) --}}
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-phone-volume"></i></span>
                            <div class="info-details">
                                <span class="info-label">الهاتف</span>
                                <span class="info-value">{{ $appointment->phone ?: '-' }}</span>
                            </div>
                        </div>
                        {{-- الإيميل (اختياري) --}}
                        {{-- <div class="info-row"> ... </div> --}}
                    </div>
                    {{-- أزرار الإجراءات للمواعيد المؤكدة --}}
                    <div class="card-actions">
                        {{-- يمكنك إضافة زر لعرض تفاصيل أكثر أو الانتقال لملف المريض --}}
                        {{-- <button class="action-btn btn-details"><i class="fas fa-eye"></i> تفاصيل</button> --}}
                        {{-- زر الحذف للمواعيد المؤكدة (قد لا يكون مرغوباً به) --}}
                        {{-- <button class="action-btn btn-delete" data-toggle="modal"
                            data-target="#Deleted{{ $appointment->id }}"><i class="fas fa-trash-alt"></i> حذف</button> --}}
                        {{-- <span class="status-badge-confirmed"><i class="fas fa-check-circle me-1"></i> مؤكد</span> --}}
                        {{-- زر إلغاء جديد للأدمن (يفتح مودال أو يستخدم AJAX) --}}
                        <button class="action-btn btn-delete" data-toggle="modal"
                            data-target="#cancelModal{{ $appointment->id }}"> {{-- تغيير data-target --}}
                            <i class="fas fa-times"></i> إلغاء الموعد
                        </button>
                        <span class="badge bg-light text-success p-2"> <i class="fas fa-check-circle"></i> موعد مؤكد</span>
                    </div>
                </div>
                @include('Dashboard.appointments.cancel_modal')
                {{-- تضمين مودال الحذف (إذا كنت ستبقي على زر الحذف) --}}
                @include('Dashboard.appointments.delete')
            @empty
                <div class="no-appointments-admin">
                    <i class="fas fa-calendar-check"></i>
                    <p>لا توجد مواعيد مؤكدة لعرضها حالياً.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination
         @if ($appointments->hasPages())
            <div class="pagination-wrapper mt-4">
                {{ $appointments->links() }}
            </div>
        @endif --}}

    </div>

    {{-- المودالات (تبقى كما هي إذا كنت تستخدمها) --}}
    {{-- @if (isset($appointments) && $appointments->isNotEmpty())
        @foreach ($appointments as $appointment)
             @include('Dashboard.appointments.delete')
             {{-- لا حاجة لمودال approval هنا --}}
    {{-- @endforeach
     @endif --}}
@endsection

{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    @parent
    {{-- مكتبات JS (Flatpickr للفلترة، Notify للرسائل، Bootstrap للـ Modals) --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    {{-- <script src="{{ URL::asset('Dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}

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

            // (اختياري) كود فلترة الواجهة الأمامية بالبحث (يمكن تحسينه أو حذفه)
            const searchInput = document.getElementById('patientSearch');
            const appointmentCards = document.querySelectorAll('.admin-appointment-card');
            if (searchInput && appointmentCards.length > 0) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    appointmentCards.forEach(card => {
                        const patientNameElement = card.querySelector(
                            '.info-value:not(.doctor-name):not(.section-name)');
                        const patientName = patientNameElement ? patientNameElement.textContent
                            .toLowerCase() : '';
                        const showCard = !searchTerm || patientName.includes(searchTerm);

                        if (showCard) {
                            card.style.display = '';
                            card.classList.remove('animate__fadeOut');
                            card.classList.add('animate__fadeInUp');
                        } else {
                            card.classList.remove('animate__fadeInUp');
                            card.classList.add('animate__fadeOut');
                            setTimeout(() => {
                                if (!card.style.display == '') card.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            }

            console.log('Admin confirmed appointments page loaded.');
        });
    </script>
@endsection
