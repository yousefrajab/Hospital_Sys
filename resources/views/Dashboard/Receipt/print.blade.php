@extends('Dashboard.layouts.master')
@section('title')
    سند قبض - طباعة #{{ $receipt->id }}
@stop
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Cairo', 'Tajawal', sans-serif; /* خطوط عربية مناسبة للطباعة */
        }
        @media print {
            #print_Button, .breadcrumb-header, .main-header, .main-sidebar, .main-footer { /* إخفاء المزيد من العناصر عند الطباعة */
                display: none !important;
            }
            body {
                background: #fff !important;
                font-size: 12px; /* يمكن تعديل حجم الخط للطباعة */
                margin: 0; /* إزالة هوامش الجسم الافتراضية */
                padding: 0;
                -webkit-print-color-adjust: exact !important; /* لضمان طباعة الألوان والخلفيات في Chrome */
                color-adjust: exact !important; /* المعيار الجديد */
            }
            .main-content-body-invoice {
                margin: 0 !important;
                padding: 10mm !important; /* هوامش الصفحة عند الطباعة */
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
                font-size: 22px !important; /* تصغير حجم الخط قليلاً للطباعة */
                color: #000 !important;
            }
            .watermark {
                /* يمكنك إبقاء العلامة المائية أو إزالتها للطباعة */
                /* display: none !important; */
                 opacity: 0.08 !important;
                 font-size: 70px !important;
            }
            .table-invoice th, .table-invoice td {
                font-size: 11px !important; /* تصغير خط الجدول */
                padding: 6px 8px !important;
            }
            .total-amount {
                font-size: 16px !important;
            }
            .signature-box p {
                font-size: 11px !important;
            }
            .mg-t-40, .mg-b-40 {
                margin-top: 20px !important;
                margin-bottom: 20px !important;
            }
            .tx-10 { font-size: 9px !important; }
        }
        .invoice-header {
            border-bottom: 2px solid #dee2e6; /* لون أفتح قليلاً للخط */
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .invoice-title {
            color: #172b4d; /* لون أغمق وأكثر رسمية */
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.75rem;
        }
        .billed-from h6, .billed-to h6 {
            font-weight: 600;
            color: #32325d; /* لون أغمق للعناوين الفرعية */
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        .billed-from p, .billed-to p {
            font-size: 0.875rem;
            line-height: 1.6;
            color: #525f7f; /* لون نص أفتح قليلاً */
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
            background-color: #f6f9fc; /* خلفية أفتح لرأس الجدول */
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
            color: var(--bs-danger, #dc3545); /* استخدام متغير Bootstrap إذا كان متاحاً */
        }
        .watermark {
            position: fixed;
            opacity: 0.05; /* أخف قليلاً */
            font-size: 90px;
            font-weight: bold;
            color: #adb5bd; /* لون رمادي فاتح */
            transform: rotate(-45deg);
            z-index: 0; /* خلف المحتوى */
            left: 50%;
            top: 50%;
            transform-origin: center center;
            pointer-events: none; /* لتجاهل الفأرة */
            white-space: nowrap;
        }
        .signature-box {
            margin-top: 3rem;
        }
        .signature-box p.border-bottom {
            border-bottom: 1px solid #adb5bd !important; /* خط أفتح للتوقيع */
            padding-bottom: 2.5rem; /* مساحة أكبر للتوقيع */
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
        .btn-primary, .btn-secondary, .btn-danger { /* تحسين مظهر الأزرار */
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
                <h4 class="content-title mb-0 my-auto">الحسابات</h4><span class="text-muted mt-1 tx-13 ms-2 me-2 mb-0">/</span> {{-- استخدام ms-2 me-2 بدلاً من mr-2 --}}
                <h4 class="content-title mb-0 my-auto">سندات القبض</h4><span class="text-muted mt-1 tx-13 ms-2 me-2 mb-0">/</span>
                <span class="text-muted mt-1 tx-13">طباعة السند #{{ $receipt->id }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="printArea"> {{-- تغيير ID إذا أردت --}}
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="watermark">{{ $settings['site_name_ar'] ?? config('app.name', 'اسم المستشفى') }}</div>

                        <div class="invoice-header">
                            <div class="text-center mb-4">
                                {{-- يمكنك جلب الشعار من الإعدادات إذا كان متاحاً --}}
                                <img src="{{ $settings['logo_path'] ?? asset('Dashboard/img/brand/logohos.png') }}" alt="شعار المستشفى" style="max-height: 90px; margin-bottom: 1rem;">

                            </div>
                            <h1 class="invoice-number mb-0"style="font-size: 50px">سـنـد قـبـض<p class="invoice-number mb-0" style="font-size: 25px"><strong style="font-size: 25px">رقم السند:</strong> #{{ $receipt->id }}</p></h1><br>


                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="billed-from">
                                        <h6>من:</h6>
                                        <p>
                                            <strong>{{ $settings['site_name_ar'] ?? config('app.name', 'مستشفى الحياة') }}</strong><br>
                                            {{ $settings['address'] ?? '201 شارع المهندسين، القاهرة' }}<br>
                                            هاتف: {{ $settings['phone'] ?? '011111111' }}<br>
                                            البريد الإلكتروني: {{ $settings['email'] ?? 'Hospital@gmail.com' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end"> {{-- استخدام text-md-end لمحاذاة اليمين في الشاشات المتوسطة فأكبر --}}
                                    <div class="billed-to">
                                        <h6>إلى (المريض):</h6>
                                        @if($receipt->patients)
                                        <p>
                                            <strong>{{ $receipt->patients->name }}</strong><br>
                                            رقم الملف: {{ $receipt->patients->id }}<br>
                                            @if($receipt->patients->Phone)الهاتف: {{ $receipt->patients->Phone }}<br>@endif
                                            @if($receipt->patients->email)البريد الإلكتروني: {{ $receipt->patients->email }}@endif
                                        </p>
                                        @else
                                        <p class="text-danger">بيانات المريض غير متوفرة</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                             {{-- <div> --}}
                            {{-- </div> --}}
                        </div>

                        <div class="row mg-t-20">
                            <div class="col-md-6">
                                <label class="tx-gray-600 fw-bold">معلومات السند:</label>
                                <p class="invoice-info-row"><span>تاريخ الإصدار:</span> <span>{{ \Carbon\Carbon::parse($receipt->date)->translatedFormat('d F Y') }}</span></p>
                                <p class="invoice-info-row"><span>وقت الطباعة:</span> <span>{{ now()->translatedFormat('d F Y, h:i A') }}</span></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                {{-- يمكنك إضافة معلومات إضافية هنا إذا أردت --}}
                            </div>
                        </div>

                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead class="table-light"> {{-- استخدام table-light لرأس جدول أفتح --}}
                                    <tr>
                                        <th class="wd-10p text-center">#</th>
                                        <th class="wd-60p">البيان / الخدمة</th>
                                        <th class="tx-center wd-30p">المبلغ ({{ config('app.currency_symbol', 'ر.س') }})</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>
                                            {{ $receipt->description ?: 'دفعة نقدية' }}
                                            @if($receipt->service)
                                                <br><small class="text-muted d-block mt-1">(الخدمة المقدمة: <strong>{{ $receipt->service->name }}</strong>)</small>
                                            @elseif($receipt->group)
                                                <br><small class="text-muted d-block mt-1">(باقة الخدمات: <strong>{{ $receipt->group->name }}</strong>)</small>
                                            @endif
                                        </td>
                                        <td class="tx-center">{{ number_format($receipt->amount, 2) }}</td>
                                    </tr>
                                    {{-- يمكنك إضافة صفوف أخرى هنا إذا كان السند يغطي عدة بنود --}}
                                    <tr class="table-light">
                                        <td colspan="2" class="text-start tx-uppercase fw-bold">المبلغ الإجمالي المستلم</td>
                                        <td class="tx-center total-amount">{{ number_format($receipt->amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mg-t-40">
                            <div class="col-md-5">
                                <div class="signature-box">
                                    <p class="fw-bold">توقيع المستلم (أمين الصندوق):</p>
                                    <p class="border-bottom"> </p>
                                    <p class="text-muted">الاسم: .............................</p>
                                </div>
                            </div>
                            <div class="col-md-5 offset-md-2 text-md-end"> {{-- تعديل لـ offset-md-2 ومحاذاة --}}
                                <div class="signature-box">
                                    <p class="fw-bold">توقيع المريض / ولي الأمر:</p>
                                    <p class="border-bottom"> </p>
                                    <p class="text-muted">الاسم: .............................</p>
                                </div>
                            </div>
                        </div>

                        <div class="mg-t-40 invoice-footer-notes">
                            <p class="tx-10 text-muted text-center mb-0">
                                شكراً لتعاملكم معنا - هذا السند يعتبر إيصالاً رسمياً بالمبلغ المذكور أعلاه.<br>
                                {{ $settings['footer_notes_receipt'] ?? 'للاستفسارات، يرجى التواصل مع قسم الحسابات.' }}
                            </p>
                        </div>

                        <hr class="mg-b-40 mg-t-30">

                        <div class="d-print-none d-flex justify-content-end"> {{-- إخفاء الأزرار عند الطباعة --}}
                            <a href="{{ route('admin.Receipt.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> رجوع للقائمة
                            </a>
                            <button class="btn btn-primary" id="print_Button" onclick="printReceipt()">
                                <i class="fas fa-print me-1"></i> طباعة السند
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
