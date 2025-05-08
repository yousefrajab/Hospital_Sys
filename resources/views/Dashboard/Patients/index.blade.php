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

        .patient-table-card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .patient-table-header {
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

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
            border-radius: 8px;
        }

        .gender-badge {
            background-color: rgba(72, 149, 239, 0.1);
            color: var(--accent-color);
        }

        .blood-badge {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .patient-link {
            color: var(--primary-color);
            font-weight: 500;
            transition: all 0.2s;
        }

        .patient-link:hover {
            color: var(--secondary-color);
            text-decoration: none;
        }
    </style>
    @include('Style.Style')
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex align-items-center">
            <h4 class="content-title mb-0 my-auto">المرضى</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة المرضى</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
@include('Dashboard.messages_alert')

<!-- row opened -->
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card patient-table-card">
            <div class="card-header patient-table-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0 text-white">قائمة المرضى</h3>
                    <a href="{{route('admin.Patients.create')}}" class="btn btn-light">
                        <i class="fas fa-user-plus mr-2"></i> اضافة مريض جديد
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-md-nowrap" id="patients-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم المريض</th>
                                <th>صورة المريض</th>
                                <th>رقم الهوية</th>
                                <th>البريد الإلكتروني</th>
                                <th>تاريخ الميلاد</th>
                                <th>رقم الهاتف</th>
                                <th>الجنس</th>
                                <th>فصيلة الدم</th>
                                <th>العنوان</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Patients as $Patient)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    <a href="{{route('admin.Patients.show',$Patient->id)}}" class="patient-link">
                                        {{$Patient->name}}
                                    </a>
                                </td>
                                <td>
                                    {{-- عرض الصورة كما هو --}}
                                    @if ($Patient->image)
                                        <img src="{{ Url::asset('Dashboard/img/patients/' . $Patient->image->filename) }}"
                                            class="doctor-avatar" alt="{{ trans('patients.img') }}">
                                    @else
                                        <img src="{{ Url::asset('Dashboard/img/doctor_default.png') }}"
                                            class="doctor-avatar" alt="صورة افتراضية">
                                    @endif
                                </td>
                                <td>{{$Patient->national_id}}</td>
                                <td>{{$Patient->email}}</td>
                                <td>{{$Patient->Date_Birth}}</td>
                                <td>{{$Patient->Phone}}</td>
                                <td>
                                    <span class="badge gender-badge">
                                        {{$Patient->Gender == 1 ? 'ذكر' : 'أنثى'}}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge blood-badge">
                                        {{$Patient->Blood_Group}}
                                    </span>
                                </td>
                                <td>{{ Str::limit($Patient->Address, 20) }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{route('admin.Patients.edit',$Patient->id)}}" class="btn btn-sm btn-success mr-2" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger mr-2" data-toggle="modal" data-target="#Deleted{{$Patient->id}}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <a href="{{route('admin.Patients.show',$Patient->id)}}" class="btn btn-sm btn-primary" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @include('Dashboard.Patients.Deleted')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /row -->
@endsection

@section('js')
    <!-- Internal Notify js -->
    <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>

    <!-- DataTables -->
    <script>
        $(document).ready(function() {
            $('#patients-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip><"clear">',
                initComplete: function() {
                    $('.dataTables_filter input').attr('placeholder', 'ابحث هنا...');
                }
            });
        });
    </script>
@endsection
