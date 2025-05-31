@extends('Dashboard.layouts.master')

@section('title')
    {{ trans('main-sidebar_trans.Single_service') }}
@stop

@section('css')
    @include('Style.Style')
    {{-- لا تحتاج CSS لـ Select2 إذا كنت تستخدم قائمة منسدلة قياسية --}}
    <style>
        .doctors-list .badge {
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">{{ trans('main-sidebar_trans.Services') }}</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">{{ trans('main-sidebar_trans.Single_service') }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card service-card mg-b-20">
                <div class="card-header">
                    <h4 class="card-title mb-0" style="color: white">{{ trans('main-sidebar_trans.Single_service') }}</h4>
                    <button type="button" class="btn btn-modern" data-toggle="modal" data-target="#add"
                        style="color: white">
                        <i class="fas fa-plus mr-1"></i> {{ trans('Services.add_Service') }}
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="services-table" class="table table-hover text-md-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('Services.name') }}</th>
                                    <th>{{ trans('Services.price') }}</th>
                                    <th>{{ trans('doctors.Doctor') }}</th> {{-- تم تغيير العنوان ليناسب طبيب واحد --}}
                                    <th>{{ trans('doctors.Status') }}</th>
                                    <th>{{ trans('Services.description') }}</th>
                                    <th>{{ trans('sections_trans.created_at') }}</th>
                                    <th>{{ trans('sections_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($services) && $services->count() > 0) {{-- تحقق إضافي --}}
                                    @foreach ($services as $service)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ number_format($service->price, 2) }}</td>
                                            <td class="doctors-list">
                                                @if ($service->doctor) {{-- الوصول للعلاقة المفردة 'doctor' --}}
                                                    <span>{{ $service->doctor->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ trans('doctors.not_assigned') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge-status {{ $service->status == 1 ? 'active-status' : 'inactive-status' }}">
                                                    <i class="fas fa-circle status-icon pulse me-1"
                                                        style="color: {{ $service->status == 1 ? '#28a745' : '#dc3545' }}"></i>
                                                    {{ $service->status == 1 ? trans('doctors.Enabled') : trans('doctors.Not_enabled') }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($service->description, 50) }}</td>
                                            <td class="creation-time-cell">
                                                <div class="time-display"
                                                    title="{{ $service->created_at->format('Y/m/d h:i A') }}">
                                                    <small class="text-muted">
                                                        {{ $service->created_at->format('Y/m/d') }}
                                                        <span
                                                            class="time-period {{ $service->created_at->format('A') == 'AM' ? 'morning' : 'evening' }}">
                                                            {{ $service->created_at->format('A') == 'AM' ? 'صباحًا' : 'مساءً' }}
                                                        </span>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <a class="btn btn-primary btn-sm" data-effect="effect-scale"
                                                        data-toggle="modal" href="#edit{{ $service->id }}">
                                                        <i class="las la-pen"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm" data-effect="effect-scale"
                                                        data-toggle="modal" href="#delete{{ $service->id }}">
                                                        <i class="las la-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- تمرير $doctors (قائمة كل الأطباء) و $service (الخدمة الحالية للتعديل) --}}
                                        {{-- مودال التعديل سيتعامل مع $service->doctor_id داخلياً --}}
                                        @include('Dashboard.Services.Single Service.edit', ['service' => $service, 'doctors' => $doctors ?? []])
                                        @include('Dashboard.Services.Single Service.delete', ['service' => $service])
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">{{ trans('general.no_data_available') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- تمرير $doctors (قائمة كل الأطباء) لمودال الإضافة --}}
    @include('Dashboard.Services.Single Service.add', ['doctors' => $doctors ?? []])
@endsection

@section('js')
    @include('Script.Script')
    {{-- لا تحتاج Select2 JS إذا كنت تستخدم قائمة منسدلة قياسية --}}
@endsection
