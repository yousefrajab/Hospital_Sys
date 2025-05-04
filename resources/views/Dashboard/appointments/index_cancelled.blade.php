@extends('Dashboard.layouts.master')

{{-- ========================== CSS Section ========================== --}}
@section('css')
    {{-- نفس استيرادات CSS لـ index_completed --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="{{URL::asset('Dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css">

    {{-- نفس الأنماط مع تعديل لون البطاقة الملغاة وزر الحذف --}}
    <style>
         :root { /* لوحة الألوان */
            --primary-color: #4A90E2; --secondary-color: #4A4A4A; --accent-color: #50E3C2;
            --light-bg: #f9fbfd; --border-color: #e5e9f2; --white-color: #ffffff;
            --success-color: #2ecc71; --warning-color: #f39c12; --danger-color: #e74c3c; --info-color: #3498db;
            --text-dark: #34495e; --text-muted: #95a5a6; --text-cancelled: #c0392b; /* لون للحالة الملغاة */
            --card-shadow: 0 8px 25px rgba(140, 152, 164, 0.1);
        }
        body { background: var(--light-bg); font-family: 'Cairo', sans-serif; }
        .admin-appointments-container { padding: 1.5rem; }
        .page-title-container { margin-bottom: 2rem; }
        .page-title { font-size: 1.6rem; font-weight: 700; color: var(--secondary-color); display: flex; align-items: center; gap: 0.75rem; }
        .page-title i { color: var(--danger-color); } /* تغيير لون أيقونة العنوان */

        /* تصميم البطاقة */
        .admin-appointments-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }
        .admin-appointment-card { background: var(--white-color); border-radius: 12px; box-shadow: var(--card-shadow); border-left: 5px solid var(--danger-color); /* خط أحمر للملغي */ padding: 1.25rem; display: flex; flex-direction: column; opacity: 0.75; /* جعلها باهتة أكثر */ transition: all 0.3s ease; }
        .admin-appointment-card:hover { opacity: 1; transform: translateY(-3px); box-shadow: 0 10px 28px rgba(140, 152, 164, 0.15); }

        .card-main-info { flex-grow: 1; }
        .info-row { display: flex; align-items: flex-start; margin-bottom: 0.8rem; }
        .info-icon { width: 25px; text-align: center; margin-left: 10px; color: var(--primary-color); opacity: 0.9; font-size: 1.1em; flex-shrink: 0; margin-top: 2px; }
        .info-details { display: flex; flex-direction: column; }
        .info-label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 2px; }
        .info-value { font-size: 0.95rem; font-weight: 500; color: var(--text-dark); word-break: break-word; }
        .info-value.doctor-name, .info-value.section-name { font-weight: 600; color: var(--secondary-color); }
        .appointment-time .info-value { text-decoration: line-through; color: var(--text-cancelled); } /* شطب الوقت الملغي */

        /* الأزرار */
        .card-actions { background-color: #f8f9fa; padding: 0.75rem 1.25rem; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem; border-top: 1px solid var(--border-color); }
        .action-btn { border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.3rem; }
        .status-badge-cancelled { background-color: rgba(231, 76, 60, 0.1); color: #c0392b; font-size: 0.8rem; font-weight: 600; padding: 0.4rem 0.8rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 0.3rem; margin-left: auto; /* لدفعها لليمين */}
        .btn-delete-permanent { background-color: var(--danger-color); color: white; }
        .btn-delete-permanent:hover { background-color: #c82333; box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3); }

        /* Placeholder */
        .no-appointments-admin { text-align: center; padding: 3rem 1rem; background: var(--white-color); border-radius: 12px; box-shadow: var(--card-shadow); color: var(--text-muted); grid-column: 1 / -1; }
        .no-appointments-admin i { font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.5; color: var(--danger-color); }
        .no-appointments-admin p { font-size: 1.1rem; font-weight: 500; }

         /* Pagination */
         .pagination-wrapper { margin-top: 2rem; display: flex; justify-content: center; }
         .pagination { box-shadow: none; }
         .page-item.active .page-link { background-color: var(--primary-color); border-color: var(--primary-color); }
         .page-link { color: var(--primary-color); }
         .page-link:hover { color: var(--secondary-color); }
    </style>
@endsection

@section('title')
    المواعيد الملغاة
@endsection

@section('page-header')
    <!-- breadcrumb -->
     <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">المواعيد</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المواعيد الملغاة</span>
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
             <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-warning btn-sm mr-2"><i class="fas fa-clock me-1"></i> غير المؤكدة</a>
             <a href="{{ route('admin.appointments.index2') }}" class="btn btn-outline-success btn-sm mr-2"><i class="fas fa-check me-1"></i> المؤكدة</a>
             <a href="{{ route('admin.completed') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-history me-1"></i> المنتهية</a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="admin-appointments-container">
         <div class="page-title-container">
            <h1 class="page-title"><i class="fas fa-ban"></i> المواعيد الملغاة</h1>
        </div>

        {{-- قسم الفلترة --}}
        {{-- <div class="filter-controls animate__animated animate__fadeIn mb-4"> ... </div> --}}

        <div class="admin-appointments-grid">
            @forelse ($appointments as $appointment)
                <div class="admin-appointment-card animate__animated animate__fadeInUp" data-status="{{ $appointment->type }}" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <div class="card-main-info">
                        {{-- معلومات المريض --}}
                        <div class="info-row"> <span class="info-icon"><i class="fas fa-user"></i></span> <div class="info-details"> <span class="info-label">اسم المريض</span> <span class="info-value">{{ $appointment->name }}</span> </div> </div>
                        {{-- القسم والطبيب --}}
                        <div class="info-row"> <span class="info-icon"><i class="fas fa-sitemap"></i></span> <div class="info-details"> <span class="info-label">القسم</span> <span class="info-value section-name">{{ $appointment->section->name ?? '-' }}</span> </div> </div>
                        <div class="info-row"> <span class="info-icon"><i class="fas fa-user-md"></i></span> <div class="info-details"> <span class="info-label">الطبيب</span> <span class="info-value doctor-name">{{ $appointment->doctor->name ?? '-' }}</span> </div> </div>
                        {{-- وقت الموعد الملغي (مع شطب) --}}
                        <div class="info-row appointment-time"> <span class="info-icon"><i class="fas fa-calendar-times"></i></span> <div class="info-details"> <span class="info-label">تاريخ ووقت الموعد (ملغي)</span> <span class="info-value">{{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : '-' }}</span> </div> </div>
                        {{-- الهاتف --}}
                        <div class="info-row"> <span class="info-icon"><i class="fas fa-phone-slash"></i></span> <div class="info-details"> <span class="info-label">الهاتف</span> <span class="info-value">{{ $appointment->phone ?: '-' }}</span> </div> </div>
                         {{-- تاريخ الإلغاء (تاريخ آخر تحديث) --}}
                         <div class="info-row"> <span class="info-icon"><i class="fas fa-history"></i></span> <div class="info-details"> <span class="info-label">تاريخ الإلغاء</span> <span class="info-value text-muted small">{{ $appointment->updated_at ? $appointment->updated_at->diffForHumans() : '-' }}</span> </div> </div>
                    </div>
                    {{-- فوتر البطاقة --}}
                    <div class="card-actions">
                         <span class="status-badge-cancelled"><i class="fas fa-ban me-1"></i> ملغي</span>
                         {{-- زر الحذف النهائي (يفتح مودال) --}}
                         <button class="action-btn btn-delete-permanent" data-toggle="modal" data-target="#Deleted{{$appointment->id}}"><i class="fas fa-trash-alt"></i> حذف نهائي</button>
                    </div>
                </div>
                 {{-- تضمين مودال الحذف --}}
                  @include('Dashboard.appointments.delete')
            @empty
                 <div class="no-appointments-admin">
                      <i class="fas fa-ban"></i>
                     <p>لا توجد مواعيد ملغاة لعرضها حالياً.</p>
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
    {{-- Flatpickr JS (للفلترة) --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    {{-- Notify JS (للرسائل) --}}
    <script src="{{URL::asset('Dashboard/plugins/notify/js/notifIt.js')}}"></script>
     {{-- Bootstrap JS (للـ Modals) --}}
     {{-- <script src="{{ URL::asset('Dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}

    <script>
         document.addEventListener('DOMContentLoaded', function() {
             // تهيئة Flatpickr لفلتر التاريخ (اختياري)
             if(document.getElementById('dateFilter')) {
                flatpickr("#dateFilter", { dateFormat: "Y-m-d", locale: "ar", altInput: true, altFormat: "j F Y"});
             }
              // يمكنك إضافة كود JS للفلترة هنا إذا احتجت
              console.log('Cancelled appointments page loaded.');
         });
     </script>
@endsection
