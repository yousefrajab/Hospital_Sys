@extends('Dashboard.layouts.master')

@section('css')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #e6e9ff;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #2b2d42;
            --light-color: #f8f9fa;
            --gray-color: #6c757d;
        }

        .patient-profile {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .patient-profile:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        }

        /* .patient-avatar {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        } */

        .patient-stats {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .doctor-avatar {
            position: relative;
            width: 100px;
            height: 180px;
            margin: 0 auto;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* .doctor-avatar img {
            width: 100%;
            height: 100%;

        } */


        .stat-card {
            border-left: 3px solid var(--primary-color);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .modern-tabs .nav-link {
            color: var(--gray-color);
            font-weight: 600;
            padding: 12px 20px;
            border: none;
            margin-right: 5px;
            position: relative;
            transition: all 0.3s;
            background: transparent;
        }

        .modern-tabs .nav-link.active {
            color: var(--primary-color);
            background: transparent;
        }

        .modern-tabs .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px 3px 0 0;
        }

        .modern-tabs .nav-link:hover:not(.active) {
            color: var(--primary-color);
        }

        .tab-content-card {
            border-radius: 0 0 15px 15px;
            border: none;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        }

        .data-table {
            border-radius: 10px;
            overflow: hidden;
        }

        .data-table th {
            background-color: var(--primary-light);
            color: var(--dark-color);
            font-weight: 600;
            border: none;
        }

        .data-table td {
            vertical-align: middle;
            border-top: 1px solid #f1f1f1;
        }

        .badge-pill {
            padding: 0.35em 0.65em;
            font-weight: 500;
            border-radius: 50px;
        }

        .invoice-badge {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .receipt-badge {
            background-color: rgba(23, 162, 184, 0.1);
            color: var(--info-color);
        }

        .payment-badge {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .empty-state {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 48px;
            color: var(--gray-color);
            margin-bottom: 20px;
        }

        .balance-card {
            background: linear-gradient(135deg, #f5f7fa, #e4e8f0);
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .floating-action-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
            z-index: 100;
            transition: all 0.3s;
        }

        .floating-action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .patient-profile {
                text-align: center;
            }

            .modern-tabs .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('title')
    <i class="fas fa-user-injured"></i> ملف المريض - {{ $Patient->name }}
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-users"></i> إدارة المرضى</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ ملف المريض</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('admin.Patients.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> قائمة المرضى
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row" data-aos="fade-in">

        <div class="col-lg-4 col-md-12">
            <div class="patient-profile p-4 mb-4">
                <div class="d-flex align-items-center">
                    @if ($Patient->image)
                        <img src="{{ Url::asset('Dashboard/img/patients/' . $Patient->image->filename) }}"
                            class="doctor-avatar" alt="{{ trans('patients.img') }}">
                    @else
                        <img src="{{ Url::asset('Dashboard/img/doctor_default.png') }}" class="doctor-avatar"
                            alt="صورة افتراضية">
                    @endif
                    <div>
                        <h3 class="mb-1">{{ $Patient->name }}</h3>
                        <p class="mb-2">
                            <i class="fas fa-id-card mr-1"></i>
                            ID: {{ $Patient->id }}
                        </p>
                        <span class="badge badge-pill {{ $Patient->Gender == 1 ? 'bg-primary' : 'bg-pink' }}">
                            <i class="fas {{ $Patient->Gender == 1 ? 'fa-mars' : 'fa-venus' }}"></i>
                            {{ $Patient->Gender == 1 ? 'ذكر' : 'أنثى' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="patient-stats p-3 mb-4">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-card p-2">
                            <h5 class="mb-1 text-primary">{{ number_format($invoices->sum('total_with_tax'), 2) }}</h5>
                            <small class="text-muted">إجمالي الفواتير</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-card p-2">
                            <h5 class="mb-1 text-success">{{ number_format($receipt_accounts->sum('amount'), 2) }}</h5>
                            <small class="text-muted">إجمالي المدفوعات</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card p-2">
                            <h5 class="mb-1 text-info">{{ $invoices->count() }}</h5>
                            <small class="text-muted">عدد الفواتير</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card p-2">
                            @php
                                $balance = $invoices->sum('total_with_tax') - $receipt_accounts->sum('amount');
                            @endphp
                            <h5 class="mb-1 {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format(abs($balance), 2) }}
                            </h5>
                            <small class="text-muted">{{ $balance > 0 ? 'مدين' : 'دائن' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i> المعلومات الأساسية</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-id-card mr-2 text-primary"></i> رقم الهوية</span>
                            <span class="font-weight-bold">{{ $Patient->national_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-phone mr-2 text-primary"></i> الهاتف</span>
                            <span class="font-weight-bold">{{ $Patient->Phone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-envelope mr-2 text-primary"></i> البريد</span>
                            <span class="font-weight-bold">{{ $Patient->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-birthday-cake mr-2 text-primary"></i> تاريخ الميلاد</span>
                            <span class="font-weight-bold">{{ $Patient->Date_Birth }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-tint mr-2 text-primary"></i> فصيلة الدم</span>
                            <span class="badge badge-pill bg-danger">{{ $Patient->Blood_Group }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-calendar-alt mr-2 text-primary"></i> تاريخ التسجيل</span>
                            <span class="font-weight-bold">{{ $Patient->created_at->format('Y-m-d') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header bg-white p-0">
                    <ul class="nav modern-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#invoices-tab">
                                <i class="fas fa-file-invoice-dollar mr-1"></i>
                            </a>الفواتير
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#payments-tab">
                                <i class="fas fa-money-bill-wave mr-1"></i> المدفوعات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#account-tab">
                                <i class="fas fa-calculator mr-1"></i> كشف حساب
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#radiology-tab">
                                <i class="fas fa-x-ray mr-1"></i> الأشعة
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#lab-tab">
                                <i class="fas fa-flask mr-1"></i> المختبر
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body tab-content p-0">
                    <!-- الفواتير -->
                    <div class="tab-pane fade show active p-3" id="invoices-tab">
                        @if ($invoices->count() > 0)
                            <div class="table-responsive">
                                <table class="table data-table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الخدمة</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                            <th>خيارات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $invoice)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $invoice->Service->name ?? $invoice->Group->name }}</td>
                                                <td>{{ $invoice->invoice_date }}</td>
                                                <td>{{ number_format($invoice->total_with_tax, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill {{ $invoice->type == 1 ? 'invoice-badge' : 'payment-badge' }}">
                                                        {{ $invoice->type == 1 ? 'نقدي' : 'آجل' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="3">الإجمالي</th>
                                            <th colspan="3">{{ number_format($invoices->sum('total_with_tax'), 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <h4>لا توجد فواتير مسجلة</h4>
                                <p class="text-muted">لم يتم تسجيل أي فواتير لهذا المريض حتى الآن</p>
                                {{-- <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة فاتورة جديدة
                        </button> --}}
                            </div>
                        @endif
                    </div>

                    <!-- المدفوعات -->
                    <div class="tab-pane fade p-3" id="payments-tab">
                        @if ($receipt_accounts->count() > 0)
                            <div class="table-responsive">
                                <table class="table data-table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>البيان</th>
                                            <th>خيارات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($receipt_accounts as $receipt_account)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $receipt_account->date }}</td>
                                                <td>{{ number_format($receipt_account->amount, 2) }}</td>
                                                <td>{{ $receipt_account->description }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-receipt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="2">الإجمالي</th>
                                            <th colspan="3">{{ number_format($receipt_accounts->sum('amount'), 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h4>لا توجد مدفوعات مسجلة</h4>
                                <p class="text-muted">لم يتم تسجيل أي مدفوعات لهذا المريض حتى الآن</p>
                            </div>
                        @endif
                    </div>

                    <!-- كشف الحساب -->
                    <div class="tab-pane fade p-3" id="account-tab">
                        @if ($Patient_accounts->count() > 0)
                            <div class="table-responsive">
                                <table class="table data-table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>التاريخ</th>
                                            <th>الوصف</th>
                                            <th>مدين</th>
                                            <th>دائن</th>
                                            <th>الرصيد</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $runningBalance = 0;
                                        @endphp
                                        @foreach ($Patient_accounts as $Patient_account)
                                            @php
                                                $runningBalance += $Patient_account->Debit - $Patient_account->credit;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $Patient_account->date }}</td>
                                                <td>
                                                    @if ($Patient_account->invoice_id == true)
                                                        {{ $Patient_account->invoice->Service->name ?? $Patient_account->invoice->Group->name }}
                                                    @elseif($Patient_account->receipt_id == true)
                                                        {{ $Patient_account->ReceiptAccount->description }}
                                                    @elseif($Patient_account->Payment_id == true)
                                                        {{ $Patient_account->PaymentAccount->description }}
                                                    @endif
                                                </td>
                                                <td>{{ number_format($Patient_account->Debit, 2) }}</td>
                                                <td>{{ number_format($Patient_account->credit, 2) }}</td>
                                                <td class="{{ $runningBalance > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format(abs($runningBalance), 2) }}
                                                    ({{ $runningBalance > 0 ? 'مدين' : 'دائن' }})
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="balance-card p-3 mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">الرصيد الحالي:</h5>
                                    @php
                                        $balance = $invoices->sum('total_with_tax') - $receipt_accounts->sum('amount');
                                    @endphp
                                    <h3 class="mb-0 {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format(abs($balance), 2) }}
                                        <small class="text-muted">({{ $balance > 0 ? 'مدين' : 'دائن' }})</small>
                                    </h3>
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <h4>لا توجد حركات مالية</h4>
                                <p class="text-muted">لم يتم تسجيل أي حركات مالية لهذا المريض حتى الآن</p>
                            </div>
                        @endif
                    </div>

                    <!-- الأشعة -->
                    <div class="tab-pane fade p-3" id="radiology-tab">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-x-ray"></i>
                            </div>
                            <h4>قسم الأشعة قيد التطوير</h4>
                            <p class="text-muted">سيتم إضافة وظائف الأشعة قريبًا في تحديثات النظام القادمة</p>
                            <button class="btn btn-primary disabled">
                                <i class="fas fa-clock"></i> قريبًا
                            </button>
                        </div>
                    </div>

                    <!-- المختبر -->
                    <div class="tab-pane fade p-3" id="lab-tab">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-flask"></i>
                            </div>
                            <h4>قسم المختبر قيد التطوير</h4>
                            <p class="text-muted">سيتم إضافة وظائف المختبر قريبًا في تحديثات النظام القادمة</p>
                            <button class="btn btn-primary disabled">
                                <i class="fas fa-clock"></i> قريبًا
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <a href="#" class="floating-action-btn" data-toggle="tooltip" title="إضافة جديد">
        <i class="fas fa-plus"></i>
    </a>

@endsection

@section('js')
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        $(document).ready(function() {
            // Initialize DataTables
            $('.data-table').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                initComplete: function() {
                    $('.dataTables_filter input').attr('placeholder', 'ابحث هنا...');
                }
            });

            // Tooltip initialization
            $('[data-toggle="tooltip"]').tooltip();

            // Smooth scroll for tabs
            $('.modern-tabs a').on('click', function(e) {
                e.preventDefault();
                $($(this).attr('href')).scrollIntoView({
                    behavior: 'smooth'
                });
                $(this).tab('show');
            });
        });
    </script>
@endsection
