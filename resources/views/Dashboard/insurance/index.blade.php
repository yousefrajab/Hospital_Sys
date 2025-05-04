@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('Admin/assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('path/to/notifIt.css') }}">
    @include('Style.Style')
@endsection
@section('title')
    {{ trans('main-sidebar_trans.Insurance') }}
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('main-sidebar_trans.Services') }}</h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ trans('main-sidebar_trans.Insurance') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('Dashboard.messages_alert')

    <!-- row -->
    <div class="row">
        <div class="col">
            <div class="card doctors-card">
                <div class="card-header doctors-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 text-white">
                            <a href="{{ route('admin.insurance.create') }}" class="btn btn-light">
                                <i class="fas fa-plus-circle"></i>{{ trans('insurance.Add_Insurance') }}</a>
                    </div>
                </div>
                <div class="card-body">

                    {{-- <table class="table text-md-nowrap text-center" id="example1"> --}}
                    <div class="table-responsive" style="overflow: visible;">
                        <table class="table text-center border-0 rounded-3 shadow table-hover" id="example1"
                            style="transition: all 0.3s ease-in-out;">
                            {{-- <table class="table table-hover text-center table-bordered table-striped" id="example1" style="width:100%"> --}}
                            <thead>
                                <tr class="table-secondary">
                                    <th>#</th>
                                    <th>{{ trans('insurance.Company_code') }}</th>
                                    <th>{{ trans('insurance.Company_name') }}</th>
                                    <th>{{ trans('insurance.discount_percentage') }}</th>
                                    <th>{{ trans('insurance.Insurance_bearing_percentage') }}</th>
                                    <th>{{ trans('insurance.status') }}</th>
                                    <th>{{ trans('insurance.notes') }}</th>
                                    <th>{{ trans('insurance.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($insurances as $insurance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $insurance->insurance_code }}</td>
                                        <td>{{ $insurance->name }}</td>
                                        <td>%{{ $insurance->discount_percentage }}</td>
                                        <td>%{{ $insurance->Company_rate }}</td>
                                        <td>
                                            <span
                                                class="badge-status {{ $insurance->status == 1 ? 'active-status' : 'inactive-status' }}">
                                                <i class="fas fa-circle status-icon pulse me-1"
                                                    style="color: {{ $insurance->status == 1 ? '#28a745' : '#dc3545' }}"></i>
                                                {{ $insurance->status == 1 ? trans('doctors.Enabled') : trans('doctors.Not_enabled') }}
                                            </span>
                                        </td>
                                        <td>{{ $insurance->notes }}</td>
                                        <td>
                                            <a href="{{ route('admin.insurance.edit', $insurance->id) }}"
                                                class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#Deleted{{ $insurance->id }}"><i class="fas fa-trash"></i>
                                            </button>

                                        </td>
                                        @include('Dashboard.insurance.Deleted')
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('Admin/assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ asset('path/to/notifIt.min.js') }}"></script>
    <script src="{{ URL::asset('Admin/assets/plugins/notify/js/notifit-custom.js') }}"></script>

    @include('Script.Script')
@endsection
