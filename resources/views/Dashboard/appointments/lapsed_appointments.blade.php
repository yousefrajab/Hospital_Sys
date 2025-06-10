@extends('Dashboard.layouts.master')

{{-- ========================== CSS Section ========================== --}}
@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="{{URL::asset('Dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{--  <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css"> --}} {{-- Arabic for Flatpickr --}}

    <style>
         :root {
            --primary-color: #007bff; --secondary-color: #6c757d;
            --light-bg: #f8f9fa; --border-color: #dee2e6; --white-color: #ffffff;
            --success-color: #28a745; --warning-color: #ffc107; --danger-color: #dc3545; --info-color: #17a2b8;
            --text-dark: #343a40; --text-muted: #6c757d;
            --lapsed-color: #fd7e14; /* برتقالي للمواعيد الفائتة */
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, .15);
            --bs-card-border-radius: 0.5rem; /* Consistency */
        }
        body { background: var(--light-bg); font-family: 'Cairo', sans-serif; color: var(--text-dark); }
        .admin-appointments-container { padding: 1.5rem; }
        .page-title-container { margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color); }
        .page-title { font-size: 1.75rem; font-weight: 600; color: var(--text-dark); display: flex; align-items: center; gap: 0.75rem; }
        .page-title i { color: var(--lapsed-color); font-size: 1.5rem; }

        .admin-appointments-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem; }
        .admin-appointment-card {
            background: var(--white-color); border-radius: var(--bs-card-border-radius);
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            border-left: 5px solid var(--lapsed-color);
            padding: 0; /* Remove padding, will add to inner divs */
            display: flex; flex-direction: column;
            transition: all 0.25s ease-in-out;
        }
        .admin-appointment-card:hover { transform: translateY(-4px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); }

        .card-main-info { padding: 1.25rem; flex-grow: 1; }
        .info-row { display: flex; align-items: flex-start; margin-bottom: 0.75rem; }
        .info-icon { width: 22px; text-align: center; margin-left: 12px; color: var(--primary-color); opacity: 0.7; font-size: 1em; flex-shrink: 0; margin-top:3px;}
        .info-details { display: flex; flex-direction: column; line-height:1.4; }
        .info-label { font-size: 0.7rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 0.9rem; font-weight: 500; color: var(--text-dark); word-break: break-word; }
        .info-value.doctor-name, .info-value.section-name { font-weight: 600; color: var(--secondary-color); }
        .appointment-time-lapsed .info-value { color: var(--lapsed-color); font-weight: bold; text-decoration: line-through; opacity: 0.8; }
        .info-row .text-danger.fw-bold { color: var(--danger-color)!important;}

        .card-actions {
            background-color: #fcfcfc;
            padding: 0.75rem 1.25rem; border-radius: 0 0 var(--bs-card-border-radius) var(--bs-card-border-radius);
            display: flex; justify-content: space-between;
            align-items: center;
            gap: 0.5rem; margin-top: 0.5rem; /* Reduced margin */
            border-top: 1px solid var(--border-color);
        }
        .action-btn { border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-badge-current-lapsed {
            background-color: rgba(var(--lapsed-color-rgb, 253, 126, 20), 0.1);
            color: var(--lapsed-color);
            font-size: 0.75rem; font-weight: 500; padding: 0.35rem 0.7rem;
            border-radius: 50px; display: inline-flex; align-items: center; gap: 0.3rem;
        }
        :root { --lapsed-color-rgb: 253, 126, 20; }

        .btn-mark-completed { background-color: var(--success-color); color: white; }
        .btn-mark-completed:hover { background-color: #218838; }
        .btn-admin-cancel { background-color: var(--info-color); color: white; }
        .btn-admin-cancel:hover { background-color: #138496;}

        .no-appointments-admin { text-align: center; padding: 3rem 1rem; background: var(--white-color); border-radius: var(--bs-card-border-radius); box-shadow: var(--card-shadow); color: var(--text-muted); grid-column: 1 / -1; }
        .no-appointments-admin i { font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.4; color: var(--lapsed-color); }
        .no-appointments-admin p { font-size: 1rem; font-weight: 500; }

         .pagination-wrapper { margin-top: 2rem; display: flex; justify-content: center; }
         .page-item.active .page-link { background-color: var(--primary-color); border-color: var(--primary-color); }
         .page-link { color: var(--primary-color); }
         .page-link:hover { color: var(--secondary-color); }
         .btn-outline-primary, .btn-outline-success, .btn-outline-secondary, .btn-outline-danger, .btn-outline-warning { font-size: 0.8rem; padding: 0.3rem 0.75rem;}
    </style>
@endsection

@section('title')
    المواعيد الفائتة للمتابعة
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title tx-15 mb-0 my-auto">إدارة المواعيد</h4>
                <span class="text-muted mt-1 tx-12 mr-2 mb-0">/ قائمة المواعيد الفائتة</span>
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
             <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="tooltip" title="المواعيد التي تنتظر تأكيدك"><i class="fas fa-hourglass-start me-1"></i> غير المؤكدة</a>
             <a href="{{ route('admin.appointments.index2') }}" class="btn btn-outline-success btn-sm me-2" data-bs-toggle="tooltip" title="المواعيد المؤكدة والقادمة"><i class="fas fa-calendar-check me-1"></i> المؤكدة</a>
             <a href="{{ route('admin.completed') }}" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="tooltip" title="سجل المواعيد المكتملة"><i class="fas fa-history me-1"></i> المنتهية</a>
             <a href="{{ route('admin.cancelled') }}" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="سجل المواعيد الملغاة"><i class="fas fa-ban me-1"></i> الملغاة</a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="admin-appointments-container">
         <div class="page-title-container">
            <h1 class="page-title"><i class="fas fa-calendar-times"></i> المواعيد الفائتة التي تحتاج إلى تحديث حالة</h1>
        </div>

        <form method="GET" action="{{ route('admin.appointments.lapsed') }}" class="mb-4 p-3 bg-white border rounded shadow-sm">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <label for="search_lapsed" class="form-label visually-hidden">بحث:</label>
                    <input type="text" class="form-control form-control-sm" id="search_lapsed" name="search_lapsed" placeholder="بحث باسم المريض، الطبيب، أو ملاحظة..." value="{{ $request->search_lapsed ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="date_lapsed_filter" class="form-label visually-hidden">تاريخ الموعد:</label>
                    <input type="text" class="form-control form-control-sm flatpickr-date" id="date_lapsed_filter" name="date_lapsed_filter" placeholder="فلترة بتاريخ الموعد" value="{{ $request->date_lapsed_filter ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fas fa-filter"></i> فلترة</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.appointments.lapsed') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="fas fa-redo"></i> مسح</a>
                </div>
            </div>
        </form>

        <div class="admin-appointments-grid">
            {{-- استخدام $lapsedAppointmentsPaginated كما هو مُمرر من الكنترولر المُعدل --}}
            @forelse ($lapsedAppointments as $appointment_lap)
                <div class="admin-appointment-card animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 50 }}ms;">
                    <div class="card-main-info">
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-user-injured fa-fw"></i></span>
                            <div class="info-details">
                                <span class="info-label">المريض</span>
                                <span class="info-value">{{ $appointment_lap->patient->name ?? ($appointment_lap->name ?? 'غير معروف') }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-user-md fa-fw"></i></span>
                            <div class="info-details">
                                <span class="info-label">الطبيب</span>
                                <span class="info-value doctor-name">{{ $appointment_lap->doctor->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-clinic-medical fa-fw"></i></span> {{-- تغيير الأيقونة للقسم --}}
                            <div class="info-details">
                                <span class="info-label">القسم</span>
                                <span class="info-value section-name">{{ $appointment_lap->section->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="info-row appointment-time-lapsed">
                            <span class="info-icon"><i class="fas fa-calendar-alt fa-fw"></i></span>
                            <div class="info-details">
                                <span class="info-label">وقت الموعد الأصلي</span>
                                <span class="info-value">{{ $appointment_lap->appointment ? $appointment_lap->appointment->translatedFormat('l، j M Y - H:i') : '-' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-phone fa-fw"></i></span>
                            <div class="info-details">
                                <span class="info-label">هاتف التواصل</span>
                                <span class="info-value">{{ $appointment_lap->patient->Phone ?? ($appointment_lap->phone ?? '-') }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-icon"><i class="fas fa-stopwatch fa-fw text-danger"></i></span>
                            <div class="info-details">
                                <span class="info-label">فات منذ</span>
                                <span class="info-value text-danger fw-bold">{{ $appointment_lap->appointment ? $appointment_lap->appointment->diffForHumans(null, true, false, 2) : '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                         <span class="status-badge-current-lapsed"><i class="fas fa-info-circle me-1"></i> الحالة الحالية: {{ $appointment_lap->type }}</span>
                         <div class="btn-group">
                            {{-- زر تحديد كمكتمل (سيفتح مودال) --}}
                            {{-- <button type="button" class="action-btn btn-mark-completed" data-bs-toggle="modal" data-bs-target="#completeAppointmentModal_{{$appointment_lap->id}}" title="تحديد الموعد كمكتمل"><i class="fas fa-check-circle"></i> مكتمل</button> --}}
                            {{-- زر إلغاء الموعد (سيفتح مودال) --}}
                            <button type="button" class="action-btn btn-admin-cancel" data-toggle="modal" data-target="#cancelModal{{$appointment_lap->id}}" title="إلغاء الموعد من قبل الإدارة"><i class="fas fa-ban"></i> إلغاء إداري</button>
                            {{-- يمكنك إضافة زر "لم يحضر" هنا بنفس الطريقة إذا كان لديك هذه الحالة --}}
                        </div>
                    </div>
                </div>
                {{-- تضمين مودالات تحديث الحالة --}}
                {{-- @include('Dashboard.appointments.modals.admin-complete-modal', ['appointment' => $appointment_lap])
                @include('Dashboard.appointments.modals.admin-cancel-modal', ['appointment' => $appointment_lap]) --}}
                @include('Dashboard.appointments.cancel_modal', ['appointment' => $appointment_lap])
            @empty
                 <div class="no-appointments-admin">
                      <i class="far fa-calendar-check text-success"></i>
                     <p>لا توجد مواعيد فائتة تحتاج لمراجعة حالياً. كل المواعيد مُحدَّثة!</p>
                 </div>
            @endforelse
        </div>

         @if ($lapsedAppointments->hasPages())
            <div class="pagination-wrapper mt-4">
                {{-- استخدام اسم المتغير الصحيح --}}
                {{ $lapsedAppointments->appends($request->query())->links() }}
            </div>
        @endif
    </div>

@endsection

@section('js')
    @parent
    {{--  --}}
@endsection
