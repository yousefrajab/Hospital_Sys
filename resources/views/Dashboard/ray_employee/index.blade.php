@extends('Dashboard.layouts.master')

@section('css')
<link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4895ef;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --danger-color: #dc3545;
        --success-color: #28a745;
    }

    .employee-table-card {
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .employee-table-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 15px 15px 0 0 !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }

    .table thead th {
        background-color: var(--light-color);
        color: var(--dark-color);
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: rgba(72, 149, 239, 0.05);
    }

    .btn-sm {
        padding: 0.35rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 8px;
    }

    .btn-info {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
    }
</style>
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex align-items-center">
            <h4 class="content-title mb-0 my-auto">الاشعة</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الموظفين</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
@include('Dashboard.messages_alert')

<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card employee-table-card">
            <div class="card-header employee-table-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0 text-white">قائمة الموظفين</h3>
                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#add">
                        <i class="fas fa-user-plus mr-2"></i> اضافة موظف جديد
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-md-nowrap" id="ray-employees-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>رقم الهوية</th>
                            <th>البريد الالكتروني</th>
                            <th>تاريخ الاضافة</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ray_employees as $ray_employee)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$ray_employee->name}}</td>
                                <td>{{$ray_employee->national_id}}</td>
                                <td>{{$ray_employee->email}}</td>
                                <td>{{$ray_employee->created_at->diffForHumans()}}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a class="btn btn-sm btn-info mr-2" data-effect="effect-scale" data-toggle="modal" href="#edit{{$ray_employee->id}}" title="تعديل">
                                            <i class="las la-pen"></i>
                                        </a>
                                        <a class="btn btn-sm btn-danger" data-effect="effect-scale" data-toggle="modal" href="#delete{{$ray_employee->id}}" title="حذف">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @include('Dashboard.ray_employee.edit')
                            @include('Dashboard.ray_employee.delete')
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('Dashboard.ray_employee.add')
@endsection

@section('js')
<script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>

<script>
    $(document).ready(function () {
        $('#ray-employees-table').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            },
            responsive: true,
            dom: '<"top"lf>rt<"bottom"ip><"clear">',
            initComplete: function () {
                $('.dataTables_filter input').attr('placeholder', 'ابحث هنا...');
            }
        });
    });
</script>
@endsection
