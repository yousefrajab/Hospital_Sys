@extends('Dashboard.layouts.master')
@section('title', 'طلبات إعادة الصرف المعلقة')

@section('css')
    @parent
    {{-- CSS files needed for this page (AOS, FontAwesome, NotifIt) --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        /* --- Root Variables & Basic Styling (Adopted from your previous clean styles) --- */
        :root {
            --bs-primary-rgb: 67, 97, 238; /* #4361ee */
            --bs-primary: rgb(var(--bs-primary-rgb));
            /* ... (باقي متغيرات الألوان الأساسية) ... */
            --bs-body-bg: #f8f9fc;
            --bs-border-color: #e3e6f0;
            --bs-card-border-radius: 0.45rem;
            --bs-card-box-shadow: 0 0.15rem 1.75rem 0 rgba(0,0,0,0.05);
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: #5a5c69; }
        .content-title { font-weight: 600; }
        .card { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); margin-bottom: 1.5rem; }
        .card-header { background-color: #fff; border-bottom: 1px solid var(--bs-border-color); padding: 0.9rem 1.25rem; }
        .card-title-css { font-weight: 600; color: var(--bs-dark); font-size:1.1rem } /* Custom Card Title */

        .table thead th { background-color: var(--bs-light) !important; color: #5a5c69; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom-width: 2px !important; white-space: nowrap; padding: 0.75rem 1rem; }
        .table td, .table th { vertical-align: middle; padding: 0.75rem 1rem; }
        .table tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.04); }

        /* Status Badges (نفس أنماطك السابقة) */
         .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.3px; min-width: 110px; text-align: center; display: inline-block; }
         .status-new { background-color: rgba(var(--bs-info-rgb), 0.15); color: rgb(var(--bs-info-rgb)); border: 1px solid rgba(var(--bs-info-rgb), 0.3); }
         .status-pending_review { background-color: rgba(var(--bs-warning-rgb), 0.2); color: #a17d06; border: 1px solid rgba(var(--bs-warning-rgb), 0.4); }
         .status-approved { background-color: rgba(var(--bs-primary-rgb), 0.15); color: var(--bs-primary); border: 1px solid rgba(var(--bs-primary-rgb), 0.3); }
         .status-ready_for_pickup { background-color: #e2f0d9; color: #548235; border: 1px solid #c5e0b4;}
         .status-processing { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5;}
         .status-dispensed { background-color: rgba(var(--bs-success-rgb), 0.15); color: rgb(var(--bs-success-rgb)); border: 1px solid rgba(var(--bs-success-rgb), 0.3); }
         .status-partially_dispensed { background-color: rgba(255, 165, 0, 0.15); color: #cc8400; border: 1px solid rgba(255, 165, 0, 0.3); }
         .status-cancelled_by_doctor, .status-cancelled_by_pharmacist, .status-cancelled_by_patient { background-color: rgba(var(--bs-danger-rgb), 0.1); color: rgb(var(--bs-danger-rgb)); border: 1px solid rgba(var(--bs-danger-rgb), 0.2); }
         .status-on_hold { background-color: rgba(var(--bs-secondary-rgb), 0.15); color: rgb(var(--bs-secondary-rgb)); border: 1px solid rgba(var(--bs-secondary-rgb), 0.2); }
         .status-expired { background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;}
         .status-refill_requested { background-color: #ffe6cc; color: #c45100; border: 1px solid #ffdab3; } /* لون لطلب إعادة الصرف */
         .status-default { background-color: var(--bs-light); color: var(--bs-dark); border: 1px solid var(--bs-border-color); }


         .action-buttons .btn { margin: 0 2px; padding: 0.3rem 0.6rem; font-size: 0.8rem; }
         .action-buttons .btn i { font-size: 0.9rem; }

         .empty-state-container { text-align: center; padding: 2.5rem 1rem; background-color: var(--bs-light); border-radius: var(--bs-card-border-radius); border: 1px dashed var(--bs-border-color); }
         .empty-state-container i.empty-icon {  font-size: 3.5rem; color: var(--bs-primary); opacity: 0.6; margin-bottom: 1rem; display: block; }
         .empty-state-container h5 { font-weight: 600; color: var(--bs-dark); margin-bottom: 0.5rem; }
         .empty-state-container p { color: var(--bs-secondary); font-size: 0.95rem; }

         .prescription-date { font-weight: 500; color: #555; }
         .doctor-name { font-weight: 500; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">
                    <a href="{{ route('patient.pharmacy.index') }}" class="text-primary-dark tx-16 hover-underline ">
                        <i class="fas fa-pills me-2 opacity-75"></i> {{ trans('patients.my_prescriptions') }}
                    </a>
                </h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="mt-1 tx-13"> {{ trans('patients.pending_refill_requests') }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="card" data-aos="fade-up">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title-css mb-0"><i class="fas fa-hourglass-start text-warning me-2"></i>{{ trans('patients.my_pending_refill_requests') }}</h4>
            @if($pendingRefills->total() > 0)
             <span class="badge bg-warning-transparent text-warning rounded-pill">
                 {{ $pendingRefills->total() }} {{ trans('patients.pending_request_unit') }}
             </span>
            @endif
        </div>
        <div class="card-body p-0">
            @if ($pendingRefills->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="min-width: 140px;">{{ trans('patients.original_prescription_number_col') }}</th>
                                <th style="min-width: 180px;">{{ trans('patients.doctor_col') }}</th>
                                <th style="min-width: 130px;">{{ trans('patients.refill_request_date_col') }}</th>
                                <th style="min-width: 160px;">{{ trans('patients.current_request_status_col') }}</th>
                                <th class="text-center">{{ trans('patients.medication_count_col') }}</th>
                                <th class="text-center" style="min-width: 120px;">{{ trans('patients.actions_col') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingRefills as $prescription)
                                <tr>
                                    <td>{{ $loop->iteration + $pendingRefills->firstItem() - 1 }}</td>
                                    <td>
                                        <a href="{{ route('patient.pharmacy.show', $prescription->id) }}" class="text-primary fw-500" data-bs-toggle="tooltip" title="{{ trans('patients.view_original_prescription_details') }}">
                                            {{ $prescription->prescription_number }}
                                        </a>
                                    </td>
                                    <td class="doctor-name">
                                        <i class="fas fa-user-md text-muted me-1 opacity-75"></i>
                                        {{ $prescription->doctor ? $prescription->doctor->name : trans('patients.unspecified') }}
                                    </td>
                                    <td class="prescription-date">
                                        {{ $prescription->updated_at->translatedFormat('d M Y, h:i A') }}
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $prescription->status_badge_class }}" data-bs-toggle="tooltip" title="{{ $prescription->status_display }}">
                                            {{ $prescription->status_display }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                         @if (isset($prescription->items_count) && $prescription->items_count > 0)
                                             <span class="badge bg-light text-dark border">
                                               {{ $prescription->items_count }}
                                               {{ $prescription->items_count == 1 ? trans('patients.medication_unit_singular') : ($prescription->items_count == 2 ? trans('patients.medication_unit_dual') : trans('patients.medication_unit_plural')) }}
                                             </span>
                                         @else
                                             <span class="text-muted small"> --- </span>
                                         @endif
                                    </td>
                                    <td class="text-center action-buttons">
                                        <a href="{{ route('patient.pharmacy.show', $prescription->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" title="{{ trans('patients.view_original_prescription_and_track_status') }}">
                                            <i class="fas fa-eye"></i> <span class="d-none d-md-inline">{{ trans('patients.follow_up_button') }}</span>
                                        </a>
                                        @if($prescription->status === \App\Models\Prescription::STATUS_REFILL_REQUESTED)
                                             {{-- <form action="{{ route('patient.pharmacy.cancel-refill-request', $prescription->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('patients.confirm_cancellation_message') }}')">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="{{ trans('patients.cancel_refill_request_tooltip') }}">
                                                     <i class="fas fa-times-circle"></i>
                                                 </button>
                                             </form> --}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($pendingRefills->hasPages())
                    <div class="mt-3 px-3 pb-3 d-flex justify-content-center">
                        {{ $pendingRefills->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state-container p-5" data-aos="zoom-in-up">
                    <i class="fas fa-check-double empty-icon text-success opacity-75"></i>
                    <h5 class="mt-3 fw-bold">{{ trans('patients.no_pending_refills_title') }}</h5>
                    <p class="text-muted col-md-8 mx-auto mt-2">
                        {{ trans('patients.no_pending_refills_message') }}
                    </p>
                    <a href="{{ route('patient.pharmacy.index') }}" class="btn btn-primary-transparent mt-2">
                        <i class="fas fa-receipt me-1"></i> {{ trans('patients.view_all_my_prescriptions') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 600,
                once: true,
                offset: 50
            });

            // تفعيل الـ Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                     boundary: document.body,
                     fallbackPlacements: ['top', 'bottom', 'right', 'left']
                });
            });

            @if (session('success'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>",
                    type: "success", position: "top-center", autohide: true, timeout: 5000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>",
                    type: "error", position: "top-center", autohide: true, timeout: 7000
                });
            @endif
        });
    </script>
@endsection
