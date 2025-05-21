@extends('Dashboard.layouts.master')
@section('title', 'طلبات تجديد الوصفات للموافقة')

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
     {{-- إذا كنت ستستخدم Modals من Bootstrap، تأكد من تضمين JS الخاص بـ Bootstrap --}}
    <style>
        /* --- Root Variables & Basic Styling (يمكنك استخدام نفس الستايلات من adherence_dashboard) --- */
         :root { /* ... نفس متغيرات CSS ... */
             --bs-primary-rgb: 67, 97, 238; --bs-primary: rgb(var(--bs-primary-rgb));
             --bs-success-rgb: 25, 135, 84; --bs-success: rgb(var(--bs-success-rgb));
             /* ... الخ ... */
             --bs-body-bg: #f8f9fc;
         }
         body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: #5a5c69; }
         .card { border-radius: 0.45rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(0,0,0,0.05); border: 1px solid #e3e6f0; margin-bottom: 1.5rem; }
         .card-header { background-color: #fff; border-bottom: 1px solid #e3e6f0; padding: 0.9rem 1.25rem; }
         .card-title-css { font-weight: 600; color: #212529; font-size:1.1rem; }
         .table thead th { background-color: #f8f9fa !important; color: #5a5c69; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom-width: 2px !important; white-space: nowrap; padding: 0.75rem 1rem; }
         .table td, .table th { vertical-align: middle; padding: 0.75rem 1rem; }
         .table tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.04); }
         .patient-avatar-sm { width: 32px; height: 32px; border-radius: 50%; margin-right: 10px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 0 5px rgba(0,0,0,0.1);}
         .fw-500 {font-weight: 500!important;}
         .tx-primary {color: var(--bs-primary);}
         .text-warning{color: var(--bs-warning)!important}
         .text-danger{color:var(--bs-danger)!important}
         .action-buttons .btn { min-width: 80px; } /* لتوحيد عرض الأزرار قليلاً */

         /* Status Badges (نفس أنماطك السابقة) */
         .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.3px; min-width: 90px; text-align: center; display: inline-block; }
         .status-new { background-color: rgba(var(--bs-info-rgb), 0.15); color: rgb(var(--bs-info-rgb)); border: 1px solid rgba(var(--bs-info-rgb), 0.3); }
         .status-pending_review { background-color: rgba(var(--bs-warning-rgb), 0.2); color: #a17d06; border: 1px solid rgba(var(--bs-warning-rgb), 0.4); }
         .status-refill_requested { background-color: #ffe6cc; color: #c45100; border: 1px solid #ffdab3; }

         .request-reason-badge {
             font-size: 0.75rem;
             padding: 0.3em 0.6em;
         }
         .empty-state-container { text-align: center; padding: 2.5rem 1rem; background-color: var(--bs-light); border-radius: 0.45rem; border: 1px dashed #e3e6f0; }
         .empty-state-container i.empty-icon {  font-size: 3.5rem; color: var(--bs-primary); opacity: 0.6; margin-bottom: 1rem; display: block; }
         .empty-state-container h5 { font-weight: 600; color: var(--bs-dark); margin-bottom: 0.5rem; }
         .empty-state-container p { color: var(--bs-secondary); font-size: 0.95rem; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-user-shield text-warning me-2"></i>طلبات تحتاج قرارك</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="mt-1 tx-13">مراجعة طلبات تجديد الوصفات</span>
            </div>
        </div>
        {{-- لا يوجد زر إجراء رئيسي هنا عادةً --}}
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="card" data-aos="fade-up">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
             <h4 class="card-title-css mb-2 mb-md-0">
                 <i class="fas fa-list-check me-2"></i> قائمة طلبات تجديد الوصفات للموافقة
                 @if($approvalRequests->total() > 0)
                     <span class="badge bg-warning-transparent text-warning rounded-pill ms-2">{{ $approvalRequests->total() }} طلب</span>
                 @endif
             </h4>
             <form method="GET" action="{{ route('doctor.prescriptions.approvalRequests') }}" class="d-flex align-items-center" style="min-width: 250px;">
                 <input type="text" name="search_patient_approval" class="form-control form-control-sm me-2" placeholder="بحث باسم المريض أو هويته..." value="{{ $request->search_patient_approval }}">
                 <button type="submit" class="btn btn-sm btn-outline-primary ripple"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            @if($approvalRequests->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="min-width: 180px;">مقدم الطلب (المريض)</th>
                                <th style="min-width: 150px;">الوصفة الأصلية</th>
                                <th style="min-width: 150px;">الدواء/الملخص</th>
                                <th style="min-width: 120px;">تاريخ الطلب</th>
                                <th style="min-width: 150px;">سبب الحاجة للموافقة</th>
                                <th class="text-center" style="min-width: 220px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvalRequests as $idx => $prescription)
                                <tr>
                                    <td>{{ $idx + $approvalRequests->firstItem() }}</td>
                                    <td>
                                        @if($prescription->patient)
                                            <div class="d-flex align-items-center">
                                                 <img src="{{ $prescription->patient->image ? asset('Dashboard/img/patients/' . $prescription->patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png') }}" alt="patient" class="patient-avatar-sm">
                                                 <div>
                                                     <a href="{{ route('doctor.patient.details', $prescription->patient->id) }}" class="fw-500 text-primary d-block" data-bs-toggle="tooltip" title="عرض ملف المريض الكامل">
                                                         {{ $prescription->patient->name }}
                                                     </a>
                                                     <small class="text-muted d-block">
                                                         {{ $prescription->patient->gender == 1 ? 'ذكر' : ($prescription->patient->gender == 2 ? 'أنثى' : '') }}
                                                         {{ $prescription->patient->Date_Birth ? '، ' . $prescription->patient->Date_Birth->age . ' سنة' : '' }}
                                                     </small>
                                                 </div>
                                             </div>
                                        @else
                                             <span class="text-muted">مريض غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" class="fw-500" data-bs-toggle="tooltip" title="عرض تفاصيل الوصفة الأصلية">
                                            {{ $prescription->prescription_number }}
                                        </a>
                                        <small class="text-muted d-block">أنشئت: {{ $prescription->prescription_date->translatedFormat('d M Y') }}</small>
                                    </td>
                                    <td>
                                        @if($prescription->items->isNotEmpty())
                                            <small data-bs-toggle="tooltip" title="{{ $prescription->items->pluck('medication.name')->filter()->implode(', ') }}">
                                                {{ $prescription->items->first()->medication->name ?? 'غير محدد' }}
                                                @if($prescription->items_count > 1)
                                                    <span class="text-muted">(+{{ $prescription->items_count - 1 }})</span>
                                                @endif
                                            </small>
                                        @else
                                             <span class="text-muted small">(لا أدوية)</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $prescription->updated_at->translatedFormat('d M Y, h:i A') }}
                                    </td>
                                    <td>
                                         {{-- هنا يمكنك إضافة منطق لعرض سبب الحاجة للموافقة --}}
                                         @php
                                             $reason = "طلب تجديد من المريض"; // سبب افتراضي
                                             if ($prescription->items()->where('refills_allowed', '>', 0)->sum('refills_allowed') <= $prescription->items()->sum('refills_done')) {
                                                 $reason = "استنفاد مرات الإعادة المسموحة";
                                             } elseif ($prescription->is_chronic_prescription && !$prescription->items()->where('refills_allowed', '>', 0)->exists()){
                                                 $reason = "تجديد وصفة مزمنة (بدون عداد صرف)";
                                             }
                                             // يمكنك إضافة المزيد من الأسباب إذا كان الصيدلي يصعد طلبات
                                         @endphp
                                         <span class="badge bg-light border text-dark request-reason-badge" data-bs-toggle="tooltip" title="سبب ظهور هذا الطلب لمراجعة الطبيب">
                                             <i class="fas fa-info-circle text-primary me-1"></i>{{ $reason }}
                                         </span>
                                    </td>
                                    <td class="text-center action-buttons">
                                        {{-- أزرار الموافقة والرفض (ستحتاج لمسارات ودوال Modal) --}}
                                        <button type="button" class="btn btn-sm btn-success-gradient" data-bs-toggle="modal" data-bs-target="#approveRefillModal-{{ $prescription->id }}" data-bs-toggle="tooltip" title="الموافقة على طلب التجديد/إعادة الصرف">
                                            <i class="fas fa-check-circle me-1"></i> موافقة
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger-gradient" data-bs-toggle="modal" data-bs-target="#denyRefillModal-{{ $prescription->id }}" data-bs-toggle="tooltip" title="رفض طلب التجديد/إعادة الصرف">
                                            <i class="fas fa-times-circle me-1"></i> رفض
                                        </button>
                                        <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="عرض تفاصيل الوصفة الكاملة قبل اتخاذ قرار">
                                             <i class="fas fa-search-plus"></i>
                                         </a>
                                    </td>
                                </tr>
                                 {{-- هنا يمكنك إضافة Modals للموافقة والرفض --}}


                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($approvalRequests->hasPages())
                    <div class="mt-3 px-3 pb-3 d-flex justify-content-center">
                        {{ $approvalRequests->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @else
                <div class="empty-state-container p-5" data-aos="zoom-in-up">
                    <i class="fas fa-clipboard-check empty-icon text-success opacity-75"></i>
                    <h5 class="mt-3 fw-bold">لا توجد طلبات إعادة صرف تنتظر موافقتك حاليًا.</h5>
                    <p class="text-muted col-md-8 mx-auto mt-2">
                        عندما يقدم مريض طلبًا لتجديد وصفة تحتاج إلى مراجعتك، ستظهر هنا.
                    </p>
                </div>
            @endif
        </div>
    </div>


     {{-- --------- Modals للموافقة والرفض (مثال لكل وصفة) --------- --}}
     @foreach($approvalRequests as $prescription)
         {{-- Modal for Approving Refill/Renewal --}}
         <div class="modal fade" id="approveRefillModal-{{ $prescription->id }}" tabindex="-1" aria-labelledby="approveRefillModalLabel-{{ $prescription->id }}" aria-hidden="true">
             <div class="modal-dialog modal-lg">
                 <form action="{{-- route('doctor.prescriptions.approveRefill', $prescription->id) --}}" method="POST"> {{-- سننشئ هذا المسار لاحقًا --}}
                     @csrf
                     <div class="modal-content">
                         <div class="modal-header bg-success-gradient text-white">
                             <h5 class="modal-title" id="approveRefillModalLabel-{{ $prescription->id }}">موافقة على تجديد/إعادة صرف للوصفة: {{ $prescription->prescription_number }}</h5>
                             <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body">
                             <p><strong>المريض:</strong> {{ $prescription->patient->name ?? 'غير محدد' }}</p>
                             <p><strong>الوصفة الأصلية:</strong>
                                 @if($prescription->items->isNotEmpty())
                                     {{ $prescription->items->pluck('medication.name')->filter()->implode(', ') }}
                                 @else
                                     (لا أدوية مرفقة)
                                 @endif
                             </p>
                             <hr>
                             <div class="row">
                                 <div class="col-md-6 mb-3">
                                     <label for="new_refills_allowed-{{ $prescription->id }}" class="form-label">عدد مرات إعادة الصرف الجديدة المسموح بها (لكل بند مؤهل):</label>
                                     <input type="number" class="form-control" id="new_refills_allowed-{{ $prescription->id }}" name="new_refills_allowed_per_item" min="0" value="1" required>
                                     <small class="form-text text-muted">أدخل عدد المرات الإضافية التي يمكن للمريض إعادة صرف الأدوية المؤهلة في هذه الوصفة. (0 يعني لا إعادة صرف إضافية لهذه المرة ولكن قد يتم تجديد الوصفة).</small>
                                 </div>
                                 <div class="col-md-6 mb-3">
                                     <label for="new_prescription_expiry-{{ $prescription->id }}" class="form-label">تاريخ صلاحية جديد للوصفة (اختياري):</label>
                                     <input type="date" class="form-control flatpickr-modal" id="new_prescription_expiry-{{ $prescription->id }}" name="new_prescription_expiry_date" min="{{ now()->toDateString() }}">
                                     <small class="form-text text-muted">إذا كانت هذه موافقة على التجديد، حدد تاريخ انتهاء جديد للوصفة ككل.</small>
                                 </div>
                             </div>
                             <div class="mb-3">
                                 <label for="doctor_approval_notes-{{ $prescription->id }}" class="form-label">ملاحظات الطبيب (للصيدلي أو المريض - اختياري):</label>
                                 <textarea class="form-control" id="doctor_approval_notes-{{ $prescription->id }}" name="doctor_approval_notes" rows="3" placeholder="مثال: يرجى متابعة ضغط الدم، تم تعديل الجرعة..."></textarea>
                             </div>
                             <div class="form-check">
                                 <input class="form-check-input" type="checkbox" value="1" id="notify_patient_approval-{{ $prescription->id }}" name="notify_patient_on_approval" checked>
                                 <label class="form-check-label" for="notify_patient_approval-{{ $prescription->id }}">
                                     إرسال إشعار للمريض بالموافقة
                                 </label>
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إغلاق</button>
                             <button type="submit" class="btn btn-success"><i class="fas fa-check-circle me-1"></i> تأكيد الموافقة والتجديد</button>
                         </div>
                     </div>
                 </form>
             </div>
         </div>

         {{-- Modal for Denying Refill/Renewal --}}
         <div class="modal fade" id="denyRefillModal-{{ $prescription->id }}" tabindex="-1" aria-labelledby="denyRefillModalLabel-{{ $prescription->id }}" aria-hidden="true">
             <div class="modal-dialog">
                  <form action="{{-- route('doctor.prescriptions.denyRefill', $prescription->id) --}}" method="POST"> {{-- سننشئ هذا المسار لاحقًا --}}
                     @csrf
                     <div class="modal-content">
                         <div class="modal-header bg-danger-gradient text-white">
                             <h5 class="modal-title" id="denyRefillModalLabel-{{ $prescription->id }}">رفض طلب تجديد/إعادة صرف للوصفة: {{ $prescription->prescription_number }}</h5>
                             <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body">
                             <p><strong>المريض:</strong> {{ $prescription->patient->name ?? 'غير محدد' }}</p>
                             <div class="mb-3">
                                 <label for="denial_reason-{{ $prescription->id }}" class="form-label">سبب الرفض (مطلوب):</label>
                                 <textarea class="form-control" id="denial_reason-{{ $prescription->id }}" name="denial_reason" rows="4" placeholder="مثال: يحتاج المريض لمراجعة طبية قبل تجديد هذا الدواء، تم تغيير الخطة العلاجية..." required></textarea>
                                 <small class="form-text text-muted">هذا السبب سيتم تسجيله وقد يتم إرساله للمريض.</small>
                             </div>
                              <div class="form-check">
                                 <input class="form-check-input" type="checkbox" value="1" id="notify_patient_denial-{{ $prescription->id }}" name="notify_patient_on_denial" checked>
                                 <label class="form-check-label" for="notify_patient_denial-{{ $prescription->id }}">
                                     إرسال إشعار للمريض بالرفض والسبب
                                 </label>
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إغلاق</button>
                             <button type="submit" class="btn btn-danger"><i class="fas fa-times-circle me-1"></i> تأكيد الرفض</button>
                         </div>
                     </div>
                 </form>
             </div>
         </div>
     @endforeach
    {{-- --------- نهاية Modals --------- --}}


@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
     {{-- إذا استخدمت flatpickr في الـ modals --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>


    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 600,
                once: true,
                offset: 60
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, { boundary: document.body, container: 'body' });
            });

             // Initialize flatpickr for date inputs within modals
             flatpickr(".flatpickr-modal", {
                 dateFormat: "Y-m-d",
                 locale: "ar",
                 allowInput: true,
                 minDate: "today" //  لا يمكن تحديد تاريخ صلاحية في الماضي
             });


            @if (session('success'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>", type: "success", position: "top-center", autohide: true, timeout: 5000});
            @endif
            @if (session('error'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>",type: "error",position: "top-center",autohide: true,timeout: 7000});
            @endif
        });
    </script>
@endsection
