@extends('Dashboard.layouts.master')

{{-- قسم CSS: إضافة الأنماط الخاصة بعمود ساعات العمل --}}
@section('css')
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
        .working-hours-summary {
            font-size: 0.8rem;
            /* حجم خط أصغر قليلاً */
            line-height: 1.5;
            min-width: 190px;
            /* عرض أدنى للعمود للسماح بعرض الوقت */
            vertical-align: middle;
            /* محاذاة رأسية للوسط */
        }

        .working-days-list {
            padding-right: 0;
            /* إزالة الحشوة الافتراضية لليمين في RTL */
        }

        .working-days-list li {
            margin-bottom: 4px;
            white-space: nowrap;
            /* منع التفاف السطر */
            display: flex;
            /* لترتيب الأيقونة والنص */
            align-items: center;
        }

        .working-days-list li:last-child {
            margin-bottom: 0;
        }

        .working-days-list li i {
            opacity: 0.6;
            margin-left: 5px;
            /* مسافة لليسار (للعربية) */
            font-size: 0.9em;
            width: 15px;
            /* عرض ثابت للأيقونة للمحاذاة */
            text-align: center;
        }

        .working-days-list strong {
            color: #556a81;
            /* لون أغمق لليوم */
            min-width: 40px;
            /* محاذاة بسيطة لأسماء الأيام */
            display: inline-block;
            font-weight: 600;
            /* خط أعرض لاسم اليوم */
        }

        .working-days-list span.times {
            color: #333;
            /* لون أغمق للوقت */
            font-weight: 500;
        }

        .badge-light {
            /* تنسيق badge "لم يحدد" */
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 0.8em;
        }
    </style>
@endsection

