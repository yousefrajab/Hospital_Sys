<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ trans('patients.invoice_print_title', ['id' => $invoice->id]) }}</title>    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff; /* White background for printing */
            color: #000;
            font-size: 12pt; /* Standard font size for print */
            line-height: 1.5;
        }
        .print-container {
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            padding: 20mm 15mm; /* Margins */
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.2); /* Shadow for screen view */
            position: relative; /* For footer positioning */
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .invoice-header .logo img {
            max-height: 80px; /* Adjust as needed */
            max-width: 200px;
        }
        .invoice-header .hospital-info {
            text-align: left; /* RTL: text-align: right */
        }
        .invoice-header .hospital-info h3 {
            margin: 0 0 5px 0;
            font-size: 1.5em;
            font-weight: 700;
            color: #00796B; /* Match primary color */
        }
        .invoice-header .hospital-info p {
            margin: 2px 0;
            font-size: 0.9em;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 0.95em;
        }
        .invoice-details .to-details,
        .invoice-details .from-details {
            width: 48%;
        }
        .invoice-details h5 {
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            color: #00796B;
        }
        .invoice-details p {
            margin-bottom: 3px;
        }
        .invoice-details strong {
            font-weight: 600;
            min-width: 100px; /* For alignment */
            display: inline-block;
        }

        .invoice-items table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 0.9em;
        }
        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: right; /* Default for RTL */
        }
        .invoice-items th {
            background-color: #f0f0f0;
            font-weight: 600;
        }
        .invoice-items td.amount, .invoice-items th.amount { text-align: left; } /* For numbers */
        .rtl .invoice-items td.amount, .rtl .invoice-items th.amount { text-align: right; }


        .invoice-summary {
            display: flex;
            justify-content: flex-end; /* Totals on the left for RTL */
            margin-bottom: 30px;
        }
        .invoice-summary table {
            width: 45%; /* Adjust width as needed */
            font-size: 0.95em;
        }
        .invoice-summary td { padding: 8px 0; }
        .invoice-summary .summary-label { text-align: right; padding-right: 15px; font-weight: 500;}
        .invoice-summary .summary-value { text-align: left; font-weight: 600; }
        .invoice-summary .total-row td {
            font-size: 1.1em;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
            color: #00796B;
        }

        .invoice-footer {
            text-align: center;
            font-size: 0.8em;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #eee;
            /* position: absolute;
            bottom: 20mm;
            left: 15mm;
            right: 15mm; */
        }
        .invoice-footer p { margin: 3px 0; }

        .print-button-container {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px; /* Space below button on screen */
        }
        .print-button {
            padding: 10px 25px;
            font-size: 1.1em;
            background-color: #00796B;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .print-button:hover { background-color: #004D40; }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 10pt; /* Adjust for printer */
                background-color: #fff;
                -webkit-print-color-adjust: exact !important; /* Chrome, Safari */
                color-adjust: exact !important; /* Standard */
            }
            .print-container {
                margin: 0;
                padding: 10mm 10mm; /* Reduce padding for print */
                box-shadow: none;
                border: none;
                width: 100%;
                min-height: 0;
            }
            .print-button-container {
                display: none !important;
            }
            .invoice-header {
                border-bottom: 1px solid #000;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
             .invoice-summary .total-row td {
                border-top: 1px solid #000;
            }
            .invoice-items th {
                background-color: #eee !important; /* Ensure background prints */
            }
        }
    </style>
</head>
<body>

