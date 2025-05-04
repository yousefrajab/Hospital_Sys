@extends('Dashboard.layouts.master')
{{-- @if ($errors->any())
    <div class="w-full max-w-3xl mx-auto mt-6 mb-4">
        <div class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100 border border-red-300 dark:border-red-700 rounded-xl p-4 shadow-md animate-fade-in">
            <h2 class="font-bold text-lg mb-2">❗ هناك بعض الأخطاء:</h2>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif --}}
@section('title')
    {{ trans('main-sidebar_trans.Single_service') }}
@stop

@section('css')
@include('Style.Style')

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
                                    <th>{{ trans('doctors.Status') }}</th>
                                    <th>{{ trans('Services.description') }}</th>
                                    <th>{{ trans('sections_trans.created_at') }}</th>
                                    <th>{{ trans('sections_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ number_format($service->price, 2) }}</td>
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
                                    @include('Dashboard.Services.Single Service.edit')
                                    @include('Dashboard.Services.Single Service.delete')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Dashboard.Services.Single Service.add')
@endsection

@section('js')

@include('Script.Script')
@endsection
