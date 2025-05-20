@extends('Dashboard.layouts.master')
@section('title', 'الوصفات المصروفة')

@section('css')
    @parent
    {{-- نفس الـ CSS المستخدم في index.blade.php للوصفات --}}
    <link href="{{ URL::asset('dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        /* ... (نفس أنماط CSS من index.blade.php للوصفات، مع تعديل الألوان إذا أردت) ... */
        :root {
            --bs-primary-rgb: 67, 97, 238; /* #4361ee */
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84;  /* #198754 */
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-info-rgb: 13, 202, 240;    /* #0dcaf0 */
            --bs-danger-rgb: 220, 53, 69;   /* #dc3545 */
            --bs-warning-rgb: 255, 193, 7;  /* #ffc107 */
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.75rem;
            --bs-card-box-shadow: 0 0.15rem 1.25rem rgba(58, 59, 69, 0.1);
            --bs-body-color: #525f7f;
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: var(--bs-body-color); }
        .card-custom { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); background-color: #fff; margin-bottom: 1.5rem; }
        .card-custom .card-header { background-color: rgb(var(--bs-light-rgb)); border-bottom: 1px solid var(--bs-border-color); padding: 1rem 1.25rem; }
        .card-custom .card-title { font-weight: 600; color: var(--bs-primary); margin-bottom: 0; }
        .card-custom .card-title i { margin-left: 0.5rem; }
        .table-prescriptions thead th { background-color: rgba(var(--bs-primary-rgb), 0.07); color: var(--bs-primary); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; padding: 0.8rem 1rem; border-bottom-width: 2px !important; white-space: nowrap; }
        .table-prescriptions tbody td { vertical-align: middle; padding: 0.8rem 1rem; font-size: 0.9rem; border-top: 1px solid var(--bs-border-color); }
        .table-prescriptions tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.03); }
        .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.75rem; font-weight: 500; }
        .status-dispensed { background-color: rgba(var(--bs-success-rgb), 0.15); color: rgb(var(--bs-success-rgb)); border:1px solid rgba(var(--bs-success-rgb),0.2); }
        .empty-state { text-align: center; padding: 2.5rem 1rem; background-color:rgb(var(--bs-light-rgb)); border-radius: var(--bs-card-border-radius); border: 1px dashed var(--bs-border-color); }
        .empty-state i { font-size: 3rem; color: var(--bs-border-color); margin-bottom: 1rem; display: block; }
        .empty-state h5 { font-weight: 600; color: var(--bs-dark-rgb); margin-bottom: 0.5rem; }
        .empty-state p { color: var(--bs-body-color); font-size: 0.95rem; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-pills text-primary me-2"></i>الصيدلية</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">الوصفات المصروفة</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="card card-custom filter-card" data-aos="fade-down">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>فلترة الوصفات المصروفة</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('pharmacy_employee.prescriptions.dispensed') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="search_dispensed" class="form-label">بحث برقم الوصفة / اسم المريض / الطبيب:</label>
                        <input type="text" name="search_term" id="search_dispensed" class="form-control"
                               placeholder="أدخل مصطلح البحث..." value="{{ $request->input('search_term') }}">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="date_filter_dispensed_page" class="form-label">تاريخ الصرف:</label>
                        <input type="text" name="date_filter_dispensed" id="date_filter_dispensed_page" class="form-control flatpickr-date"
                               placeholder="YYYY-MM-DD" value="{{ $request->input('date_filter_dispensed') }}">
                    </div>
                    <div class="col-lg-3 col-md-12">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> بحث</button>
                        @if(request()->hasAny(['search_term', 'date_filter_dispensed']))
                            <a href="{{ route('pharmacy_employee.prescriptions.dispensed') }}" class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                                <i class="fas fa-eraser me-1"></i> مسح
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header">
            <h4 class="card-title">قائمة الوصفات المصروفة ({{ $dispensedPrescriptions->total() }})</h4>
        </div>
        <div class="card-body p-0">
            @if($dispensedPrescriptions->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-prescriptions table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم الوصفة</th>
                                <th>اسم المريض</th>
                                <th>الطبيب المعالج</th>
                                <th>تاريخ الصرف</th>
                                <th class="text-center">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dispensedPrescriptions as $prescription)
                                <tr>
                                    <td>{{ $loop->iteration + $dispensedPrescriptions->firstItem() - 1 }}</td>
                                    <td><strong>{{ $prescription->prescription_number }}</strong></td>
                                    <td>{{ $prescription->patient->name ?? 'مريض غير معروف' }}</td>
                                    <td>{{ $prescription->doctor->name ?? 'طبيب غير محدد' }}</td>
                                    <td>{{ $prescription->dispensed_at ? $prescription->dispensed_at->translatedFormat('d M Y, H:i') : '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('pharmacy_employee.prescriptions.dispense.form', $prescription->id) }}" {{-- أو مسار لعرض تفاصيل الصرف --}}
                                           class="btn btn-outline-info btn-sm action-btn">
                                            <i class="fas fa-eye"></i> عرض التفاصيل
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($dispensedPrescriptions->hasPages())
                    <div class="p-3 border-top">
                        {{ $dispensedPrescriptions->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @else
                <div class="empty-state p-5">
                    <i class="fas fa-check-circle fa-3x text-success"></i>
                    <h5 class="mt-3">لا توجد وصفات مصروفة تطابق البحث.</h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    @parent
    {{-- نفس JS المستخدم في index.blade.php للوصفات --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <script>
        $(document).ready(function() {
            AOS.init({ duration: 700, once: true, offset: 50 });
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%', dir: "rtl", theme: "bootstrap-5", allowClear: true,
                dropdownParent: $(this).closest('.card-body')
            });
            flatpickr(".flatpickr-date", { dateFormat: "Y-m-d", locale: "ar", allowInput:true });

            @if(session('success'))
                notif({msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "top-center"});
            @endif
            @if(session('error'))
                notif({msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "top-center"});
            @endif
        });
    </script>
@endsection