@section('title')
    {{ trans('main-sidebar_trans.doctors') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('main-sidebar_trans.doctors') }}</h4>
                <span class="text mt-1 tx-13 mr-2 mb-0">/ {{ trans('main-sidebar_trans.view_all') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @include('Dashboard.messages_alert') {{-- عرض رسائل النجاح/الخطأ العامة --}}

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card doctors-card">
                <div class="doctors-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 text-white">
                            <i class="fas fa-user-md mr-2"></i>{{ trans('doctors.List of Doctors') }}
                        </h3>
                        <div>
                            <a href="{{ route('admin.Doctors.create') }}" class="btn btn-light mr-2">
                                <i class="fas fa-plus-circle mr-1"></i> {{ trans('doctors.add_doctor') }}
                            </a>
                            <button type="button" class="btn btn-light" id="btn_delete_all">
                                <i class="fas fa-trash-alt mr-1"></i> {{ trans('doctors.delete_select') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" style="min-height: 400px; overflow: visible !important;">
                        {{-- التأكيد على overflow: visible --}}
                        <table class="table table-hover table-advanced" id="doctors-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><input name="select_all" type="checkbox" id="example-select-all"></th>
                                    <th>{{ trans('doctors.name') }}</th>
                                    <th>{{ trans('doctors.national_id') }}</th> {{-- تم إخفاءه لتوفير مساحة --}}
                                    <th>{{ trans('doctors.img') }}</th>
                                    <th>{{ trans('doctors.email') }}</th>
                                    <th>{{ trans('doctors.section') }}</th>
                                    <th>{{ trans('doctors.phone') }}</th>
                                    <th>{{ trans('doctors.number_of_statements') }}</th>
                                    <th>ساعات العمل</th> {{-- *** عنوان العمود الجديد *** --}}
                                    <th>{{ trans('doctors.Status') }}</th>
                                    {{-- <th>{{ trans('doctors.created_at') }}</th> --}}
                                    <th>{{ trans('doctors.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- التحقق من وجود أطباء قبل الحلقة --}}
                                @forelse ($doctors as $doctor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">
                                            <div class="innovative-checkbox">
                                                <input type="checkbox" name="delete_select" value="{{ $doctor->id }}"
                                                    id="doctor-check-{{ $doctor->id }}" class="delete_select">
                                                <label for="doctor-check-{{ $doctor->id }}" class="check-label">
                                                    {{-- SVG كما هو --}}
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        class="check-icon">
                                                        <circle cx="12" cy="12" r="10"
                                                            class="check-bg-circle"></circle>
                                                        <polyline points="6 12 10 16 18 8" class="check-mark"></polyline>
                                                        <circle cx="12" cy="12" r="12" class="pulse-effect">
                                                        </circle>
                                                    </svg>
                                                    <span class="check-text">{{ trans('doctors.select') }}</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td><strong>{{ $doctor->name }}</strong></td>
                                        <td>{{ $doctor->national_id }}</td>
                                        <td>
                                            {{-- عرض الصورة كما هو --}}
                                            @if ($doctor->image)
                                                <img src="{{ Url::asset('Dashboard/img/doctors/' . $doctor->image->filename) }}"
                                                    class="doctor-avatar" alt="{{ trans('doctors.img') }}">
                                            @else
                                                <img src="{{ Url::asset('Dashboard/img/doctor_default.png') }}"
                                                    class="doctor-avatar" alt="صورة افتراضية">
                                            @endif
                                        </td>
                                        <td>{{ $doctor->email }}</td>
                                        <td>
                                            {{-- عرض القسم كما هو --}}
                                            @if ($doctor->section)
                                                {{-- إضافة تحقق من وجود القسم --}}
                                                <a href="{{ route('admin.Sections.show', $doctor->section->id) }}"
                                                    class="inline-block">
                                                    <span
                                                        class="badge bg-primary-light text-primary p-2">{{ $doctor->section->name }}</span>
                                                </a>
                                            @else
                                                <span class="badge badge-light">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>{{ $doctor->phone }}</td>
                                        {{-- <td> --}}
                                        {{-- عرض doctorappointments القديم (تم إخفاءه) --}}
                                        {{-- @foreach ($doctor->doctorappointments as $appointment) ... @endforeach --}}
                                        {{-- </td> --}}
                                        <td>
                                            {{-- عرض عدد الكشوفات كما هو --}}
                                            <div class="statements-count" data-count="{{ $doctor->number_of_statements }}">
                                                <div class="count-circle"> <span
                                                        class="count-number">{{ $doctor->number_of_statements }}</span>
                                                    <svg class="count-circle-bg" viewBox="0 0 36 36">
                                                        <path
                                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                            fill="none" stroke="#e0e0e0" stroke-width="3" />
                                                        <path class="count-circle-fill"
                                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                            fill="none" stroke="#4361ee" stroke-width="3"
                                                            stroke-dasharray="0, 100" />
                                                    </svg>
                                                </div> <span class="count-label">{{ trans('doctors.Reports') }}</span>
                                            </div>
                                        </td>

                                        {{-- *** محتوى عمود ساعات العمل الجديد *** --}}
                                        <td class="working-hours-summary">
                                            {{-- التأكد من أن workingDays تم تحميلها (مهم) --}}
                                            @if ($doctor->relationLoaded('workingDays') && $doctor->workingDays->where('active', true)->isNotEmpty())
                                                <ul class="list-unstyled mb-0 working-days-list">
                                                    @php
                                                        $daysOrder = [
                                                            'Saturday',
                                                            'Sunday',
                                                            'Monday',
                                                            'Tuesday',
                                                            'Wednesday',
                                                            'Thursday',
                                                            'Friday',
                                                        ];
                                                        $activeWorkingDays = $doctor->workingDays
                                                            ->where('active', true)
                                                            ->sortBy(function ($day) use ($daysOrder) {
                                                                // معالجة اسم اليوم ليكون متناسقاً قبل البحث
                                                                $dayNameNormalized = ucfirst(
                                                                    strtolower(trim($day->day)),
                                                                );
                                                                return array_search($dayNameNormalized, $daysOrder) ??
                                                                    99; // أعط قيمة كبيرة للأيام غير المعروفة
                                                            });
                                                    @endphp
                                                    @foreach ($activeWorkingDays as $day)
                                                        <li>
                                                            <i class="far fa-calendar-check text-success"></i>
                                                            {{-- أيقونة أوضح --}}
                                                            <strong>{{ trans('doctors.' . $day->day) ?? ucfirst(strtolower(trim($day->day))) }}:</strong>
                                                            <span class="times text-nowrap mr-1"
                                                                dir="ltr">{{ \Carbon\Carbon::parse($day->start_time)->format('h:i A') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($day->end_time)->format('h:i A') }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="badge badge-light">لم يحدد</span>
                                            @endif
                                        </td>
                                        {{-- -------------------------------------- --}}

                                        <td>
                                            {{-- عرض الحالة كما هو --}}
                                            <span
                                                class="badge-status {{ $doctor->status == 1 ? 'active-status' : 'inactive-status' }}">
                                                <i class="fas fa-circle status-icon pulse mr-1"
                                                    style="color: {{ $doctor->status == 1 ? '#28a745' : '#dc3545' }}"></i>
                                                {{ $doctor->status == 1 ? trans('doctors.Enabled') : trans('doctors.Not_enabled') }}
                                            </span>
                                        </td>
                                        {{-- <td class="creation-time-cell">
                                            <div class="time-display"
                                                title="{{ $doctor->created_at->format('Y/m/d h:i A') }}"> <small
                                                    class="text-muted"> {{ $doctor->created_at->format('Y/m/d') }} <span
                                                        class="time-period {{ $doctor->created_at->format('A') == 'AM' ? 'morning' : 'evening' }}">
                                                        {{ $doctor->created_at->format('A') == 'AM' ? trans('doctors.AM') : trans('doctors.PM') }}
                                                    </span> </small> </div>
                                        </td> --}}
                                        <td>
                                            {{-- Dropdown العمليات كما هو --}}
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                    type="button" id="dropdownMenu{{ $doctor->id }}"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-action"
                                                    aria-labelledby="dropdownMenu{{ $doctor->id }}">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.Doctors.edit', $doctor->id) }}"> <i
                                                            class="fas fa-edit text-success action-icon"></i>
                                                        {{ trans('doctors.modify data') }} </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#update_password{{ $doctor->id }}"> <i
                                                            class="fas fa-key text-primary action-icon"></i>{{ trans('doctors.update_password') }}
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#update_status{{ $doctor->id }}"> <i
                                                            class="fas fa-power-off text-warning action-icon"></i>
                                                        {{ trans('doctors.Status_change') }} </a>

                                                    <a href="{{ route('admin.doctors.schedule.edit', $doctor->id) }}"
                                                        class="dropdown-item"> {{-- استخدام ستايل زر الإلغاء كمثال --}}
                                                        <span class="btn-content"><i class="fas fa-calendar-alt me-2"></i>
                                                            <span>تعديل جدول العمل</span></span>
                                                        <span class="btn-wave"></span>
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#"
                                                        data-toggle="modal" data-target="#delete{{ $doctor->id }}"> <i
                                                            class="fas fa-trash-alt action-icon"></i>
                                                        {{ trans('doctors.delete_doctor') }} </a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @empty
                                    {{-- رسالة في حالة عدم وجود أطباء --}}
                                    <tr>
                                        <td colspan="12" class="text-center text-muted py-4">لا يوجد أطباء لعرضهم
                                            حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->

    {{-- تضمين المودالات (تأكد من أنها موجودة وتعمل) --}}
    @if (isset($doctors) && $doctors->isNotEmpty())
        {{-- تحقق إضافي لتجنب الخطأ إذا كانت doctors فارغة --}}
        @include('Dashboard.Doctors.delete_select') {{-- مودال الحذف المتعدد --}}
        @foreach ($doctors as $doctor)
            {{-- تضمين مودالات كل طبيب --}}
            @include('Dashboard.Doctors.delete')
            @include('Dashboard.Doctors.update_password')
            @include('Dashboard.Doctors.update_status')
        @endforeach
    @endif

@endsection

{{-- قسم JS: تضمين السكربتات الأصلية فقط --}}
@section('js')
    {{-- تأكد من أن هذا الملف يحتوي على تهيئة DataTables وكود الحذف المتعدد --}}
    {{-- وأن jQuery و Popper و Bootstrap JS محملة في الـ layout الرئيسي --}}
    @include('Script.Script')
@endsection
