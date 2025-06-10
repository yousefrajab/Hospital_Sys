<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سند قبض رقم #{{ $receiptAccount->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&family=Cairo:wght@400;600;700&display=swap"
        rel="stylesheet">
    {{-- Bootstrap 5 for basic grid and utilities if needed, but mostly custom print styles --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            /* White background for printing */
            color: #000;
            font-size: 12pt;
            /* Standard font size for print */
            line-height: 1.6;
        }

        .print-container {
            width: 210mm;
            /* A4 width */
            min-height: 297mm;
            /* A4 height, or adjust to content */
            padding: 15mm;
            /* Margins for content */
            margin: 10mm auto;
            /* Center on screen */
            background: white;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.15);
            /* Shadow for screen view */
            position: relative;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .receipt-header .logo img {
            max-height: 70px;
            max-width: 180px;
        }

        .receipt-header .hospital-info-print {
            /* Renamed to avoid conflict if other CSS is present */
            text-align: left;
            /* RTL: text-align: right */
        }

        .receipt-header .hospital-info-print h3 {
            margin: 0 0 5px 0;
            font-size: 1.4em;
            font-weight: 700;
            color: #00796B;
            /* Teal color from previous invoice example */
        }

        .receipt-header .hospital-info-print p {
            margin: 2px 0;
            font-size: 0.85em;
        }

        .receipt-title-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .receipt-title-section h1 {
            font-family: 'Cairo', sans-serif;
            /* Distinct font for title */
            font-size: 2em;
            font-weight: 700;
            color: #111;
            margin: 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
            display: inline-block;
        }

        .receipt-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* Two columns */
            gap: 20px;
            margin-bottom: 25px;
            font-size: 0.95em;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #fdfdfd;
        }

        .receipt-details-grid .detail-group h5 {
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 8px;
            color: #00796B;
        }

        .receipt-details-grid p {
            margin-bottom: 5px;
            display: flex;
            /* For aligning label and value */
        }

        .receipt-details-grid strong {
            font-weight: 600;
            min-width: 100px;
            /* Adjust as needed for alignment */
            display: inline-block;
            color: #555;
        }

        .receipt-details-grid span {
            color: #000;
        }


        .receipt-body {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .receipt-body h4 {
            font-family: 'Cairo', sans-serif;
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 15px;
            color: #222;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .receipt-body .amount-section {
            font-size: 1.5em;
            /* Larger font for amount */
            font-weight: 700;
            color: var(--deptdetail-primary-color, #00796B);
            /* Using a defined primary color */
            margin-bottom: 15px;
            text-align: center;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .receipt-body .amount-section .currency {
            font-size: 0.7em;
            font-weight: normal;
            color: #555;
        }

        .receipt-body .description-section p {
            font-size: 1em;
            line-height: 1.7;
            white-space: pre-wrap;
            /* To respect new lines in description */
        }


        .receipt-footer {
            text-align: center;
            font-size: 0.85em;
            color: #666;
            padding-top: 25px;
            border-top: 1px solid #ccc;
            margin-top: 30px;
        }

        .receipt-footer p {
            margin: 3px 0;
        }

        .print-button-container {
            text-align: center;
            margin-bottom: 20px;
            /* Space below button on screen */
        }

        .print-button {
            padding: 12px 30px;
            font-size: 1.1em;
            font-family: 'Cairo', sans-serif;
            background-color: #00796B;
            /* Teal */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .print-button:hover {
            background-color: #004D40;
            /* Darker Teal */
        }

        .print-button i {
            margin-inline-end: 8px;
        }

        @media print {

            html,
            body {
                font-size: 10pt;
                /* Standard print size */
                background-color: #fff !important;
                /* Force white background */
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .print-container {
                margin: 0;
                padding: 10mm;
                /* Adjust print margins */
                box-shadow: none;
                border: none;
                width: 100%;
                min-height: auto;
                /* Let content define height */
            }

            .print-button-container {
                display: none !important;
            }

            .receipt-header {
                border-bottom: 1px solid #000;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }

            .receipt-body .amount-section {
                background-color: #f0f0f0 !important;
                /* Ensure light bg prints for emphasis */
            }

            .receipt-footer {
                /* position: fixed; /* Optional: try to fix footer to bottom of page */
                /* bottom: 10mm; */
                /* left: 10mm; */
                /* right: 10mm; */
            }
        }
    </style>
</head>

<body>


    <div class="print-container">
        <header class="receipt-header">
            <div class="logo">
                @if (!empty($hospitalSettings['logo']))
                    <img src="{{ $hospitalSettings['logo'] }}" alt="{{ $hospitalSettings['name'] ?? 'شعار المنشأة' }}">
                @else
                    <h2>{{ $hospitalSettings['name'] ?? config('app.name', 'المنصة الطبية') }}</h2>
                @endif
            </div>
            <div class="hospital-info-print">
                <h3>{{ $hospitalSettings['name'] ?? config('app.name', 'المنصة الطبية') }}</h3>
                @if (!empty($hospitalSettings['address']))
                    <p>{{ $hospitalSettings['address'] }}</p>
                @endif
                @if (!empty($hospitalSettings['phone']))
                    <p>الهاتف: {{ $hospitalSettings['phone'] }}</p>
                @endif
                @if (!empty($hospitalSettings['email']))
                    <p>البريد: {{ $hospitalSettings['email'] }}</p>
                @endif
                @if (!empty($hospitalSettings['tax_number']))
                    <p>الرقم الضريبي: {{ $hospitalSettings['tax_number'] }}</p>
                @endif
            </div>
        </header>

        <section class="receipt-title-section">
            <h1>سند قبض</h1>
        </section>

        <section class="receipt-details-grid">
            <div class="detail-group patient-details">
                <h5>بيانات المريض:</h5>
                <p><strong>الاسم:</strong> <span>{{ $receiptAccount->patients->name ?? 'غير محدد' }}</span></p>
                <p><strong>رقم الهاتف:</strong> <span>{{ $receiptAccount->patients->Phone ?? 'غير محدد' }}</span></p>
                <p><strong>رقم الهوية:</strong> <span>{{ $receiptAccount->patients->national_id ?? 'غير محدد' }}</span></p>
                <p><strong> العنوان: </strong> <span> {{ $receiptAccount->patients->Address ?? 'غير محدد' }} </span></p>
                <p><strong> البريد الاكتروني: </strong> <span> {{ $receiptAccount->patients->email ?? 'غير محدد' }} </span></p>

                <p><strong>رقم الملف:</strong> <span>{{ $receiptAccount->patient_id ?? 'غير محدد' }}</span></p>

                {{-- يمكنك إضافة المزيد من تفاصيل المريض إذا لزم الأمر --}}
            </div>
            <div class="detail-group receipt-info">
                <h5>بيانات السند:</h5>
                <p><strong>رقم السند:</strong> <span>#{{ $receiptAccount->id }}</span></p>
                <p><strong>تاريخ السند:</strong>
                    <span>{{ \Carbon\Carbon::parse($receiptAccount->date)->translatedFormat('d F Y') }}</span></p>
                <p><strong>تاريخ الإصدار:</strong>
                    <span>{{ $receiptAccount->created_at->translatedFormat('d F Y - H:i A') }}</span></p>
            </div>
        </section>

        <section class="receipt-body">
            <h4>تفاصيل الدفعة</h4>
            <div class="amount-section">
                المبلغ المدفوع: {{ number_format($receiptAccount->amount, 2) }}
                <span class="currency">{{ config('app.currency_symbol', 'ر.س') }}</span>
            </div>

            @if ($receiptAccount->description)
                <div class="description-section mt-4">
                    <p><strong>البيان / الوصف:</strong></p>
                    <p>{{ nl2br(e($receiptAccount->description)) }}</p>
                </div>
            @endif
        </section>

        {{-- يمكنك إضافة قسم للتوقيعات إذا لزم الأمر --}}
        {{-- <section class="signatures-section mt-5">
            <div class="row">
                <div class="col-6 text-center">
                    <p class="mb-5">_________________________</p>
                    <p>توقيع المستلم (المريض)</p>
                </div>
                <div class="col-6 text-center">
                    <p class="mb-5">_________________________</p>
                    <p>توقيع أمين الصندوق</p>
                </div>
            </div>
        </section> --}}

        <footer class="receipt-footer">
            <p>هذا السند بمثابة إيصال استلام للمبلغ المذكور أعلاه.</p>
            <p>{{ $hospitalSettings['name'] ?? config('app.name') }} - {{ $hospitalSettings['address'] ?? 'العنوان' }}
            </p>
            <p> شكراً لتعاملكم معنا.</p>
        </footer>
    </div>

    <script>
        // لإخفاء زر الطباعة تلقائيًا بعد الطباعة أو الإلغاء (اختياري)
        // (function () {
        //     var beforePrint = function () {
        //         // console.log('Functionality to run before printing.');
        //     };
        //     var afterPrint = function () {
        //         // يمكن إخفاء الزر هنا، لكن الأفضل إخفاؤه بـ CSS @media print
        //         // document.querySelector('.print-button-container').style.display = 'none';
        //     };

        //     if (window.matchMedia) {
        //         var mediaQueryList = window.matchMedia('print');
        //         mediaQueryList.addListener(function (mql) {
        //             if (mql.matches) {
        //                 beforePrint();
        //             } else {
        //                 afterPrint();
        //             }
        //         });
        //     }

        //     // window.onbeforeprint = beforePrint; // قد لا يعمل في كل المتصفحات
        //     // window.onafterprint = afterPrint; // قد لا يعمل في كل المتصفحات
        // }());

        // للطباعة التلقائية عند تحميل الصفحة (إذا أردت)
        // window.onload = function() {
        //     setTimeout(function() { // تأخير بسيط لضمان تحميل كل شيء
        //         window.print();
        //     }, 500);
        // }
    </script>
    <div class="print-button-container">
        <button onclick="window.print()" class="print-button"><i class="fas fa-print"></i> طباعة السند</button>
    </div>
</body>

</html>
