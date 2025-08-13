@extends('Dashboard.layouts.master')
@section('title')
    سند قبض - طباعة #{{ $receipt->id }}
@stop
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            /* خطوط عربية مناسبة للطباعة */
        }

        @media print {

            #print_Button,
            .breadcrumb-header,
            .main-header,
            .main-sidebar,
            .main-footer {
                /* إخفاء المزيد من العناصر عند الطباعة */
                display: none !important;
            }

            body {
                background: #fff !important;
                font-size: 12px;
                /* يمكن تعديل حجم الخط للطباعة */
                margin: 0;
                /* إزالة هوامش الجسم الافتراضية */
                padding: 0;
                -webkit-print-color-adjust: exact !important;
                /* لضمان طباعة الألوان والخلفيات في Chrome */
                color-adjust: exact !important;
                /* المعيار الجديد */
            }

            .main-content-body-invoice {
                margin: 0 !important;
                padding: 10mm !important;
                /* هوامش الصفحة عند الطباعة */
                width: 100% !important;
                box-shadow: none !important;
                border: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
                margin-bottom: 0 !important;
            }

            .card-body {
                padding: 0 !important;
            }

            .invoice-title {
                font-size: 22px !important;
                /* تصغير حجم الخط قليلاً للطباعة */
                color: #000 !important;
            }

            .watermark {
                /* يمكنك إبقاء العلامة المائية أو إزالتها للطباعة */
                /* display: none !important; */
                opacity: 0.08 !important;
                font-size: 70px !important;
            }

            .table-invoice th,
            .table-invoice td {
                font-size: 11px !important;
                /* تصغير خط الجدول */
                padding: 6px 8px !important;
            }

            .total-amount {
                font-size: 16px !important;
            }

            .signature-box p {
                font-size: 11px !important;
            }

            .mg-t-40,
            .mg-b-40 {
                margin-top: 20px !important;
                margin-bottom: 20px !important;
            }

            .tx-10 {
                font-size: 9px !important;
            }
        }

        .invoice-header {
            border-bottom: 2px solid #dee2e6;
            /* لون أفتح قليلاً للخط */
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .invoice-title {
            color: #172b4d;
            /* لون أغمق وأكثر رسمية */
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.75rem;
        }

        .billed-from h6,
        .billed-to h6 {
            font-weight: 600;
            color: #32325d;
            /* لون أغمق للعناوين الفرعية */
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .billed-from p,
        .billed-to p {
            font-size: 0.875rem;
            line-height: 1.6;
            color: #525f7f;
            /* لون نص أفتح قليلاً */
            margin-bottom: 0;
        }

        .invoice-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .invoice-info-row span:first-child {
            font-weight: 600;
            color: #32325d;
        }

        .invoice-info-row span:last-child {
            color: #525f7f;
        }

        .table-invoice {
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .table-invoice thead th {
            background-color: #f6f9fc;
            /* خلفية أفتح لرأس الجدول */
            font-weight: 600;
            color: #525f7f;
            border-bottom-width: 2px;
            border-color: #dee2e6;
        }

        .table-invoice td {
            color: #525f7f;
        }

        .total-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--bs-danger, #dc3545);
            /* استخدام متغير Bootstrap إذا كان متاحاً */
        }

        .watermark {
            position: fixed;
            opacity: 0.05;
            /* أخف قليلاً */
            font-size: 90px;
            font-weight: bold;
            color: #adb5bd;
            /* لون رمادي فاتح */
            transform: rotate(-45deg);
            z-index: 0;
            /* خلف المحتوى */
            left: 50%;
            top: 50%;
            transform-origin: center center;
            pointer-events: none;
            /* لتجاهل الفأرة */
            white-space: nowrap;
        }

        .signature-box {
            margin-top: 3rem;
        }

        .signature-box p.border-bottom {
            border-bottom: 1px solid #adb5bd !important;
            /* خط أفتح للتوقيع */
            padding-bottom: 2.5rem;
            /* مساحة أكبر للتوقيع */
            margin-bottom: 0.5rem;
        }

        .signature-box p.text-muted {
            font-size: 0.8rem;
        }

        .invoice-footer-notes {
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
            margin-top: 2rem;
        }

        .btn-primary,
        .btn-secondary,
        .btn-danger {
            /* تحسين مظهر الأزرار */
            border-radius: 0.3rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('AdminSb.accounts') }}</h4><span
                    class="text-muted mt-1 tx-13 ms-2 me-2 mb-0">/</span>
                <h4 class="content-title mb-0 my-auto">{{ trans('AdminSb.receipts') }}</h4><span
                    class="text-muted mt-1 tx-13 ms-2 me-2 mb-0">/</span>
                <span
                    class="text-muted mt-1 tx-13">{{ trans('AdminSb.print_receipt_number', ['id' => $receipt->id]) }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="printArea">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="watermark">
                            {{ $settings['site_name_' . App::getLocale()] ?? config('app.name', 'Hospital Name') }}</div>

                        <div class="invoice-header">
                            <div class="text-center mb-4">
                                <img src="{{ $settings['logo_path'] ?? asset('Dashboard/img/brand/logohos.png') }}"
                                    alt="{{ trans('AdminSb.hospital_logo') }}"
                                    style="max-height: 90px; margin-bottom: 1rem;">
                            </div>
                            <h1 class="invoice-number mb-0" style="font-size: 50px">{{ trans('AdminSb.receipt_voucher') }}<p
                                    class="invoice-number mb-0" style="font-size: 25px"><strong
                                        style="font-size: 25px">{{ trans('AdminSb.voucher_number_colon') }}</strong>
                                    #{{ $receipt->id }}</p>
                            </h1><br>

                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="billed-from">
                                        <h6>{{ trans('AdminSb.from_colon') }}</h6>
                                        <p>
                                            <strong>{{ $settings['site_name_' . App::getLocale()] ?? config('app.name', 'Life Hospital') }}</strong><br>
                                            {{ $settings['address'] ?? '201 Mohandseen St, Cairo' }}<br>
                                            {{ trans('AdminSb.phone_colon') }} {{ $settings['phone'] ?? '011111111' }}<br>
                                            {{ trans('AdminSb.email_colon') }}
                                            {{ $settings['email'] ?? 'Hospital@gmail.com' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="billed-to">
                                        <h6>{{ trans('AdminSb.to_patient_colon') }}</h6>
                                        @if ($receipt->patients)
                                            <p>
                                                <strong>{{ $receipt->patients->name }}</strong><br>
                                                {{ trans('AdminSb.file_number_colon') }} {{ $receipt->patients->id }}<br>
                                                @if ($receipt->patients->Phone)
                                                    {{ trans('AdminSb.phone_colon') }} {{ $receipt->patients->Phone }}<br>
                                                @endif
                                                @if ($receipt->patients->email)
                                                    {{ trans('AdminSb.email_colon') }} {{ $receipt->patients->email }}
                                                @endif
                                            </p>
                                        @else
                                            <p class="text-danger">{{ trans('AdminSb.patient_data_not_available') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mg-t-20">
                            <div class="col-md-6">
                                <label class="tx-gray-600 fw-bold">{{ trans('AdminSb.voucher_information_colon') }}</label>
                                <p class="invoice-info-row"><span>{{ trans('AdminSb.issue_date_colon') }}</span>
                                    <span>{{ \Carbon\Carbon::parse($receipt->date)->translatedFormat('d F Y') }}</span>
                                </p>
                                <p class="invoice-info-row"><span>{{ trans('AdminSb.print_time_colon') }}</span>
                                    <span>{{ now()->translatedFormat('d F Y, h:i A') }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="wd-10p text-center">#</th>
                                        <th class="wd-60p">{{ trans('AdminSb.description_service') }}</th>
                                        <th class="tx-center wd-30p">
                                            {{ trans('AdminSb.amount_currency', ['currency' => config('app.currency_symbol', 'NIS')]) }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>
                                            {{ $receipt->description ?: trans('AdminSb.cash_payment') }}
                                            @if ($receipt->service)
                                                <br><small
                                                    class="text-muted d-block mt-1">({{ trans('AdminSb.service_provided_colon') }}
                                                    <strong>{{ $receipt->service->name }}</strong>)</small>
                                            @elseif($receipt->group)
                                                <br><small
                                                    class="text-muted d-block mt-1">({{ trans('AdminSb.service_package_colon') }}
                                                    <strong>{{ $receipt->group->name }}</strong>)</small>
                                            @endif
                                        </td>
                                        <td class="tx-center">{{ number_format($receipt->amount, 2) }}</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td colspan="2" class="text-start tx-uppercase fw-bold">
                                            {{ trans('AdminSb.total_amount_received') }}</td>
                                        <td class="tx-center total-amount">{{ number_format($receipt->amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mg-t-40">
                            <div class="col-md-5">
                                <div class="signature-box">
                                    <p class="fw-bold">{{ trans('AdminSb.recipient_signature_cashier') }}</p>
                                    <p class="border-bottom"> </p>
                                    <p class="text-muted">{{ trans('AdminSb.name_colon') }} .............................
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-5 offset-md-2 text-md-end">
                                <div class="signature-box">
                                    <p class="fw-bold">{{ trans('AdminSb.patient_guardian_signature') }}</p>
                                    <p class="border-bottom"> </p>
                                    <p class="text-muted">{{ trans('AdminSb.name_colon') }} .............................
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mg-t-40 invoice-footer-notes">
                            <p class="tx-10 text-muted text-center mb-0">
                                {{ trans('AdminSb.thank_you_message') }}<br>
                                {{ $settings['footer_notes_receipt'] ?? trans('AdminSb.footer_note_default') }}
                            </p>
                        </div>

                        <hr class="mg-b-40 mg-t-30">

                        <div class="d-print-none d-flex justify-content-end">
                            <a href="{{ route('admin.Receipt.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> {{ trans('AdminSb.back_to_list') }}
                            </a>
                            <button class="btn btn-primary" id="print_Button" onclick="printReceipt()">
                                <i class="fas fa-print me-1"></i> {{ trans('AdminSb.print_voucher') }}
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
    @parent {{-- إذا كان الـ layout الرئيسي يحتوي على jQuery --}}
    <script>
        function printReceipt() {
            // يمكنك إخفاء عناصر معينة قبل الطباعة إذا أردت
            // مثال: $('.main-header').hide();
            // $('.main-sidebar').hide();

            var printContents = document.getElementById('printArea').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // لإعادة تحميل الصفحة واستعادة العناصر المخفية و الـ listeners
        }

        // يمكنك إضافة event listener لزر الطباعة إذا لم يكن onclick كافياً
        // $(document).ready(function() {
        //     $('#print_Button').on('click', function() {
        //         printReceipt();
        //     });
        // });
    </script>
@endsection
