@extends('Dashboard.layouts.master')

@section('title', 'إدارة خدماتي الطبية')

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        :root {
            --bs-primary-rgb: 59, 130, 246; /* Tailwind Blue 500 */
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-primary-darker: rgb(37, 99, 235); /* Tailwind Blue 600 for hover */
            --bs-success-rgb: 16, 185, 129; /* Tailwind Emerald 500 */
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-danger-rgb: 239, 68, 68; /* Tailwind Red 500 */
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-info-rgb: 59, 130, 246; /* Using primary for info as an example */
            --bs-info: rgb(var(--bs-info-rgb));
            --bs-gray-100: #f8f9fa; /* Light gray for backgrounds */
            --bs-gray-200: #e9ecef; /* Border color */
            --bs-gray-700: #4a5568; /* Darker text */
            --bs-gray-800: #2d3748; /* Even darker text */
            --bs-gray-900: #1a202c; /* Almost black */
            --bs-body-bg: #f7fafc; /* Off-white background */
            --bs-border-color: var(--bs-gray-200);
            --bs-card-border-radius: 0.75rem; /* More rounded cards */
            --bs-card-box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --bs-card-cap-bg: #fff;
            --bs-card-cap-padding-y: 1.25rem;
            --bs-card-cap-padding-x: 1.5rem;
        }
        body { font-family: 'Cairo', 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: var(--bs-gray-700); }
        .card {
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            border: 1px solid var(--bs-border-color);
            margin-bottom: 2rem;
            overflow: hidden; /* To contain a potentially full-width header */
        }
        .card-header.card-header-enhanced {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-darker) 100%);
            color: white;
            padding: var(--bs-card-cap-padding-y) var(--bs-card-cap-padding-x);
            border-bottom: none;
        }
        .card-header.card-header-light {
            background-color: var(--bs-card-cap-bg);
            border-bottom: 1px solid var(--bs-border-color);
            padding: var(--bs-card-cap-padding-y) var(--bs-card-cap-padding-x);
        }
        .card-title-enhanced { font-weight: 700; font-size:1.2rem; margin-bottom: 0; display: flex; align-items: center; }
        .card-title-enhanced i { font-size: 1.3em; margin-right: 0.75rem; opacity: 0.9; }

        .table thead th {
            background-color: var(--bs-gray-100) !important; color: var(--bs-gray-900);
            font-weight: 600; font-size: 0.85rem; text-transform: uppercase;
            letter-spacing: 0.05em; border-bottom: 2px solid var(--bs-border-color) !important;
            padding: 1rem 1.5rem;
        }
        .table td, .table th { vertical-align: middle; padding: 1rem 1.5rem; border-top: 1px solid var(--bs-border-color); }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.04); }

        .service-name-cell .service-name { font-weight: 600; color: var(--bs-gray-800); font-size: 1.05rem; }
        .service-name-cell .service-description { font-size: 0.85rem; color: var(--bs-gray-700); margin-top: 0.25rem; }

        .status-badge {
            padding: 0.45em 1em; border-radius: 50px; font-size: 0.8rem;
            font-weight: 600; letter-spacing: 0.5px; min-width: 100px;
            text-align: center; display: inline-flex; align-items: center; justify-content: center;
        }
        .status-badge i { margin-right: 0.4em; font-size: 0.9em; }
        .status-badge.bg-success-soft { background-color: rgba(var(--bs-success-rgb), 0.15); color: rgb(var(--bs-success-rgb)); }
        .status-badge.bg-danger-soft { background-color: rgba(var(--bs-danger-rgb), 0.15); color: rgb(var(--bs-danger-rgb)); }

        .action-buttons .btn {
            border-radius: 50px; /* Circular buttons */
            width: 40px; height: 40px;
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0; margin: 0 0.25rem;
            transition: all 0.2s ease-in-out;
        }
        .action-buttons .btn:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .btn-outline-info:hover { background-color: rgba(var(--bs-info-rgb), 0.1); }
        .btn-outline-danger:hover { background-color: rgba(var(--bs-danger-rgb), 0.1); }


        .empty-state-container {
            text-align: center; padding: 4rem 2rem; background-color: #fff;
            border-radius: var(--bs-card-border-radius);
            border: 2px dashed var(--bs-border-color);
        }
        .empty-state-container .empty-icon { font-size: 5rem; color: var(--bs-primary); opacity: 0.2; margin-bottom: 1.5rem; display: block; }
        .empty-state-container h4 { font-weight: 700; color: var(--bs-gray-800); margin-bottom: 0.75rem; font-size: 1.5rem; }
        .empty-state-container p { color: var(--bs-gray-700); font-size: 1.05rem; margin-bottom: 1.5rem; }
        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-darker) 100%);
            border: none; color: white;
        }
        .btn-primary-gradient:hover {
             background: linear-gradient(135deg, var(--bs-primary-darker) 0%, var(--bs-primary) 100%);
             box-shadow: 0 4px 15px rgba(var(--bs-primary-rgb), 0.3);
        }

        .btn-circle-plus {
            background: var(--bs-primary); color: white;
            width: 50px; height: 50px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.2rem; box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
            transition: all 0.3s ease;
        }
        .btn-circle-plus:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 16px rgba(var(--bs-primary-rgb), 0.4);
        }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between py-3">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">
                    <i class="fas fa-clinic-medical fa-lg text-primary me-2"></i>
                    <span class="fw-bold">إدارة خدماتي الطبية</span>
                </h4>
            </div>
             <p class="text-muted mt-1 mb-0 tx-13">عرض، إنشاء، وتعديل الخدمات التي تقدمها.</p>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <a href="{{ route('doctor.services_management.create') }}" class="btn btn-primary-gradient btn-icon-text ripple" data-aos="fade-left" data-aos-delay="100">
                <i class="fas fa-plus-circle me-2"></i> إنشاء خدمة جديدة
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    @include('Dashboard.messages_alert')

    <div class="card" data-aos="fade-up">
        <div class="card-header card-header-light">
            <h4 class="card-title-enhanced">
                <i class="fas fa-stream text-primary"></i>
                قائمة خدماتي المسجلة
                 @if(isset($doctorServices) && $doctorServices->count() > 0)
                    <span class="badge bg-primary-light text-primary rounded-pill ms-2 px-3 py-1">{{ $doctorServices->count() }} خدمة</span>
                @endif
            </h4>
            {{-- يمكن وضع فلاتر أو بحث هنا إذا أردت --}}
        </div>
        <div class="card-body p-0">
            @if(isset($doctorServices) && $doctorServices->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover"> {{-- إزالة table-borderless لإظهار فواصل الأسطر --}}
                    <thead>
                        <tr>
                            <th style="width: 40%;">الاسم والوصف</th>
                            <th class="text-center">السعر</th>
                            <th class="text-center">الحالة</th>
                            <th class="text-center" style="width: 15%;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctorServices as $service)
                        <tr data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <td class="service-name-cell">
                                <div class="service-name">{{ $service->name }}</div>
                                @if($service->description)
                                <div class="service-description" data-bs-toggle="tooltip" title="{{$service->description}}">
                                    <i class="fas fa-info-circle fa-xs me-1 text-muted"></i>{{ Str::limit($service->description, 70) }}
                                </div>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ number_format($service->price, 2) }} <small class="text-muted">{{-- {{ trans('general.currency_symbol') }} --}}</small></td>
                            <td class="text-center">
                                <span class="status-badge {{ $service->status ? 'bg-success-soft' : 'bg-danger-soft' }}">
                                    <i class="fas {{ $service->status ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $service->status ? trans('doctors.Enabled') : trans('doctors.Not_enabled') }}
                                </span>
                            </td>
                            <td class="text-center action-buttons">
                                <a href="{{ route('doctor.services_management.edit', $service->id) }}" class="btn btn-outline-info ripple" data-bs-toggle="tooltip" title="تعديل بيانات الخدمة">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('doctor.services_management.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة نهائياً؟ لا يمكن التراجع عن هذا الإجراء.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger ripple" data-bs-toggle="tooltip" title="حذف الخدمة نهائياً">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(method_exists($doctorServices, 'links')) {{-- Check if pagination is used --}}
                <div class="mt-3 px-4 pb-3 d-flex justify-content-center">
                    {{ $doctorServices->links('pagination::bootstrap-5') }}
                </div>
            @endif
            @else
            <div class="empty-state-container" data-aos="zoom-in-up">
                <i class="fas fa-notes-medical empty-icon"></i>
                <h4>لم تقم بتسجيل أي خدمات طبية بعد.</h4>
                <p>ابدأ بإضافة الخدمات التي تقدمها لمرضاك.</p>
                <a href="{{ route('doctor.services_management.create') }}" class="btn btn-lg btn-primary-gradient ripple mt-3 px-5">
                    <i class="fas fa-plus-circle me-2"></i> إضافة خدمتي الأولى
                </a>
            </div>
            @endif
        </div>
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
                duration: 800,
                once: true,
                offset: 50
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl, {
                container: 'body' // Recommended for better positioning
              })
            })

            @if (session('success'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>", type: "success", position: "top-center", autohide: true, timeout: 5000});
            @endif
            @if (session('error'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>",type: "error",position: "top-center",autohide: true,timeout: 7000});
            @endif
             @if (session('info'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-info-circle fa-lg me-2 text-info'></i><div style='font-size: 0.95rem;'>{{ session('info') }}</div></div>",type: "info",position: "top-center",autohide: true,timeout: 5000});
            @endif
        });
    </script>
@endsection
