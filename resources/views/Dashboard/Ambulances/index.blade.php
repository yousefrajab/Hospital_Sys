@extends('Dashboard.layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('Admin/assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('path/to/notifIt.css') }}">
    @include('Style.Style')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاسعاف</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ سيارات
                    الاسعاف</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('Dashboard.messages_alert')
    <!-- row opened -->
    <div class="row row-sm">
        <!--div-->
        <div class="col-xl-12">
            <div class="card doctors-card">
                <div class="card-header doctors-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 text-white">
                            <a href="{{ route('admin.Ambulance.create') }}" class="btn btn-light">
                                <i class="fas fa-plus-circle"></i>اضافة سيارة جديدة</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="overflow: visible;">
                        <table class="table text-center border-0 rounded-3 shadow table-hover" id="example1"
                            style="transition: all 0.3s ease-in-out;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم السيارة</th>
                                    <th>موديل السيارة</th>
                                    <th>سنة الصنع</th>
                                    <th>نوع السيارة</th>
                                    <th>اسم السائق</th>
                                    <th>رقم الرخصة</th>
                                    <th>رقم الهاتف</th>
                                    <th>حالة السيارة</th>
                                    <th>ملاحظات</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ambulances as $ambulance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ambulance->car_number }}</td>
                                        <td>{{ $ambulance->car_model }}</td>
                                        <td>{{ $ambulance->car_year_made }}</td>
                                        <td>{{ $ambulance->car_ambulancetype == 1 ? 'مملكوكة' : 'ايجار' }}</td>
                                        <td>{{ $ambulance->driver_name }}</td>
                                        <td>{{ $ambulance->driver_license_number }}</td>
                                        <td>{{ $ambulance->driver_phone }}</td>
                                        <td class="{{ $ambulance->is_available == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $ambulance->is_available == 1 ? 'مفعل' : 'غير مفعل' }}</td>
                                        <td>{{ $ambulance->notes }}</td>
                                        <td>
                                            <a href="{{ route('admin.Ambulance.edit', $ambulance->id) }}"
                                                class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#Deleted{{ $ambulance->id }}"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @include('Dashboard.Ambulances.Deleted')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
