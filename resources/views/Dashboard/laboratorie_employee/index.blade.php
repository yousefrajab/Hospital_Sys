@extends('Dashboard.layouts.master')

@section('css')
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
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

        .doctor-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المختبر</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                    الموظفين</span>
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
                        <button type="button" class="btn btn-add-employee" data-toggle="modal" data-target="#add">
                            <i class="fas fa-user-plus"></i> إضافة موظف جديد
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>الاسم</th>
                                    <th>رقم الهوية</th>
                                    <th>البريد الالكتروني</th>
                                    <th>رقم الجوال</th>
                                    <th> الحالة</th>
                                    <th>تاريخ الاضافة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laboratorie_employees as $laboratorie_employee)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset('Dashboard/img/laboratorieEmployees/' . ($laboratorie_employee->image ? $laboratorie_employee->image->filename : 'default.png')) }}"
                                                class="doctor-avatar" alt="صورة الموظف">
                                        </td>
                                        <td>{{ $laboratorie_employee->name }}</td>
                                        <td>{{ $laboratorie_employee->national_id }}</td>
                                        <td>{{ $laboratorie_employee->email }}</td>
                                        <td>{{ $laboratorie_employee->phone }}</td>
                                        <td>
                                            <span class="badge badge-{{ $laboratorie_employee->status == 1 ? 'success' : 'danger' }}">
                                                {{ $laboratorie_employee->status == 1 ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>{{ $laboratorie_employee->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.laboratorie_employee.edit', $laboratorie_employee->id) }}"
                                                    class="btn btn-sm btn-info" {{-- الحفاظ على نفس شكل الزر --}}
                                                    title="تعديل"> {{-- إضافة تلميح للمستخدم --}}
                                                     <i class="las la-pen"></i>
                                                 </a>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#delete{{ $laboratorie_employee->id }}"><i
                                                        class="las la-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- @include('Dashboard.laboratorie_employee.edit', ['laboratorie_employee' => $laboratorie_employee])
                                    {{-- ** تضمين مودال الحذف هنا ** --}}
                                    @include('Dashboard.laboratorie_employee.delete', ['laboratorie_employee' => $laboratorie_employee])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Dashboard.laboratorie_employee.add')
@endsection

@section('js')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.13.6/b-2.4.2/b-html5-2.4.2/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip><"clear">'
            });
        });
    </script>
@endsection
