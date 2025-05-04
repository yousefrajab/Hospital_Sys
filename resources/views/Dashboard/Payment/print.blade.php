@extends('Dashboard.layouts.master')
@section('title')
    سند صرف - طباعة
@stop
@section('css')
    <style>
        @media print {
            #print_Button, .breadcrumb-header {
                display: none !important;
            }
            body {
                background: #fff !important;
                font-size: 14px;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .invoice-title {
                font-size: 24px;
                color: #000 !important;
            }
        }
        .invoice-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-title {
            color: #2c3e50;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
        }
        .billed-from h6 {
            font-weight: 600;
            color: #2c3e50;
        }
        .invoice-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .invoice-info-row span:first-child {
            font-weight: 600;
            color: #2c3e50;
        }
        .table-invoice {
            border: 1px solid #eee;
        }
        .table-invoice thead th {
            background-color: #f8f9fa;
            font-weight: 700;
        }
        .total-amount {
            font-size: 18px;
            font-weight: 700;
            color: #e74c3c;
        }
        .watermark {
            position: fixed;
            opacity: 0.1;
            font-size: 80px;
            color: #000;
            transform: rotate(-45deg);
            z-index: -1;
            left: 25%;
            top: 40%;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">سند صرف</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ طباعة السند</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="watermark">سند صرف</div>

                        <div class="invoice-header">
                            <div class="text-center">
                                <img src="{{ asset('Dashboard/img/brand/logohos.png') }}" alt="شعار المستشفى" style="height: 80px; margin-bottom: 15px;">
                                <h1 class="invoice-title">سند صرف</h1>
                                <p class="invoice-number">رقم السند: #{{ $payment_account->id }}</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="billed-from">
                                        <h6>مستشفى الحياة</h6>
                                        <p>201 شارع المهندسين، القاهرة<br>
                                            هاتف: 011111111<br>
                                            البريد الإلكتروني: Hospital@gmail.com</p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="billed-to">
                                        <h6>معلومات المريض</h6>
                                        <p>{{ $payment_account->patients->name }}<br>
                                            رقم الملف: {{ $payment_account->patients->id }}<br>
                                            الهاتف: {{ $payment_account->patients->Phone ?? 'غير متوفر' }}<br>
                                            الاميل: {{ $payment_account->patients->email ?? 'غير متوفر' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mg-t-20">
                            <div class="col-md-6">
                                <label class="tx-gray-600">معلومات السند</label>
                                <p class="invoice-info-row"><span>تاريخ الإصدار:</span> <span>{{ $payment_account->date }}</span></p>
                                <p class="invoice-info-row"><span>وقت الإصدار:</span> <span>{{ now()->format('h:i A') }}</span></p>
                            </div>
                            {{-- <div class="col-md-6 text-right">
                                <label class="tx-gray-600">المعلومات المالية</label>
                                <div class="col">
                                    <label>طريقة الدفع</label>
                                    <select class="form-control">
                                        <option value="">-- اختار من القائمة --</option>
                                        <option value="نقدي">نقدي</option>
                                        <option value="آجل">آجل</option>
                                    </select>
                                </div>

                                <p class="invoice-info-row"><span>حالة السند:</span> <span class="badge badge-success">مكتمل</span></p>
                            </div> --}}
                        </div>

                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th class="wd-5p">#</th>
                                        <th class="wd-60p">الوصف</th>
                                        <th class="tx-center wd-35p">المبلغ (شيكل)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $payment_account->description ?? 'لا توجد ملاحظات' }}</td>
                                        <td class="tx-center">{{ number_format($payment_account->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-left tx-uppercase tx-bold">المبلغ الإجمالي</td>
                                        <td class="tx-center total-amount">{{ number_format($payment_account->amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mg-t-40">
                            <div class="col-md-4">
                                <div class="signature-box">
                                    <p class="border-bottom">توقيع المحصل</p>
                                    <p class="text-muted">الاسم: .............................</p>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-4 text-right">
                                <div class="signature-box">
                                    <p class="border-bottom">توقيع المدير المالي</p>
                                    <p class="text-muted">الاسم: .............................</p>
                                </div>
                            </div>
                        </div>

                        <div class="mg-t-40">
                            <p class="tx-10 text-muted text-center">
                                شكراً لثقتكم بمستشفى الحياة - هذا السند صالح كإثبات دفع رسمي<br>
                                للاستفسارات يرجى الاتصال على: 011111111 - ساعات العمل: 8 صباحاً حتى 10 مساءً
                            </p>
                        </div>

                        <hr class="mg-b-40">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-circle-left"></i> رجوع
                            </a>
                            <button class="btn btn-danger" id="print_Button" onclick="printDiv()">
                                <i class="fas fa-print"></i> طباعة السند
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <script>
        function printDiv() {
            // Hide buttons before printing
            $('#print_Button').hide();
            $('.breadcrumb-header').hide();

            // Open print dialog
            window.print();

            // Show buttons after printing is done (if not canceled)
            setTimeout(function() {
                $('#print_Button').show();
                $('.breadcrumb-header').show();
            }, 500);
        }

        // Automatically trigger print when page loads (optional)
        // window.addEventListener('load', function() {
        //     printDiv();
        // });
    </script>
@endsection
