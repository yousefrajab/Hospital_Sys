@extends('Dashboard.layouts.master')
@section('title', 'تفاصيل المرض: ' . $disease->name)

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* ... (استخدم نفس متغيرات CSS الرائعة التي صممتها لتوحيد المظهر) ... */
        :root {
            --admin-primary: #4f46e5; --admin-primary-dark: #4338ca; --admin-secondary: #10b981;
            --admin-success: #22c55e; --admin-danger: #ef4444; --admin-warning: #f59e0b;
            --admin-info: #3b82f6; --admin-bg: #f8f9fc; --admin-card-bg: #ffffff;
            --admin-text: #111827; --admin-text-secondary: #6b7280; --admin-border-color: #e5e7eb;
            --admin-radius-lg: 0.75rem; --admin-radius-xl: 1rem;
            --admin-shadow-lg: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            --admin-transition: all 0.3s ease-in-out;
        }
        @media (prefers-color-scheme: dark) {
            :root { /* ... (أنماط الوضع الداكن) ... */ }
            .info-card-disease { background-color: #2d3748; border-color: var(--admin-border-color); }
            .info-card-disease strong { color: var(--admin-text) !important; }
            .description-box { background-color: #2d3748; border-color: var(--admin-border-color);}
        }
        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }

        .disease-details-page { padding-top: 1.5rem; padding-bottom: 3rem; }
        .main-details-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-xl);
            box-shadow: var(--admin-shadow-lg);
            border: 1px solid var(--admin-border-color);
            overflow: hidden;
        }
        .details-card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-info)); /* تدرج لوني مختلف قليلاً */
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid transparent;
            border-radius: var(--admin-radius-xl) var(--admin-radius-xl) 0 0;
        }
        .details-card-header h3 { margin: 0; font-size: 1.6rem; font-weight: 700; }
        .details-card-header .disease-type-badge { font-size: 0.9rem; padding: 0.5em 1em; border-radius: 50px; }

        .details-card-body { padding: 2rem; }

        .info-grid-disease { display: grid; grid-template-columns: 1fr; /* عمود واحد للمعلومات الأساسية */ gap: 1.5rem; margin-bottom: 2rem; }
        .info-card-disease {
            background-color: var(--admin-bg);
            padding: 1.25rem 1.5rem;
            border-radius: var(--admin-radius-md);
            border-left: 5px solid var(--admin-secondary);
            transition: var(--admin-transition);
        }
        .info-card-disease:hover { transform: translateY(-3px); box-shadow: 0 3px 10px rgba(0,0,0,0.07); }
        .info-card-disease .info-label { display: block; font-size: 0.9rem; color: var(--admin-text-secondary); margin-bottom: 0.4rem; font-weight: 500;}
        .info-card-disease .info-value { font-size: 1.1rem; color: var(--admin-text); font-weight: 600; word-break: break-word; }
        .info-card-disease .info-value i { margin-left: 0.5rem; color: var(--admin-secondary); width: 1.2em; }

        .description-box {
            background-color: var(--admin-bg);
            padding: 1.5rem;
            border-radius: var(--admin-radius-md);
            border: 1px dashed var(--admin-border-color);
            margin-top: 1rem;
        }
        .description-box h5 { font-weight: 600; color: var(--admin-primary); margin-bottom: 0.75rem; }
        .description-box p { color: var(--admin-text-secondary); line-height: 1.7; white-space: pre-wrap; /* للحفاظ على تنسيق الأسطر */ }

        .action-buttons-footer-show { margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border-color); text-align: center; }
        .btn-action-footer-show { padding: 0.75rem 1.5rem; border-radius: var(--admin-radius-md); font-weight: 600; margin: 0 0.5rem; text-decoration: none; }
        .btn-primary-custom { background-color: var(--admin-primary); color:white; border:1px solid var(--admin-primary); }
        .btn-primary-custom:hover { background-color: var(--admin-primary-dark); border-color:var(--admin-primary-dark); }
        .btn-secondary-custom { background-color: var(--admin-text-secondary); color:white; border:1px solid var(--admin-text-secondary); }
        .btn-secondary-custom:hover { background-color: #5a6268; border-color:#545b62; }

        /* شارات لنوع المرض */
        .badge-chronic-show { background-color: rgba(var(--admin-warning-rgb), 0.2); color: #856404; border: 1px solid rgba(var(--admin-warning-rgb),0.3); }
        .badge-not-chronic-show { background-color: rgba(var(--admin-info-rgb), 0.15); color: var(--admin-info); border: 1px solid rgba(var(--admin-info-rgb),0.3); }
        .dark .badge-chronic-show { color: var(--admin-warning); }
        .dark .badge-not-chronic-show { color: var(--admin-info); }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-viruses fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">{{ trans('AdminSb.disease_management') }}</h4>
                    <span class="text-muted mt-0 tx-13">/ {{ trans('AdminSb.disease_details_colon') }} {{ $disease->name }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.diseases.edit', $disease->id) }}" class="btn btn-outline-primary btn-sm me-2" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-edit me-1"></i> {{ trans('AdminSb.edit_disease') }}
            </a>
            <a href="{{ route('admin.diseases.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> {{ trans('AdminSb.back_to_diseases_list') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="disease-details-page">
        <div class="main-details-card animate__animated animate__fadeInUp">
            <div class="details-card-header">
                <h3><i class="fas fa-notes-medical"></i> {{ $disease->name }}</h3>
                <span class="badge disease-type-badge {{ $disease->is_chronic ? 'badge-chronic-show' : 'badge-not-chronic-show' }}">
                    <i class="fas {{ $disease->is_chronic ? 'fa-history' : 'fa-bolt' }} me-1"></i>
                    {{ $disease->is_chronic ? trans('AdminSb.chronic_disease') : trans('AdminSb.non_chronic_disease') }}
                </span>
            </div>

            <div class="details-card-body">
                <div class="info-grid-disease">
                    <div class="info-card-disease">
                        <span class="info-label"><i class="fas fa-file-signature"></i>{{ trans('AdminSb.disease_name') }}</span>
                        <strong class="info-value">{{ $disease->name }}</strong>
                    </div>
                    <div class="info-card-disease">
                        <span class="info-label"><i class="far fa-calendar-alt"></i>{{ trans('AdminSb.date_added') }}</span>
                        <strong class="info-value">{{ $disease->created_at->translatedFormat('l, d M Y - H:i A') }}</strong>
                    </div>
                    <div class="info-card-disease">
                        <span class="info-label"><i class="far fa-calendar-check"></i>{{ trans('AdminSb.last_updated') }}</span>
                        <strong class="info-value">{{ $disease->updated_at->translatedFormat('l, d M Y - H:i A') }}</strong>
                    </div>
                    <div class="info-card-disease">
                        <span class="info-label"><i class="fas {{ $disease->is_chronic ? 'fa-stopwatch-20' : 'fa-heart-pulse' }}"></i>{{ trans('AdminSb.disease_type') }}</span>
                        <strong class="info-value">{{ $disease->is_chronic ? trans('AdminSb.chronic') : trans('AdminSb.non_chronic_acute_temporary') }}</strong>
                    </div>
                </div>

                @if($disease->description)
                    <hr class="section-divider-page">
                    <div class="description-box">
                        <h5><i class="fas fa-align-left me-2"></i> {{ trans('AdminSb.disease_description') }}</h5>
                        <p>{{ $disease->description }}</p>
                    </div>
                @endif
                @if($disease->patientChronicRecords()->count() > 0)
                <hr class="section-divider-page">
                <div>
                    <h5 class="section-title-page"><i class="fas fa-users me-2"></i> {{ trans('AdminSb.patients_diagnosed_with_this_disease', ['count' => $disease->patientChronicRecords()->count()]) }}</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($disease->patientChronicRecords()->with('patient')->take(5)->get() as $record)
                            <li class="list-group-item">
                                <a href="#">{{ $record->patient->name ?? trans('AdminSb.unknown_patient') }}</a>
                                <small class="text-muted"> ({{ trans('AdminSb.diagnosis_date_colon') }} {{ $record->diagnosed_at ? $record->diagnosed_at->format('Y-m-d') : '-' }})</small>
                            </li>
                        @endforeach
                        @if($disease->patientChronicRecords()->count() > 5)
                            <li class="list-group-item text-center"><a href="#">{{ trans('AdminSb.view_more') }}</a></li>
                        @endif
                    </ul>
                </div>
                @endif

                <div class="action-buttons-footer-show">
                    <a href="{{ route('admin.diseases.edit', $disease->id) }}" class="btn btn-primary-custom btn-action-footer-show ripple-effect">
                        <i class="fas fa-edit me-2"></i> {{ trans('AdminSb.edit_disease_data') }}
                    </a>
                    <a href="{{ route('admin.diseases.index') }}" class="btn btn-secondary-custom btn-action-footer-show ripple-effect">
                        <i class="fas fa-list-alt me-2"></i> {{ trans('AdminSb.view_all_diseases') }}
                    </a>
                     <button type="button" class="btn btn-danger btn-action-footer-show ripple-effect" data-toggle="modal" data-target="#deleteDiseaseConfirmModal{{ $disease->id }}">
                        <i class="fas fa-trash-alt me-2"></i> {{ trans('AdminSb.delete_disease') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(isset($disease))
    <div class="modal fade" id="deleteDiseaseConfirmModal{{ $disease->id }}" tabindex="-1" aria-labelledby="deleteDiseaseModalLabel{{ $disease->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--admin-danger), #c82333); color:white; border-bottom:none;">
                    <h5 class="modal-title" id="deleteDiseaseModalLabel{{ $disease->id }}"><i class="fas fa-exclamation-triangle me-2"></i> {{ trans('AdminSb.confirm_deletion') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <form action="{{ route('admin.diseases.destroy', $disease->id) }}" method="POST" id="confirmDeleteDiseaseFormModal{{ $disease->id }}">
                        @csrf
                        @method('DELETE')
                        <div style="font-size: 3rem; color: var(--admin-danger); margin-bottom: 1rem;">
                            <i class="fas fa-viruses"></i>
                        </div>
                        <h4>{{ trans('AdminSb.are_you_sure_delete_disease_permanently') }}</h4>
                        <p class="text-muted">
                            {{ trans('AdminSb.disease_name_colon') }} <strong>{{ $disease->name }}</strong>
                        </p>
                        <p class="text-danger small mt-3">
                            <i class="fas fa-info-circle"></i> {{ trans('AdminSb.action_cannot_be_undone') }}
                            @if($disease->patientChronicRecords()->exists())
                                <br><strong>{{ trans('AdminSb.warning_disease_linked_to_patients') }}</strong>
                            @endif
                        </p>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-top-0">
                    <button type="submit" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px;"><i class="fas fa-times me-1"></i> {{ trans('AdminSb.cancel') }}</button>
                    <button type="submit" form="confirmDeleteDiseaseFormModal{{ $disease->id }}" class="btn btn-danger" style="min-width: 100px;"><i class="fas fa-trash-alt me-1"></i> {{ trans('AdminSb.yes_delete') }}</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            console.log("Disease details page loaded for Disease ID: {{ $disease->id }}");
            @if (session('success'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "bottom", autohide: true, timeout: 5000 });
            @endif
            @if (session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "bottom", autohide: true, timeout: 7000 });
            @endif

            // (اختياري) تأثير التحميل لزر الحذف في المودال
            $('form[id^="confirmDeleteDiseaseFormModal"]').on('submit', function() {
                $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin me-2"></i> جاري الحذف...').prop('disabled', true);
            });
        });
    </script>
@endsection
