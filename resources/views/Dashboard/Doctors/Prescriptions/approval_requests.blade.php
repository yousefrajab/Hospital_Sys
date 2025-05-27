@extends('Dashboard.layouts.master')
@section('title', 'طلبات تجديد الوصفات للموافقة')

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"> {{-- لـ flatpickr في الـ modal --}}
    <style>
        /* --- Root Variables & Basic Styling --- */
        :root {
            --bs-primary-rgb: 67, 97, 238; --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84; --bs-success: rgb(var(--bs-success-rgb));
            --bs-warning-rgb: 255, 193, 7; --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-danger-rgb: 220, 53, 69; --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-danger-rgb-light: 253, 237, 239; /* Added for danger-transparent */
            --bs-secondary-rgb: 108, 117, 125;
            --bs-body-bg: #f8f9fc;
            --bs-border-color: #e3e6f0;
            --bs-card-border-radius: 0.45rem;
            --bs-card-box-shadow: 0 0.15rem 1.75rem 0 rgba(0,0,0,0.05);
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: #5a5c69; }
        .card { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); margin-bottom: 1.5rem; }
        .card-header { background-color: #fff; border-bottom: 1px solid var(--bs-border-color); padding: 0.9rem 1.25rem; }
        .card-title-css { font-weight: 600; color: #212529; font-size:1.1rem; }
        .table thead th { background-color: #f8f9fa !important; color: #5a5c69; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom-width: 2px !important; white-space: nowrap; padding: 0.75rem 1rem; }
        .table td, .table th { vertical-align: middle; padding: 0.75rem 1rem; }
        .table tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.04); }
        .patient-avatar-sm { width: 35px; height: 35px; border-radius: 50%; margin-right: 10px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 0 5px rgba(0,0,0,0.1);}
        .fw-500 {font-weight: 500!important;}
        .tx-primary {color: var(--bs-primary);}
        .text-warning-dark {color: #c77c03 !important;} /* Darker warning text for better contrast on light backgrounds */
        .text-danger-dark{color:var(--bs-danger)!important}
        .action-buttons .btn { min-width: 40px; padding: 0.3rem 0.6rem; }
        .action-buttons .btn-label { display: none; }
        @media (min-width: 768px) { .action-buttons .btn-label { display: inline; } }


        .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.3px; min-width: 90px; text-align: center; display: inline-block; }
        .status-refill_requested { background-color: #ffe6cc; color: #c45100; border: 1px solid #ffdab3; }
        .request-reason-badge { font-size: 0.8rem; padding: 0.35em 0.7em; font-weight:500;}

        .empty-state-container { text-align: center; padding: 2.5rem 1rem; background-color: #f8f9fa; border-radius: 0.45rem; border: 1px dashed #e3e6f0; }
        .empty-state-container i.empty-icon {  font-size: 3.5rem; color: var(--bs-primary); opacity: 0.5; margin-bottom: 1rem; display: block; }
        .empty-state-container h5 { font-weight: 600; color: #212529; margin-bottom: 0.5rem; }
        .empty-state-container p { color: #6c757d; font-size: 0.95rem; }

        /* Modal Specific Styles */
        .modal-header .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); } /* Make close button more visible on dark headers */
        .modal-body .item-for-approval {
            padding: 0.75rem;
            border: 1px solid var(--bs-border-color);
            border-radius: var(--bs-card-border-radius);
            margin-bottom: 0.75rem;
            background-color: #fcfdff;
        }
        .modal-body .item-for-approval:last-child { margin-bottom: 0; }
        .modal-body .item-for-approval .form-check-input { margin-top: 0.4em; }
        .modal-body .form-label.sub-label { font-size: 0.85rem; margin-bottom: 0.2rem; color: #555; }
        .modal-body .form-control.form-control-sm-custom { font-size:0.85rem; padding: .25rem .5rem; max-width: 80px; display:inline-block;}
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
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="card" data-aos="fade-up">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
             <h4 class="card-title-css mb-2 mb-md-0">
                 <i class="fas fa-list-check me-2 text-warning-dark"></i> قائمة طلبات التجديد للموافقة
                 @if($approvalRequests->total() > 0)
                     <span class="badge bg-warning-transparent text-warning-dark rounded-pill ms-2">{{ $approvalRequests->total() }} طلب</span>
                 @endif
             </h4>
             <form method="GET" action="{{ route('doctor.prescriptions.approvalRequests') }}" class="d-flex align-items-center" style="min-width: 300px;">
                 <input type="text" name="search_patient_approval" class="form-control form-control-sm me-2" placeholder="بحث باسم المريض، هويته، رقم الوصفة..." value="{{ $request->search_patient_approval ?? '' }}">
                 <button type="submit" class="btn btn-sm btn-outline-primary ripple"><i class="fas fa-search"></i> بحث</button>
                 @if(request('search_patient_approval'))
                    <a href="{{ route('doctor.prescriptions.approvalRequests') }}" class="btn btn-sm btn-link text-danger ms-2" data-bs-toggle="tooltip" title="إلغاء البحث"><i class="fas fa-times"></i></a>
                 @endif
            </form>
        </div>
        <div class="card-body p-0">
            @if($approvalRequests->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="min-width: 200px;">المريض</th>
                                <th style="min-width: 150px;">الوصفة الأصلية</th>
                                <th style="min-width: 160px;">ملخص الأدوية الرئيسية</th>
                                <th style="min-width: 130px;">تاريخ الطلب</th>
                                <th style="min-width: 180px;">السبب الداعي لموافقتك</th>
                                <th class="text-center" style="min-width: 180px;">الإجراء</th>
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
                                                         {{ $prescription->patient->gender_display ?? ($prescription->patient->gender == 1 ? 'ذكر' : ($prescription->patient->gender == 2 ? 'أنثى' : '')) }}
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
                                            <ul class="list-unstyled mb-0 small">
                                                @foreach($prescription->items->take(2) as $item) {{-- عرض أول دواءين كملخص --}}
                                                    <li data-bs-toggle="tooltip" title="{{ $item->medication->name ?? '' }} {{ $item->medication->strength ?? '' }}"><i class="fas fa-capsule fa-xs text-muted me-1"></i>{{ Str::limit($item->medication->name ?? 'غير محدد', 25) }}</li>
                                                @endforeach
                                                @if($prescription->items_count > 2)
                                                    <li class="text-muted">& و {{ $prescription->items_count - 2 }} أدوية أخرى...</li>
                                                @endif
                                            </ul>
                                        @else
                                             <span class="text-muted small">(لا أدوية)</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <span data-bs-toggle="tooltip" title="{{ $prescription->updated_at->translatedFormat('h:i A') }}">
                                            {{ $prescription->updated_at->translatedFormat('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                         @php
                                             // منطق متقدم لتحديد السبب، ستحتاج لتعديله بناءً على استعلامك في الكنترولر
                                             $reasonText = "طلب تجديد من المريض";
                                             // يمكنك إضافة عمود `renewal_request_reason` في جدول prescriptions
                                             // أو تحليله من حالة البنود
                                             $allOriginalRefillsDone = true;
                                             $hadRefillableItems = false;
                                             foreach($prescription->items as $pItem){
                                                 if($pItem->refills_allowed > 0) $hadRefillableItems = true;
                                                 if($pItem->refills_allowed > $pItem->refills_done) $allOriginalRefillsDone = false;
                                             }
                                             if($hadRefillableItems && $allOriginalRefillsDone){
                                                $reasonText = "استنفاد مرات الإعادة";
                                             } elseif ($prescription->is_chronic_prescription && !$hadRefillableItems) {
                                                $reasonText = "طلب تجديد وصفة مزمنة";
                                             }
                                         @endphp
                                         <span class="badge bg-light border text-primary-dark request-reason-badge" data-bs-toggle="tooltip" title="هذا هو السبب الرئيسي الذي يستدعي موافقتك المباشرة">
                                             <i class="fas fa-info-circle me-1"></i>{{ $reasonText }}
                                         </span>
                                    </td>
                                    <td class="text-center action-buttons">
                                        <button type="button" class="btn btn-sm btn-success-gradient" data-toggle="modal" data-target="#approveRefillModal-{{ $prescription->id }}" title="الموافقة على التجديد">
                                            <i class="fas fa-check-circle"></i> <span class="btn-label">موافقة</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger-gradient" data-toggle="modal" data-target="#denyRefillModal-{{ $prescription->id }}" title="رفض طلب التجديد">
                                            <i class="fas fa-times-circle"></i> <span class="btn-label">رفض</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($approvalRequests->hasPages())
                    <div class="mt-3 px-3 pb-3 d-flex justify-content-center">
                        {{ $approvalRequests->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @else
                <div class="empty-state-container p-5" data-aos="zoom-in-up">
                    <i class="fas fa-clipboard-check empty-icon text-success opacity-75"></i>
                    <h5 class="mt-3 fw-bold">لا توجد طلبات إعادة صرف تنتظر موافقتك حاليًا.</h5>
                    <p class="text-muted col-md-8 mx-auto mt-2">
                        عمل رائع! لقد قمت بمراجعة جميع الطلبات التي تحتاج إلى قرارك.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- --------- Modals for Approval and Denial (Loop through prescriptions) --------- --}}
    @foreach($approvalRequests as $prescription)
        {{-- Modal for Approving Refill/Renewal --}}
        <div class="modal fade" id="approveRefillModal-{{ $prescription->id }}" tabindex="-1" aria-labelledby="approveRefillModalLabel-{{ $prescription->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered"> {{-- modal-lg for wider modal --}}
                <form action="{{ route('doctor.prescriptions.approveRefill', $prescription->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="approveRefillModalLabel-{{ $prescription->id }}">
                                <i class="fas fa-check-double me-2"></i>موافقة على تجديد للوصفة: <strong class="font-monospace">{{ $prescription->prescription_number }}</strong>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <p><strong>المريض:</strong> {{ $prescription->patient->name ?? 'غير محدد' }}
                                <a href="{{ route('doctor.patient.details', $prescription->patient_id) }}" target="_blank" class="ms-2 small">(عرض الملف)</a>
                            </p>
                            <hr>
                            <h6><i class="fas fa-pills me-2 text-primary"></i>الأدوية المطلوب تجديدها (حدد ما سيتم تجديده واضبط الشروط):</h6>
                            @if($prescription->items->isNotEmpty())
                                @foreach($prescription->items as $item)
                                    <div class="item-for-approval" data-aos="fade-in">
                                        <div class="d-flex align-items-center mb-2">
                                            <input class="form-check-input me-2" type="checkbox"
                                                   name="items[{{ $item->id }}][renew]"
                                                   id="renew_item_{{ $item->id }}_for_pres_{{ $prescription->id }}"
                                                   {{-- يمكنك تحديد Checkbox افتراضياً إذا كان الدواء لا يزال ضمن خطة العلاج --}}
                                                   {{-- 예를 들어, ($item->refills_allowed > 0 && $item->refills_done >= $item->refills_allowed) || ($item->refills_allowed == 0 && $prescription->is_chronic_prescription) ? 'checked' :  لا يمكن وضع شرط معقد هكذا ببساطة، يحتاج لـ PHP قبله --}}
                                                   checked {{-- تحديد الكل افتراضياً، الطبيب يزيل ما لا يريده --}}
                                                   onchange="toggleRefillFields(this, {{$item->id}}, {{$prescription->id}})">
                                            <label class="form-check-label fw-500" for="renew_item_{{ $item->id }}_for_pres_{{ $prescription->id }}">
                                                {{ $item->medication->name ?? 'دواء غير معروف' }}
                                                <small class="text-muted">({{ $item->medication->strength ?? '' }} {{ $item->medication->dosage_form_display ?? '' }})</small>
                                            </label>
                                        </div>
                                        <div class="ms-4 ps-2 row gx-2 item-renewal-fields" id="fields_item_{{ $item->id }}_pres_{{ $prescription->id }}">
                                            <div class="col-md-5 mb-2">
                                                <label for="refills_allowed_new_{{ $item->id }}" class="form-label sub-label">مرات الصرف الجديدة:</label>
                                                <input type="number" class="form-control form-control-sm-custom"
                                                       id="refills_allowed_new_{{ $item->id }}"
                                                       name="items[{{ $item->id }}][refills_allowed_new]"
                                                       min="0" max="12"
                                                       value="{{ $item->refills_allowed > 0 ? $item->refills_allowed : ($prescription->is_chronic_prescription ? 1 : 0) }}" {{-- قيمة افتراضية معقولة_ --}}
                                                       required>
                                            </div>
                                            <div class="col-md-7 mb-2">
                                                <label for="item_notes_new_{{ $item->id }}" class="form-label sub-label">ملاحظة على هذا الدواء (اختياري):</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       id="item_notes_new_{{ $item->id }}"
                                                       name="items[{{ $item->id }}][item_notes_new]"
                                                       placeholder="مثال: زد الجرعة، قلل المدة...">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-danger">لا توجد بنود أدوية في هذه الوصفة لمراجعتها!</p>
                            @endif
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                     <label for="new_next_refill_due_date-{{ $prescription->id }}" class="form-label">تاريخ استحقاق التجديد التالي للوصفة ككل (اختياري):</label>
                                     <input type="text" class="form-control flatpickr-modal" id="new_next_refill_due_date-{{ $prescription->id }}" name="new_next_refill_due_date" placeholder="YYYY-MM-DD"
                                            value="{{ $prescription->next_refill_due_date ? $prescription->next_refill_due_date->addMonth()->toDateString() : now()->addMonth()->toDateString() }}"> {{-- يقترح الشهر التالي --}}
                                     <small class="form-text text-muted">إذا كانت الوصفة دورية (شهرية، إلخ).</small>
                                 </div>
                            </div>
                            <div class="mb-2">
                                <label for="general_approval_notes-{{ $prescription->id }}" class="form-label">ملاحظات عامة على الموافقة (اختياري):</label>
                                <textarea class="form-control" id="general_approval_notes-{{ $prescription->id }}" name="general_approval_notes" rows="2" placeholder="مثال: يرجى متابعة المريض بعد أسبوعين..."></textarea>
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
                            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle me-1"></i> تأكيد الموافقة وحفظ التغييرات</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal for Denying Refill/Renewal --}}
        <div class="modal fade" id="denyRefillModal-{{ $prescription->id }}" tabindex="-1" aria-labelledby="denyRefillModalLabel-{{ $prescription->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                 <form action="{{ route('doctor.prescriptions.denyRefill', $prescription->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="denyRefillModalLabel-{{ $prescription->id }}">
                                <i class="fas fa-times-circle me-2"></i>رفض طلب تجديد للوصفة: <strong class="font-monospace">{{ $prescription->prescription_number }}</strong>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>المريض:</strong> {{ $prescription->patient->name ?? 'غير محدد' }}</p>
                            <div class="mb-3">
                                <label for="denial_reason-{{ $prescription->id }}" class="form-label">سبب الرفض (مطلوب وسيُسجل):</label>
                                <textarea class="form-control" id="denial_reason-{{ $prescription->id }}" name="denial_reason" rows="4" placeholder="مثال: يتطلب المريض مراجعة طبية قبل الموافقة، تم تغيير الخطة العلاجية..." required minlength="10"></textarea>
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
                            <button type="submit" class="btn btn-danger"><i class="fas fa-ban me-1"></i> تأكيد الرفض</button>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <script>
        function toggleRefillFields(checkbox, itemId, prescriptionId) {
            const fieldsDiv = document.getElementById(`fields_item_${itemId}_pres_${prescriptionId}`);
            const refillsInput = document.getElementById(`refills_allowed_new_${itemId}`);
            if (checkbox.checked) {
                fieldsDiv.style.display = 'flex'; // Or 'block' or 'grid' depending on your row layout
                if(refillsInput) refillsInput.required = true;
            } else {
                fieldsDiv.style.display = 'none';
                if(refillsInput) refillsInput.required = false;
            }
        }

        $(document).ready(function() {
            AOS.init({
                duration: 600,
                once: true,
                offset: 60
            });

            flatpickr(".flatpickr-modal", {
                 dateFormat: "Y-m-d",
                 locale: "ar",
                 allowInput: true,
                 minDate: "today"
             });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, { boundary: document.body, container: 'body' });
            });

            @if (session('success'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>", type: "success", position: "top-center", autohide: true, timeout: 6000});
            @endif
            @if (session('error'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>",type: "error",position: "top-center",autohide: true,timeout: 8000});
            @endif

            // عند فتح modal الموافقة، تأكد من أن حقول الدواء التي ليست checked تكون مخفية
             $('.modal[id^="approveRefillModal-"]').on('show.bs.modal', function (event) {
                $(this).find('.item-for-approval').each(function() {
                    var checkbox = $(this).find('input[type="checkbox"][name*="[renew]"]');
                    var fieldsDiv = $(this).find('.item-renewal-fields');
                    var refillsInput = fieldsDiv.find('input[name*="[refills_allowed_new]"]');

                    if (checkbox.is(':checked')) {
                        fieldsDiv.show();
                        if(refillsInput.length) refillsInput.prop('required', true);
                    } else {
                        fieldsDiv.hide();
                         if(refillsInput.length) refillsInput.prop('required', false);
                    }
                });
             });
        });
    </script>
@endsection
