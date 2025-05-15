@extends('Dashboard.layouts.master')

@section('css')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- Add DataTables CSS if not already globally included --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" />


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

        body {
            background-color: #f4f7fc;
        }

        .patient-profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
            transition: all 0.3s ease;
            overflow: hidden;
            /* To contain the avatar shadow if it's too large */
        }

        .patient-profile-header:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        }

        .patient-profile-avatar {
            /* More standard avatar */
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            margin-right: 1.5rem;
            /* Space between avatar and text */
        }

        .patient-stats {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .stat-card {
            border-left: 3px solid var(--primary-color);
            transition: all 0.3s;
            background-color: #fff;
            border-radius: 8px;
            padding: 1rem;
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
            border-radius: 8px 8px 0 0;
        }

        .modern-tabs .nav-link.active {
            color: var(--primary-color);
            background: white;
            /* Tab content area is white, so active tab bg should match */
        }

        .modern-tabs .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: -1px;
            /* Align with card border */
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary-color);
        }

        .modern-tabs .nav-link:hover:not(.active) {
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .tab-content-card {
            background: white;
            border-radius: 0 0 15px 15px;
            /* Match header card */
            border: none;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        }

        .data-table {
            border-radius: 10px;
            overflow: hidden;
            width: 100% !important;
            /* Ensure DataTable takes full width */
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
            padding: 0.4em 0.75em;
            /* Slightly larger badges */
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
            background: #f9fafc;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            border: 1px dashed #e0e0e0;
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
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
            margin-bottom: 1.5rem;
        }

        .card-custom .card-header {
            background-color: white;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 15px 15px 0 0;
            font-weight: 600;
        }

        .list-group-item-action-custom {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .list-group-item-action-custom:last-child {
            border-bottom: none;
        }

        .list-group-item-action-custom strong {
            color: var(--dark-color);
        }

        @media (max-width: 768px) {
            .patient-profile-header {
                text-align: center;
            }

            .patient-profile-avatar {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .modern-tabs .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }

            .d-flex.align-items-center {
                /* For avatar + name responsive */
                flex-direction: column;
                align-items: center !important;
            }
        }


        .qr-code-section {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--admin-border-color);
        }

        .qr-code-image svg {
            display: block;
            margin: 0 auto 1rem auto;
            max-width: 300px;
            height: auto;
            border: 1px solid var(--admin-border-color);
            padding: 8px;
            background-color: white;
            border-radius: var(--admin-radius-lg);
        }

        .qr-code-instructions {
            font-size: 0.9rem;
            color: var(--admin-text-secondary);
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
                <a href="{{ route('admin.Patients.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> قائمة المرضى
                </a>
            </div>
            <div class="pr-1 mb-3 mb-xl-0 mr-2">
                <a href="{{ route('admin.Patients.edit', $Patient->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> تعديل بيانات المريض
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row" data-aos="fade-in">

        {{-- Left Column --}}
        <div class="col-lg-4 col-md-12">
            <div class="patient-profile-header p-4 mb-4">
                <div class="d-flex align-items-center">
                    @if ($Patient->image)
                        <img src="{{ Url::asset('Dashboard/img/patients/' . $Patient->image->filename) }}"
                            class="patient-profile-avatar" alt="{{ $Patient->name }}">
                    @else
                        <img src="{{ Url::asset('Dashboard/img/doctor_default.png') }}" class="patient-profile-avatar"
                            alt="صورة افتراضية">
                    @endif
                    <div class="profile-text-info">
                        <h3 class="mb-1 font-weight-bold">{{ $Patient->name }}</h3>
                        <p class="mb-1 text-light op-8">
                            <i class="fas fa-id-card mr-1"></i>
                            {{ trans('patients.patient_id') }}: {{ $Patient->id }}
                        </p>
                        <span
                            class="badge badge-pill {{ $Patient->Gender == 1 ? 'bg-primary-transparent text-primary' : 'bg-pink-transparent text-pink' }}"
                            style="background-color: rgba(255,255,255,0.2); color:white;">
                            <i class="fas {{ $Patient->Gender == 1 ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                            {{ $Patient->Gender == 1 ? trans('patients.male') : trans('patients.female') }}
                        </span>
                    </div>
                </div>
                <div class="qr-code-section">
                    @if ($Patient && $Patient->id)
                        <div class="qr-code-image">
                            {!! $Patient->generateQrCodeSvg(200) !!}
                        </div>
                        <p class="qr-code-instructions">امسح الرمز لعرض ملف المريض الكامل</p>
                        <div class="qr-download-buttons mt-3 no-print">
                            <a href="#" class="btn btn-sm btn-outline-primary" style="color: white" id="downloadPNG">
                                <i class="fas fa-download"></i> تحميل PNG
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-success" style="color: white" id="downloadSVG">
                                <i class="fas fa-download"></i> تحميل SVG
                            </a>
                        </div>
                    @else
                        <p class="text-danger my-4">خطأ: لا يمكن إنشاء رمز QR لهذا المريض.</p>
                    @endif
                </div>
            </div>

            <div class="patient-stats p-3 mb-4">
                <h5 class="mb-3 text-center font-weight-bold text-primary">ملخص مالي</h5>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-card">
                            <h5 class="mb-1 text-primary font-weight-bold">
                                {{ number_format($invoices->sum('total_with_tax'), 2) }}</h5>
                            <small class="text-muted">إجمالي الفواتير</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-card">
                            <h5 class="mb-1 text-success font-weight-bold">
                                {{ number_format($receipt_accounts->sum('amount'), 2) }}</h5>
                            <small class="text-muted">إجمالي المدفوعات</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <h5 class="mb-1 text-info font-weight-bold">{{ $invoices->count() }}</h5>
                            <small class="text-muted">عدد الفواتير</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            @php
                                $balance = $invoices->sum('total_with_tax') - $receipt_accounts->sum('amount');
                            @endphp
                            <h5 class="mb-1 font-weight-bold {{ $balance >= 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format(abs($balance), 2) }}
                            </h5>
                            <small class="text-muted">{{ $balance >= 0 ? 'مدين' : 'دائن' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2 text-primary"></i> المعلومات الأساسية</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-id-card-alt mr-2 text-primary op-7"></i>
                                {{ trans('patients.national_id') }}</span>
                            <strong class="text-dark">{{ $Patient->national_id }}</strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-phone-alt mr-2 text-primary op-7"></i>
                                {{ trans('patients.phone') }}</span>
                            <strong class="text-dark">{{ $Patient->Phone }}</strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="far fa-envelope mr-2 text-primary op-7"></i>
                                {{ trans('patients.email') }}</span>
                            <strong class="text-dark">{{ $Patient->email }}</strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-birthday-cake mr-2 text-primary op-7"></i>
                                {{ trans('patients.Date_Birth') }}</span>
                            <strong
                                class="text-dark">{{ \Carbon\Carbon::parse($Patient->Date_Birth)->format('d M, Y') }}</strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-tint mr-2 text-danger op-7"></i>
                                {{ trans('patients.Blood_Group') }}</span>
                            <span class="badge badge-danger-light">{{ $Patient->Blood_Group }}</span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-map-marker-alt mr-2 text-primary op-7"></i>
                                {{ trans('patients.Address') }}</span>
                            <strong class="text-dark">{{ $Patient->Address }}</strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="far fa-calendar-alt mr-2 text-primary op-7"></i>
                                {{ trans('patients.date_added') }}</span>
                            <strong class="text-dark">{{ $Patient->created_at->format('d M, Y') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Chronic Diseases Card --}}
            <div class="card card-custom">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-notes-medical mr-2 text-danger"></i> الأمراض المزمنة</h5>
                </div>
                <div class="card-body">
                    @if ($Patient->diagnosedChronicDiseases && $Patient->diagnosedChronicDiseases->count() > 0)
                        @foreach ($Patient->diagnosedChronicDiseases as $diagnosedDisease)
                            <div class="mb-3 pb-2 border-bottom">
                                <h6><i class="fas fa-disease text-danger mr-1"></i> {{ $diagnosedDisease->name }}</h6>
                                <small class="d-block text-muted">
                                    <i class="fas fa-calendar-check mr-1"></i> تاريخ التشخيص:
                                    <strong>{{ $diagnosedDisease->pivot->diagnosed_at ? \Carbon\Carbon::parse($diagnosedDisease->pivot->diagnosed_at)->format('d M, Y') : 'N/A' }}</strong>
                                </small>
                                <small class="d-block text-muted">
                                    <i class="fas fa-user-md mr-1"></i> بواسطة:
                                    <strong>{{ $diagnosedDisease->pivot->diagnosed_by ?? 'N/A' }}</strong>
                                </small>
                                <small class="d-block text-muted">
                                    <i class="fas fa-thermometer-half mr-1"></i> الحالة الحالية:
                                    <strong>{{ \App\Models\PatientChronicDisease::getStatuses()[$diagnosedDisease->pivot->current_status] ?? 'N/A' }}</strong>
                                </small>
                                @if ($diagnosedDisease->pivot->treatment_plan)
                                    <small class="d-block text-muted">
                                        <i class="fas fa-pills mr-1"></i> خطة العلاج:
                                        <strong>{{ Str::limit($diagnosedDisease->pivot->treatment_plan, 50) }}</strong>
                                    </small>
                                @endif
                                @if ($diagnosedDisease->pivot->notes)
                                    <small class="d-block text-muted">
                                        <i class="far fa-comment-dots mr-1"></i> ملاحظات:
                                        <strong>{{ Str::limit($diagnosedDisease->pivot->notes, 50) }}</strong>
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state p-2 text-center">
                            <div class="empty-state-icon">
                                <i class="fas fa-file-medical-alt"></i>
                            </div>
                            <p class="text-muted">لا توجد أمراض مزمنة مسجلة لهذا المريض.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-lg-8 col-md-12">
            <div class="card card-custom">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav modern-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#invoices-tab" role="tab"
                                aria-controls="invoices-tab" aria-selected="true">
                                <i class="fas fa-file-invoice-dollar mr-1"></i> الفواتير
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#payments-tab" role="tab"
                                aria-controls="payments-tab" aria-selected="false">
                                <i class="fas fa-money-bill-wave mr-1"></i> المدفوعات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#account-tab" role="tab"
                                aria-controls="account-tab" aria-selected="false">
                                <i class="fas fa-calculator mr-1"></i> كشف حساب
                            </a>
                        </li>
                        <li class="nav-item"> {{-- New Tab for Admissions --}}
                            <a class="nav-link" data-toggle="tab" href="#admissions-tab" role="tab"
                                aria-controls="admissions-tab" aria-selected="false">
                                <i class="fas fa-procedures mr-1"></i> سجل الدخول
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#radiology-tab" role="tab"
                                aria-controls="radiology-tab" aria-selected="false">
                                <i class="fas fa-x-ray mr-1"></i> الأشعة
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#lab-tab" role="tab"
                                aria-controls="lab-tab" aria-selected="false">
                                <i class="fas fa-flask mr-1"></i> المختبر
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body tab-content p-0 tab-content-card">
                    <!-- الفواتير -->
                    <div class="tab-pane fade show active p-3" id="invoices-tab" role="tabpanel">
                        @if ($invoices->count() > 0)
                            <div class="table-responsive">
                                <table class="table data-table table-hover text-md-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الخدمة / المجموعة</th>
                                            <th>تاريخ الفاتورة</th>
                                            <th>المبلغ الإجمالي</th>
                                            <th>نوع الفاتورة</th>
                                            <th>خيارات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $invoice)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $invoice->Service->name ?? ($invoice->Group->name ?? 'N/A') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}
                                                </td>
                                                <td>{{ number_format($invoice->total_with_tax, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill {{ $invoice->type == 1 ? 'invoice-badge' : 'payment-badge' }}">
                                                        {{ $invoice->type == 1 ? 'نقدي' : 'آجل' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{-- Add view/print invoice button here --}}
                                                    <a href="#" class="btn btn-sm btn-outline-primary"
                                                        data-toggle="tooltip" title="عرض الفاتورة"><i
                                                            class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="3" class="text-right font-weight-bold">الإجمالي</th>
                                            <th colspan="3" class="font-weight-bold">
                                                {{ number_format($invoices->sum('total_with_tax'), 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                <h4>لا توجد فواتير مسجلة</h4>
                                <p class="text-muted">لم يتم تسجيل أي فواتير لهذا المريض حتى الآن</p>
                            </div>
                        @endif
                    </div>

                    <!-- المدفوعات -->
                    <div class="tab-pane fade p-3" id="payments-tab" role="tabpanel">
                        @if ($receipt_accounts->count() > 0)
                            <div class="table-responsive">
                                <table class="table data-table table-hover text-md-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>تاريخ السند</th>
                                            <th>المبلغ</th>
                                            <th>البيان</th>
                                            <th>خيارات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($receipt_accounts as $receipt_account)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($receipt_account->date)->format('Y-m-d') }}
                                                </td>
                                                <td>{{ number_format($receipt_account->amount, 2) }}</td>
                                                <td>{{ $receipt_account->description }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-success"
                                                        data-toggle="tooltip" title="عرض السند"><i
                                                            class="fas fa-receipt"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="2" class="text-right font-weight-bold">الإجمالي</th>
                                            <th colspan="3" class="font-weight-bold">
                                                {{ number_format($receipt_accounts->sum('amount'), 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-money-bill-wave"></i></div>
                                <h4>لا توجد مدفوعات مسجلة</h4>
                                <p class="text-muted">لم يتم تسجيل أي مدفوعات لهذا المريض حتى الآن</p>
                            </div>
                        @endif
                    </div>

                    <!-- كشف الحساب -->
                    <div class="tab-pane fade p-3" id="account-tab" role="tabpanel">
                        @if ($Patient_accounts->count() > 0)
                            <div class="table-responsive">
                                <table class="table data-table table-hover text-md-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>التاريخ</th>
                                            <th>البيان</th>
                                            <th>مدين</th>
                                            <th>دائن</th>
                                            <th>الرصيد</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $runningBalance = 0; @endphp
                                        @foreach ($Patient_accounts as $Patient_account)
                                            @php
                                                $currentDebit = $Patient_account->Debit ?? 0;
                                                $currentCredit = $Patient_account->credit ?? 0;
                                                $runningBalance += $currentDebit - $currentCredit;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($Patient_account->date)->format('Y-m-d') }}
                                                </td>
                                                <td>
                                                    @if ($Patient_account->invoice_id && $Patient_account->invoice)
                                                        فاتورة خدمة:
                                                        {{ $Patient_account->invoice->Service->name ?? ($Patient_account->invoice->Group->name ?? 'N/A') }}
                                                    @elseif($Patient_account->receipt_id && $Patient_account->ReceiptAccount)
                                                        سند قبض: {{ $Patient_account->ReceiptAccount->description }}
                                                    @elseif($Patient_account->Payment_id && $Patient_account->PaymentAccount)
                                                        سند صرف: {{ $Patient_account->PaymentAccount->description }}
                                                    @else
                                                        حركة مالية
                                                    @endif
                                                </td>
                                                <td>{{ number_format($currentDebit, 2) }}</td>
                                                <td>{{ number_format($currentCredit, 2) }}</td>
                                                <td
                                                    class="{{ $runningBalance >= 0 ? 'text-danger' : 'text-success' }} font-weight-bold">
                                                    {{ number_format(abs($runningBalance), 2) }}
                                                    <small>({{ $runningBalance >= 0 ? 'مدين' : 'دائن' }})</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="balance-card p-3 mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bold">الرصيد النهائي:</h5>
                                    @php
                                        // Recalculate final balance based on displayed transactions or use overall summary
                                        $finalBalance =
                                            $Patient_accounts->sum('Debit') - $Patient_accounts->sum('credit');
                                    @endphp
                                    <h3
                                        class="mb-0 font-weight-bold {{ $finalBalance >= 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format(abs($finalBalance), 2) }}
                                        <small class="text-muted">({{ $finalBalance >= 0 ? 'مدين' : 'دائن' }})</small>
                                    </h3>
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-calculator"></i></div>
                                <h4>لا توجد حركات مالية</h4>
                                <p class="text-muted">لم يتم تسجيل أي حركات مالية لهذا المريض حتى الآن</p>
                            </div>
                        @endif
                    </div>

                    <!-- Admissions History Tab -->
                    <div class="tab-pane fade p-3" id="admissions-tab" role="tabpanel">
                        @if ($Patient->admissions && $Patient->admissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table data-table table-hover text-md-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>تاريخ الدخول</th>
                                            <th>تاريخ الخروج</th>
                                            <th>الطبيب المعالج</th>
                                            <th>القسم</th>
                                            <th>الغرفة/السرير</th>
                                            <th>سبب الدخول</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Patient->admissions as $admission)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($admission->admission_date)->format('Y-m-d H:i') }}
                                                </td>
                                                <td>{{ $admission->discharge_date ? \Carbon\Carbon::parse($admission->discharge_date)->format('Y-m-d H:i') : 'مازال مقيم' }}
                                                </td>
                                                <td>{{ $admission->doctor->name ?? 'غير محدد' }}</td>
                                                <td>{{ $admission->bed->room->section->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    {{ $admission->bed->room->room_number ?? 'N/A' }} -
                                                    {{ $admission->bed->bed_number ?? 'N/A' }}
                                                </td>
                                                <td>{{ Str::limit($admission->reason_for_admission, 40) ?? 'غير محدد' }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill {{ $admission->status == \App\Models\PatientAdmission::STATUS_ADMITTED && !$admission->discharge_date ? 'badge-success-light' : 'badge-warning-light' }}">
                                                        {{ \App\Models\PatientAdmission::getAdmissionStatusText($admission->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-procedures"></i></div>
                                <h4>لا يوجد سجل دخول للمريض</h4>
                                <p class="text-muted">لم يتم تسجيل أي دخول أو خروج لهذا المريض حتى الآن.</p>
                            </div>
                        @endif
                    </div>

                    <!-- الأشعة -->
                    <div class="tab-pane fade p-3" id="radiology-tab" role="tabpanel">
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fas fa-x-ray"></i></div>
                            <h4>قسم الأشعة قيد التطوير</h4>
                            <p class="text-muted">سيتم إضافة وظائف الأشعة قريبًا في تحديثات النظام القادمة</p>
                            <button class="btn btn-primary disabled"><i class="fas fa-clock"></i> قريبًا</button>
                        </div>
                    </div>

                    <!-- المختبر -->
                    <div class="tab-pane fade p-3" id="lab-tab" role="tabpanel">
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fas fa-flask"></i></div>
                            <h4>قسم المختبر قيد التطوير</h4>
                            <p class="text-muted">سيتم إضافة وظائف المختبر قريبًا في تحديثات النظام القادمة</p>
                            <button class="btn btn-primary disabled"><i class="fas fa-clock"></i> قريبًا</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <a href="#" class="floating-action-btn" data-toggle="tooltip" title="إجراء سريع">
        <i class="fas fa-plus"></i>
    </a>

@endsection

@section('js')
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    {{-- DataTables JS --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        $(document).ready(function() {
            // Initialize DataTables for all tables with class 'data-table'
            $('.data-table').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json",
                    searchPlaceholder: "ابحث هنا..."
                },
                // dom: '<"top"f>rt<"bottom"lip><"clear">', // Default is fine
                pageLength: 10, // Or any other number
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],

            });

            // Tooltip initialization
            $('[data-toggle="tooltip"]').tooltip();

            // Smooth scroll for tabs and activate tab
            $('.modern-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                // If you want to scroll to the tab content when it's shown (optional)
                // let target = $(e.target).attr("href"); // activated tab
                // $(target).get(0).scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            // Handle deep linking for tabs
            let hash = window.location.hash;
            if (hash) {
                $('.modern-tabs a[href="' + hash + '"]').tab('show');
                // Scroll to tab content after a short delay to ensure it's visible
                setTimeout(function() {
                    $(hash).get(0).scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }

            // Update hash on tab change
            $('.modern-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                window.location.hash = e.target.hash;
                // Optional: Scroll to top of tab content when tab changes
                // $(e.target.hash).scrollTop(0);
            });


        });

        document.addEventListener('DOMContentLoaded', function() {
            // تحميل QR كصورة PNG
            document.getElementById('downloadPNG').addEventListener('click', function(e) {
                e.preventDefault();
                const svg = document.querySelector('.qr-code-image svg');
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const data = new XMLSerializer().serializeToString(svg);
                const DOMURL = window.URL || window.webkitURL || window;

                const img = new Image();
                const svgBlob = new Blob([data], {type: 'image/svg+xml;charset=utf-8'});
                const url = DOMURL.createObjectURL(svgBlob);

                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);
                    DOMURL.revokeObjectURL(url);

                    const png = canvas.toDataURL('image/png');
                    const downloadLink = document.createElement('a');
                    downloadLink.href = png;
                    downloadLink.download = 'patient_qr_{{ $Patient->id }}.png';
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                };

                img.src = url;
            });

            // تحميل QR كـ SVG
            document.getElementById('downloadSVG').addEventListener('click', function(e) {
                e.preventDefault();
                const svg = document.querySelector('.qr-code-image svg').outerHTML;
                const blob = new Blob([svg], {type: 'image/svg+xml'});
                const url = URL.createObjectURL(blob);
                const downloadLink = document.createElement('a');
                downloadLink.href = url;
                downloadLink.download = 'patient_qr_{{ $Patient->id }}.svg';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                URL.revokeObjectURL(url);
            });

            @if (session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "bottom",
                    autohide: true,
                    timeout: 5000
                });
            @endif

            @if (session('error'))
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                    type: "error",
                    position: "bottom",
                    autohide: true,
                    timeout: 7000
                });
            @endif
        });
    </script>
@endsection