<div class="print-container">
        <header class="invoice-header">
            <div class="logo">
                @if(!empty($hospitalSettings['logo']))
                    <img src="{{ $hospitalSettings['logo'] }}" alt="{{ $hospitalSettings['name'] }}">
                @else
                    <h2>{{ $hospitalSettings['name'] }}</h2>
                @endif
            </div>
            <div class="hospital-info">
                <h3>{{ $hospitalSettings['name'] }}</h3>
                {{-- @if(!empty($hospitalSettings['address']))<p>{{ $hospitalSettings['address'] }}</p>@endif --}}
                @if(!empty($hospitalSettings['phone']))<p>{{ trans('patients.phone') }}: {{ $hospitalSettings['phone'] }}</p>@endif
                @if(!empty($hospitalSettings['email']))<p>{{ trans('patients.email') }}: {{ $hospitalSettings['email'] }}</p>@endif
                {{-- @if(!empty($hospitalSettings['tax_number']))<p>الرقم الضريبي: {{ $hospitalSettings['tax_number'] }}</p>@endif --}}
            </div>
        </header>

        <section class="invoice-details">
            <div class="to-details">
                <h5>{{ trans('patients.invoice_to') }}</h5>
                <p><strong>{{ trans('patients.patient_label') }}</strong> {{ $invoice->patient->name }}</p>
                <p><strong>{{ trans('patients.file_number') }}</strong> {{ $invoice->patient->id }}</p>
                @if($invoice->patient->Phone)
                <p><strong>{{ trans('patients.phone') }}:</strong> {{ $invoice->patient->Phone }}</p>
                @endif
                @if($invoice->patient->email)
                <p><strong>{{ trans('patients.email') }}:</strong> {{ $invoice->patient->email }}</p>
                @endif
            </div>
            <div class="from-details" style="text-align: left;">
                <h5>{{ trans('patients.invoice_data') }}</h5>
                <p><strong>{{ trans('patients.invoice_number') }}</strong> #{{ $invoice->id }}</p>
                <p><strong>{{ trans('patients.invoice_date') }}</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('d F Y') }}</p>
                <p><strong>{{ trans('patients.issue_date') }}</strong> {{ $invoice->created_at->translatedFormat('d F Y - H:i A') }}</p>
                <p><strong>{{ trans('patients.payment_type') }}</strong> {{ $invoice->type == 1 ? trans('patients.cash') : ($invoice->type == 2 ? trans('patients.credit') : trans('patients.unspecified')) }}</p>
                <p><strong>{{ trans('patients.status') }}</strong>
                    @php
                        $statusText = $invoice->invoice_status_display ?? ($invoice->invoice_status == 1 ? trans('patients.unpaid') : ($invoice->invoice_status == 2 ? trans('patients.paid') : ($invoice->invoice_status == 3 ? trans('patients.cancelled') : trans('patients.unknown'))));
                    @endphp
                    {{ $statusText }}
                </p>
            </div>
        </section>

        <section class="invoice-items">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('patients.item_col') }}</th>
                        <th class="amount">{{ trans('patients.unit_price_col') }}</th>
                        <th class="amount">{{ trans('patients.quantity_col') }}</th>
                        <th class="amount">{{ trans('patients.subtotal_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            @if($invoice->invoice_type == 1 && $invoice->Service)
                                {{ $invoice->Service->name }}
                                @if($invoice->Service->description)
                                    <small class="d-block text-muted">{{ Str::limit($invoice->Service->description, 70) }}</small>
                                @endif
                            @elseif($invoice->invoice_type == 2 && $invoice->Group)
                                {{ $invoice->Group->name }}
                                @if($invoice->Group->notes)
                                    <small class="d-block text-muted">{{ Str::limit($invoice->Group->notes, 70) }}</small>
                                @endif
                            @else
                                {{ trans('patients.unspecified_service_package') }}
                            @endif
                        </td>
                        <td class="amount">{{ number_format($invoice->price, 2) }}</td>
                        <td class="amount">1</td>
                        <td class="amount">{{ number_format($invoice->price, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="invoice-summary">
            <table>
                <tbody>
                    <tr>
                        <td class="summary-label">{{ trans('patients.subtotal') }}</td>
                        <td class="summary-value">{{ number_format($invoice->price, 2) }} <small>{{ config('app.currency_symbol', trans('patients.currency_sar')) }}</small></td>
                    </tr>
                    <tr>
                        <td class="summary-label">{{ trans('patients.discount_value') }}</td>
                        <td class="summary-value">{{ number_format($invoice->discount_value, 2) }} <small>{{ config('app.currency_symbol', trans('patients.currency_sar')) }}</small></td>
                    </tr>
                    <tr>
                        <td class="summary-label">{{ trans('patients.total_after_discount') }}</td>
                        <td class="summary-value">{{ number_format($invoice->price - $invoice->discount_value, 2) }} <small>{{ config('app.currency_symbol', trans('patients.currency_sar')) }}</small></td>
                    </tr>
                    <tr>
                        <td class="summary-label">{{ trans('patients.tax_rate_with_percentage', ['rate' => $invoice->tax_rate]) }}</td>
                        <td class="summary-value">{{ number_format($invoice->tax_value, 2) }} <small>{{ config('app.currency_symbol', trans('patients.currency_sar')) }}</small></td>
                    </tr>
                    <tr class="total-row">
                        <td class="summary-label">{{ trans('patients.total_due_print') }}</td>
                        <td class="summary-value">{{ number_format($invoice->total_with_tax, 2) }} <small>{{ config('app.currency_symbol', trans('patients.currency_sar')) }}</small></td>
                    </tr>
                </tbody>
            </table>
        </section>

        @if($invoice->Doctor)
        <section class="notes-section mt-4 pt-3 border-top">
            <p class="mb-1"><strong>{{ trans('patients.attending_doctor') }}</strong> {{ $invoice->Doctor->name }}</p>
            @if($invoice->Section)
            <p><strong>{{ trans('patients.department') }}</strong> {{ $invoice->Section->name }}</p>
            @endif
        </section>
        @endif

        {{-- <footer class="invoice-footer">
            <p>{{ trans('patients.footer_thank_you_message') }}</p>
            <p>{{ $hospitalSettings['name'] }} - {{ $hospitalSettings['address'] }}</p>
        </footer> --}}
    </div>
    <script>
        // يمكن إضافة أي JavaScript بسيط هنا إذا لزم الأمر، مثل الفتح التلقائي لنافذة الطباعة
        // window.onload = function() {
        //     window.print();
        // }
    </script>
      <div class="print-button-container">
        <button onclick="window.print()" class="print-button"><i class="fas fa-print"></i> {{ trans('patients.Print invoice') }}</button>
    </div>
</body>
</html>
