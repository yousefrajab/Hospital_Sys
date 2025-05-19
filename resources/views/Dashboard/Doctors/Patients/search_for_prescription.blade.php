@extends('Dashboard.layouts.master')

@section('title', 'البحث عن مريض لإنشاء وصفة طبية')

@section('css')
    @parent
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- Select2 (إذا كنت ستستخدمه للبحث المتقدم لاحقًا) --}}
    {{-- <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" /> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


    {{-- نفس أنماط CSS من index.blade.php مع تعديلات طفيفة --}}
    <style>
        :root {
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981;
            --admin-success: #22c55e;
            --admin-danger: #ef4444;
            --admin-warning: #f59e0b;
            --admin-info: #3b82f6;
            --admin-light: #f8f9fa;
            --admin-dark: #212529;
            --admin-bg: #f4f6f9;
            --admin-card-bg: #ffffff;
            --admin-text: #343a40;
            --admin-text-secondary: #6c757d;
            --admin-border-color: #dee2e6;
            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 0.5rem;
            --admin-radius-lg: 0.75rem;
            --admin-shadow: 0 2px 4px rgba(0,0,0,0.05);
            --admin-transition: all 0.25s ease-in-out;
            --admin-primary-rgb: 79, 70, 229;
            --admin-success-rgb: 34, 197, 94;
            --admin-danger-rgb: 239, 68, 68;
            --admin-info-rgb: 59, 130, 246;
             --admin-warning-rgb: 245,158,11;
        }
        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); font-size: 0.95rem; }
        .card { background-color: var(--admin-card-bg); border-radius: var(--admin-radius-lg); box-shadow: var(--admin-shadow); border: 1px solid var(--admin-border-color); margin-bottom: 1.75rem; }
        .card-header { background-color: transparent; border-bottom: 1px solid var(--admin-border-color); padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .card-header .card-title { font-weight: 600; color: var(--admin-text); margin-bottom: 0; font-size: 1.15rem; }
        .card-header .card-title i { margin-right: 0.6rem; color: var(--admin-primary); }

        .table-responsive { overflow-x: auto; }
        .table { width: 100%; margin-bottom: 0; color: var(--admin-text); /*border-collapse: separate; border-spacing: 0 0.5rem;*/ }
        .table thead th {
            background-color: var(--admin-light); color: var(--admin-text-secondary); font-weight: 600;
            font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 2px solid var(--admin-border-color); white-space: nowrap;
            padding: 1rem 1.25rem; text-align: right;
        }
        .table td, .table th { vertical-align: middle; padding: 1rem 1.25rem; border-top: 1px solid var(--admin-border-color); text-align: right; font-size: 0.9rem; }
        .table tbody tr { background-color: var(--admin-card-bg); transition: background-color 0.2s ease; }
        .table tbody tr:hover { background-color: #f1f5f9; }

        .patient-avatar-table {
            width: 45px; height: 45px; border-radius: var(--admin-radius-md);
            object-fit: cover; border: 2px solid var(--admin-border-color);
            margin-left: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .patient-name-link { color: var(--admin-primary); font-weight: 600; text-decoration: none; transition: var(--admin-transition); }
        .patient-name-link:hover { color: var(--admin-primary-dark); text-decoration: underline; }
        .patient-name-link + .text-muted { font-size: 0.8rem; }

        .badge { font-size: 0.8rem; padding: 0.45em 0.85em; border-radius: var(--admin-radius-sm); font-weight: 500; letter-spacing: 0.3px; }
        .badge-gender-male { background-color: rgba(var(--admin-info-rgb), 0.12); color: var(--admin-info); border: 1px solid rgba(var(--admin-info-rgb),0.2); }
        .badge-gender-female { background-color: rgba(236,72,153,0.12); color: #d946ef; border: 1px solid rgba(236,72,153,0.2); }
        .badge-blood { background-color: rgba(var(--admin-danger-rgb),0.12); color: var(--admin-danger); border: 1px solid rgba(var(--admin-danger-rgb),0.2); }
        .badge-blood i { transform: rotate(10deg); }

        .status-badge-table { font-weight: 500; }
        .status-badge-table.admitted { color: var(--admin-success); }
        .status-badge-table.not-admitted { color: var(--admin-text-secondary); }
        .status-badge-table i { font-size: 0.9em; }

        .action-btn-create-prescription {
            font-weight: 500;
            padding: 0.5rem 1rem; /* تعديل الحشو */
            font-size: 0.85rem;
            border-radius: var(--admin-radius-md);
        }
        .action-btn-create-prescription i { margin-right: 0.3rem; } /* RTL: margin-left */


        .filter-card .form-label { font-weight: 500; font-size: 0.85rem; margin-bottom: 0.3rem; color: var(--admin-text-secondary); }
        .form-control, .form-select {
            border-radius: var(--admin-radius-md); border: 1px solid var(--admin-border-color);
            padding: 0.65rem 1rem; /* زيادة الحشو قليلاً */ font-size: 0.95rem; /* تكبير الخط قليلاً */
            transition: var(--admin-transition); background-color: var(--admin-card-bg); color: var(--admin-text);
        }
        .form-control::placeholder { color: #adb5bd; }
        .form-control:focus, .form-select:focus { border-color: var(--admin-primary); box-shadow: 0 0 0 0.2rem rgba(var(--admin-primary-rgb),0.1); }
        .filter-card .btn-primary { background-color: var(--admin-primary); border-color: var(--admin-primary); transition: var(--admin-transition); }
        .filter-card .btn-primary:hover { background-color: var(--admin-primary-dark); border-color: var(--admin-primary-dark); }

        .diseases-list-inline { list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 0.3rem; font-size: 0.8rem; }
        .diseases-list-inline li {
            background-color: rgba(var(--admin-warning-rgb),0.1); color: var(--admin-warning);
            padding: 0.2em 0.5em; border-radius: var(--admin-radius-sm);
            border: 1px solid rgba(var(--admin-warning-rgb),0.2); white-space: nowrap;
        }
        .diseases-list-inline li.more-diseases a { color: var(--admin-primary); text-decoration: none; font-weight: 500; }
        .diseases-list-inline li.more-diseases a:hover { text-decoration: underline; }
        .no-chronic-diseases { font-size: 0.85rem; color: var(--admin-text-secondary); font-style: italic; }

        .pagination-container .pagination .page-item .page-link { color: var(--admin-primary); border-radius: var(--admin-radius-sm); margin: 0 2px; transition: var(--admin-transition); }
        .pagination-container .pagination .page-item.active .page-link { background-color: var(--admin-primary); border-color: var(--admin-primary); color: #fff; box-shadow: 0 2px 5px rgba(var(--admin-primary-rgb),0.3); }
        .pagination-container .pagination .page-item .page-link:hover { background-color: rgba(var(--admin-primary-rgb),0.1); border-color: rgba(var(--admin-primary-rgb),0.3); }
        .pagination-container .pagination .page-item.disabled .page-link { color: var(--admin-text-secondary); background-color: var(--admin-light); border-color: var(--admin-border-color); }

        .empty-state-container { text-align: center; padding: 3rem 1rem; background-color: var(--admin-light); border-radius: var(--admin-radius-md); border: 1px dashed var(--admin-border-color); }
        .empty-state-container i { font-size: 3.5rem; color: var(--admin-border-color); margin-bottom: 1rem; display: block; }
        .empty-state-container h5 { font-weight: 600; color: var(--admin-text); margin-bottom: 0.5rem; }
        .empty-state-container p { color: var(--admin-text-secondary); font-size: 0.95rem; }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-medical-alt fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto" style="font-weight: 600;">إنشاء وصفة طبية</h4>
                    <span class="text-muted mt-1 tx-13">/ البحث عن مريض</span>
                </div>
            </div>
        </div>
        {{-- يمكنك إضافة زر رجوع هنا إلى قائمة الوصفات إذا أردت --}}
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-outline-secondary btn-sm ripple-effect">
                 <i class="fas fa-list-ul me-1"></i> عرض وصفاتي
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    {{-- بطاقة الفلترة (البحث) --}}
    <div class="card filter-card animate__animated animate__fadeIn mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-search me-2" style="color: var(--admin-secondary);"></i>البحث عن مريض</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.patients.search_for_prescription') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-9 col-md-8">
                        <label for="search_term_prescription" class="form-label">أدخل اسم المريض، رقم الهوية، البريد أو الهاتف:</label>
                        <input type="text" name="search_term" id="search_term_prescription" class="form-control form-control-lg"
                               placeholder="ابدأ الكتابة للبحث..." value="{{ $request->input('search_term') }}">
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <button class="btn btn-primary btn-lg w-100" type="submit">
                            <i class="fas fa-search me-1"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- بطاقة جدول نتائج البحث --}}
    @if(isset($patients)) {{-- فقط إذا تم إجراء بحث أو تم تمرير المتغير --}}
        <div class="card patient-table-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users me-2" style="color: var(--admin-info);"></i>
                    نتائج البحث عن المرضى
                    @if($patients->total() > 0)
                        <span class="badge bg-light text-primary ms-2">{{ $patients->total() }} نتيجة</span>
                    @endif
                </h3>
            </div>
            <div class="card-body p-0">
                @if($patients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover text-md-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>اسم المريض</th>
                                    <th>الهوية / الهاتف</th>
                                    <th>العمر</th>
                                    <th>الجنس</th>
                                    <th>فصيلة الدم</th>
                                    <th>حالة الإقامة</th>
                                    <th>الأمراض المزمنة</th>
                                    <th class="text-center">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $index => $patient_result)
                                    <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $index * 0.03 }}s;">
                                        <td>{{ $patients->firstItem() + $index }}</td>
                                        <td>
                                            <img src="{{ $patient_result->image ? asset('Dashboard/img/patients/' . $patient_result->image->filename) : URL::asset('Dashboard/img/doctor_default.png') }}"
                                                 alt="{{ $patient_result->name }}" class="patient-avatar-table"
                                                 onerror="this.onerror=null; this.src='{{ URL::asset('Dashboard/img/default_patient_avatar.png') }}';">
                                        </td>
                                        <td>
                                            {{-- يمكنك جعل الاسم رابطًا لصفحة عرض تفاصيل المريض إذا كان الطبيب لديه صلاحية لذلك --}}
                                            <a href="{{ route('admin.Patients.show', $patient_result->id) }}" target="_blank" class="patient-name-link" data-bs-toggle="tooltip" title="عرض الملف الكامل للمريض (للمشرف)">
                                                {{ $patient_result->name }}
                                            </a>
                                            <small class="text-muted d-block">{{ $patient_result->email }}</small>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ $patient_result->national_id }}</span>
                                            <small class="text-muted">{{ $patient_result->Phone }}</small>
                                        </td>
                                        <td>
                                            @if ($patient_result->Date_Birth)
                                                {{ $patient_result->Date_Birth->age }} سنة
                                                <small class="text-muted d-block">({{ $patient_result->Date_Birth->translatedFormat('Y-m-d') }})</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($patient_result->Gender)
                                                <span class="badge {{ $patient_result->Gender == 1 ? 'badge-gender-male' : 'badge-gender-female' }}">
                                                    <i class="fas {{ $patient_result->Gender == 1 ? 'fa-mars' : 'fa-venus' }} me-1"></i>
                                                    {{ $patient_result->Gender == 1 ? 'ذكر' : 'أنثى' }}
                                                </span>
                                            @else <span class="text-muted">-</span> @endif
                                        </td>
                                        <td>
                                            @if ($patient_result->Blood_Group)
                                                <span class="badge badge-blood">
                                                    <i class="fas fa-tint me-1"></i> {{ $patient_result->Blood_Group }}
                                                </span>
                                            @else <span class="text-muted">-</span> @endif
                                        </td>
                                        <td class="status-badge-table {{ $patient_result->currentAdmission ? 'admitted' : 'not-admitted' }}">
                                            @if ($patient_result->currentAdmission)
                                                <i class="fas fa-hospital-user text-success me-1"></i> مقيم حاليًا
                                                @if ($patient_result->currentAdmission->bed && $patient_result->currentAdmission->bed->room)
                                                    <small class="d-block text-muted" style="font-size: 0.75rem;">
                                                        (قسم: {{ $patient_result->currentAdmission->bed->room->section->name ?? '-' }} |
                                                        غ: {{ $patient_result->currentAdmission->bed->room->room_number }} |
                                                        س: {{ $patient_result->currentAdmission->bed->bed_number }})
                                                    </small>
                                                @endif
                                            @else
                                                <i class="fas fa-user-check text-secondary me-1"></i> غير مقيم
                                            @endif
                                        </td>
                                        <td>
                                            @if ($patient_result->diagnosedChronicDiseases && $patient_result->diagnosedChronicDiseases->isNotEmpty())
                                                <ul class="diseases-list-inline">
                                                    @foreach ($patient_result->diagnosedChronicDiseases->take(2) as $diagnosedDisease)
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $diagnosedDisease->name }} - الحالة: {{ $diagnosedDisease->pivot->current_status ? (\App\Models\PatientChronicDisease::getStatuses()[$diagnosedDisease->pivot->current_status] ?? $diagnosedDisease->pivot->current_status) : 'غير محددة' }}">
                                                            <i class="fas fa-notes-medical fa-xs me-1 text-danger"></i>
                                                            {{ Str::limit($diagnosedDisease->name, 18) }}
                                                        </li>
                                                    @endforeach
                                                    @if ($patient_result->diagnosedChronicDiseases->count() > 2)
                                                        <li class="more-diseases">
                                                            <a href="{{ route('admin.Patients.show', $patient_result->id) }}#chronic-diseases-section" target="_blank" class="text-primary" data-bs-toggle="tooltip" title="عرض كل الأمراض (+{{ $patient_result->diagnosedChronicDiseases->count() - 2 }})">
                                                                <i class="fas fa-ellipsis-h"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            @else
                                                <span class="no-chronic-diseases">لا يوجد</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('doctor.prescriptions.create', ['patient_id' => $patient_result->id]) }}"
                                               class="btn btn-success btn-sm action-btn-create-prescription px-3">
                                                <i class="fas fa-file-signature me-1"></i> إنشاء وصفة
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($patients instanceof \Illuminate\Pagination\LengthAwarePaginator && $patients->hasPages())
                        <div class="mt-3 p-3 d-flex justify-content-center pagination-container border-top">
                            {{ $patients->appends($request->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @elseif($request->filled('search_term'))
                    <div class="empty-state-container p-4 mt-4">
                        <i class="fas fa-user-times"></i>
                        <h5>لم يتم العثور على نتائج</h5>
                        <p class="text-muted">لا يوجد مرضى يطابقون مصطلح البحث "<strong>{{ $request->input('search_term') }}</strong>". يرجى التأكد من البيانات والمحاولة مرة أخرى.</p>
                    </div>
                @else
                     <div class="empty-state-container p-4 mt-4">
                        <i class="fas fa-search-plus"></i>
                        <h5>ابدأ البحث عن مريض</h5>
                        <p class="text-muted">استخدم حقل البحث أعلاه للعثور على المريض المطلوب لإنشاء وصفة طبية له.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    {{-- <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script> --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 600,
                once: true,
                offset: 50 // شغل الأنيميشن عند اقتراب العنصر
            });

            // تهيئة Tooltips إذا كنت تستخدم Bootstrap 5
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            // إظهار رسائل NotifIt (إذا احتجت إليها هنا)
            @if(session('success'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "top-center" });
            @endif
            @if(session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "top-center" });
            @endif
        });
    </script>
@endsection
