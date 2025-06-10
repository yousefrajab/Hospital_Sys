@extends('Dashboard.layouts.master') {{-- أو التخطيط الخاص بموظف الصيدلية --}}

@section('title')
    صرف الوصفة الطبية رقم: {{ $prescription->prescription_number }}
@endsection

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-danger-rgb: 220, 53, 69;
            --bs-info-rgb: 13, 202, 240;
            --bs-warning-rgb: 255, 193, 7;
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #e3e6f0;
            --bs-card-border-radius: 0.75rem;
            --bs-card-box-shadow: 0 0.15rem 1.25rem rgba(58, 59, 69, 0.1);
            --bs-body-color: #525f7f;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            line-height: 1.65;
        }

        .dispense-form-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            border: 1px solid var(--bs-border-color);
            max-width: 1200px;
            margin: 2rem auto;
        }

        .section-title-underline {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--bs-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(var(--bs-primary-rgb), 0.2);
            display: flex;
            align-items: center;
        }

        .section-title-underline i {
            margin-left: 0.75rem;
            /* RTL */
        }

        .patient-doctor-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-box {
            background-color: rgb(var(--bs-light-rgb));
            padding: 1.25rem;
            border-radius: var(--bs-card-border-radius);
            border: 1px solid var(--bs-border-color);
        }

        .info-box h6 {
            color: var(--bs-primary);
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .info-box p {
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }

        .info-box strong {
            color: rgb(var(--bs-dark-rgb));
        }

        .prescription-item-card {
            background-color: #fff;
            border: 1px solid var(--bs-border-color);
            border-radius: var(--bs-card-border-radius);
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .prescription-item-card .card-header {
            background-color: rgba(var(--bs-primary-rgb), 0.07);
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            color: var(--bs-primary);
            border-bottom: 1px solid var(--bs-border-color);
        }

        .prescription-item-card .card-body {
            padding: 1.25rem;
        }

        .prescription-item-card .item-detail {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .prescription-item-card .item-detail strong {
            min-width: 100px;
            display: inline-block;
        }

        .stock-selection .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .stock-selection .form-select,
        .stock-selection .form-control {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
        /* لضمان ارتفاع متناسق لـ Select2 مع حقول الإدخال الأخرى */
        .stock-selection .select2-container--bootstrap-5 .select2-selection--single {
            height: calc(1.5em + (0.5rem * 2) + (1px * 2) + 2px) !important;
            display: flex;
            align-items: center;
        }


        .stock-info {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .form-actions {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--bs-border-color);
            text-align: left;
            /* RTL */
        }

        .btn-submit-dispense {
            font-weight: 600;
            padding: 0.7rem 1.8rem;
        }
        .btn-submit-dispense .spinner-icon { display: none; }
        .btn-submit-dispense.loading .spinner-icon { display: inline-block; animation: spin 0.75s linear infinite; }
        .btn-submit-dispense.loading .btn-text { display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Validation styling */
        .form-control.is-invalid,
        .form-select.is-invalid,
        .was-validated .form-control:invalid,
        .was-validated .form-select:invalid {
            border-color: rgb(var(--bs-danger-rgb)) !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single.is-invalid {
            border-color: rgb(var(--bs-danger-rgb)) !important;
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-danger-rgb), 0.15) !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single.is-valid { /* Optional: valid state for select2 */
            border-color: rgb(var(--bs-success-rgb)) !important;
        }

        .invalid-feedback {
            color: rgb(var(--bs-danger-rgb));
            font-size: 0.875rem;
        }
        /* أنماط مخصصة لعرض خيارات الدفعات في Select2 */
        .select2-results__option--stock-batch {
            line-height: 1.4;
        }
        .select2-results__option--stock-batch .stock-batch-main {
            display: block;
            font-weight: 500;
            color: #333;
        }
        .select2-results__option--stock-batch .stock-batch-details {
            display: block;
            font-size: 0.8rem;
            color: #777;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-pills text-primary me-2"></i>صرف الوصفات</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">الوصفة رقم: {{ $prescription->prescription_number }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('pharmacy_employee.prescriptions.index') }}"
                class="btn btn-outline-secondary btn-sm ripple-effect">
                <i class="fas fa-list-ul me-1"></i> كل الوصفات الواردة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="dispense-form-container" data-aos="fade-up">
        <div class="text-center mb-4">
            <h3 class="text-primary">صرف الوصفة الطبية رقم: {{ $prescription->prescription_number }}</h3>
            <p class="text-muted">يرجى مراجعة الأدوية واختيار الدفعات المناسبة للصرف.</p>
        </div>

        {{-- معلومات المريض والطبيب --}}
        <div class="patient-doctor-info-grid">
            <div class="info-box" data-aos="fade-right" data-aos-delay="100">
                <h6><i class="fas fa-user-injured"></i>معلومات المريض</h6>
                @if ($prescription->patient)
                    <p><strong>الاسم:</strong> {{ $prescription->patient->name }}</p>
                    <p><strong>رقم الهوية:</strong> {{ $prescription->patient->national_id }}</p>
                    <p><strong>العمر:</strong> {{ $prescription->patient->Date_Birth ? $prescription->patient->Date_Birth->age . ' سنة' : '-' }}</p>
                    @if (isset($prescription->patient->initial_allergies_text) && !empty($prescription->patient->initial_allergies_text) )
                        <p class="text-danger mt-2 mb-0"><small><i class="fas fa-allergies me-1"></i><strong>حساسيات مسجلة:</strong> {{ Str::limit($prescription->patient->initial_allergies_text, 50) }}</small></p>
                    @endif
                @else
                    <p class="text-muted">لا توجد معلومات للمريض.</p>
                @endif
            </div>
            <div class="info-box" data-aos="fade-left" data-aos-delay="100">
                <h6><i class="fas fa-user-md"></i>معلومات الطبيب</h6>
                @if ($prescription->doctor)
                    <p><strong>الاسم:</strong> {{ $prescription->doctor->name }}</p>
                    <p><strong>تاريخ الوصفة:</strong> {{ $prescription->prescription_date->translatedFormat('d M Y') }}</p>
                    @if ($prescription->doctor_notes)
                        <p class="mt-2 mb-0"><small><strong>ملاحظات الطبيب:</strong> {{ Str::limit($prescription->doctor_notes, 60) }}</small></p>
                    @endif
                @else
                    <p class="text-muted">لا توجد معلومات للطبيب.</p>
                @endif
            </div>
        </div>

        <form action="{{ route('pharmacy_employee.prescriptions.dispense.process', $prescription->id) }}" method="POST"
            id="dispenseForm" novalidate class="needs-validation">
            @csrf

            <h5 class="form-section-title mt-4"><i class="fas fa-tablets"></i>بنود الأدوية المطلوبة للصرف</h5>

            @if ($prescription->items->isNotEmpty())
                @foreach ($prescription->items as $index => $item)
                    <input type="hidden" name="items[{{ $index }}][prescription_item_id]" value="{{ $item->id }}">
                    <div class="prescription-item-card" data-aos="fade-up" data-aos-delay="{{ 200 + $index * 50 }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>{{ $loop->iteration }}. {{ $item->medication->name ?? 'دواء غير معروف' }}
                                <small class="text-muted">
                                    @if($item->medication)
                                        ({{ $item->medication->strength ?? '' }}
                                        @if($item->medication->strength && $item->medication->dosage_form) - @endif
                                        {{ $item->medication->dosage_form ? (\App\Models\Medication::getCommonDosageForms()[$item->medication->dosage_form] ?? $item->medication->dosage_form) : '' }})
                                    @endif
                                </small>
                            </span>
                            <span class="badge bg-primary-light text-primary">الكمية الموصوفة:
                                {{ $item->quantity_prescribed ?? 'N/A' }}
                                @if($item->medication && $item->medication->unit_of_measure)
                                    {{ \App\Models\Medication::getCommonUnitsOfMeasure()[$item->medication->unit_of_measure] ?? $item->medication->unit_of_measure }}
                                @endif
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <p class="item-detail mb-1"><strong>الجرعة:</strong> {{ $item->dosage }}</p>
                                    <p class="item-detail mb-1"><strong>التكرار:</strong> {{ $item->frequency }}</p>
                                    <p class="item-detail mb-0"><strong>المدة:</strong> {{ $item->duration ?: '-' }}</p>
                                </div>
                                <div class="col-md-8 stock-selection">
                                    <div class="row g-2">
                                        <div class="col-sm-7">
                                            <div class="form-group mb-sm-0">
                                                <label for="stock_id_{{ $item->id }}" class="form-label">اختر الدفعة للصرف
                                                    @if($item->quantity_prescribed > 0)<small class="text-danger">*</small>@endif
                                                </label>
                                                <select name="items[{{ $index }}][pharmacy_stock_id]"
                                                    id="stock_id_{{ $item->id }}"
                                                    class="form-select select2-stock-batch @error('items.' . $index . '.pharmacy_stock_id') is-invalid @enderror"
                                                    data-placeholder="اختر دفعة المخزون..."
                                                    onchange="updateStockInfo(this, 'stock_info_{{ $item->id }}', 'disp_qty_{{ $item->id }}', {{ $item->quantity_prescribed ?? 0 }})">
                                                    <option value="">-- اختر دفعة --</option>
                                                    @php
                                                        $medicationIdForStockLoop = $item->medication->id ?? $item->medication_id;
                                                    @endphp
                                                    @if (isset($availableStocksByMedication[$medicationIdForStockLoop]) && $availableStocksByMedication[$medicationIdForStockLoop]->isNotEmpty())
                                                        @foreach ($availableStocksByMedication[$medicationIdForStockLoop] as $stock)
                                                            <option value="{{ $stock->id }}"
                                                                data-available="{{ $stock->quantity_on_hand }}"
                                                                data-expiry="{{ $stock->expiry_date ? $stock->expiry_date->format('Y-m-d') : 'N/A' }}"
                                                                data-displaytext="{{ $stock->display_text_for_select }}"
                                                                {{ old('items.' . $index . '.pharmacy_stock_id') == $stock->id ? 'selected' : '' }}>
                                                                {{-- JavaScript will format this --}}
                                                                {{ $stock->display_text_for_select }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option value="" disabled>لا توجد دفعات متاحة</option>
                                                    @endif
                                                </select>
                                                <div id="stock_info_{{ $item->id }}" class="stock-info mt-1"></div>
                                                @error('items.' . $index . '.pharmacy_stock_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group mb-0">
                                                <label for="disp_qty_{{ $item->id }}" class="form-label" >الكمية المصروفة
                                                    <small class="text-danger">*</small></label>
                                                <input type="number" name="items[{{ $index }}][dispensed_quantity]" disabled
                                                    id="disp_qty_{{ $item->id }}"
                                                    value="{{ old('items.' . $index . '.dispensed_quantity', ($item->quantity_prescribed > 0 ? $item->quantity_prescribed : 0)) }}"
                                                    class="form-control @error('items.' . $index . '.dispensed_quantity') is-invalid @enderror"
                                                    min="0" placeholder="الكمية" required>
                                                @error('items.' . $index . '.dispensed_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($item->instructions_for_patient)
                                <div class="mt-2 mb-0 alert alert-light border p-2" style="font-size:0.85rem; background-color: #fbfdff !important;">
                                    <i class="fas fa-info-circle me-1 text-info"></i> <strong>تعليمات للمريض:</strong>
                                    {{ $item->instructions_for_patient }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state-compact my-3"><i class="fas fa-pills"></i><p>لا توجد أدوية في هذه الوصفة.</p></div>
            @endif

            @if ($prescription->items->isNotEmpty())
                <hr class="my-4">
                <div class="form-group">
                    <label for="pharmacy_notes_dispense" class="form-label">ملاحظات الصيدلية (اختياري)</label>
                    <textarea name="pharmacy_notes_dispense" id="pharmacy_notes_dispense"
                        class="form-control @error('pharmacy_notes_dispense') is-invalid @enderror" rows="3"
                        placeholder="أي ملاحظات من الصيدلي بخصوص عملية الصرف...">{{ old('pharmacy_notes_dispense', $prescription->pharmacy_notes) }}</textarea>
                    @error('pharmacy_notes_dispense') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('pharmacy_employee.prescriptions.index') }}"
                        class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-1"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-success btn-submit-dispense" id="submitDispenseBtn">
                       <span class="btn-text"> <i class="fas fa-check-circle me-2"></i> تأكيد الصرف </span>
                       <i class="fas fa-spinner fa-spin spinner-icon"></i>
                    </button>
                </div>
            @endif
        </form>
    </div>
@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>

    <script>
        function showNotification(message, type = 'info', position = 'top-center', autohide = true, timeout = 4000) {
            let iconClass = 'fas fa-info-circle';
            if (type === 'success') iconClass = 'fas fa-check-circle';
            else if (type === 'error') iconClass = 'fas fa-times-circle';
            else if (type === 'warning') iconClass = 'fas fa-exclamation-triangle';
            notif({ msg: `<div class="d-flex align-items-center p-2"><i class='${iconClass} fa-lg me-2'></i><div style="font-size: 0.95rem;">${message}</div></div>`, type, position, autohide, timeout, multiline: true, zindex: 99999, width:'auto', padding:'15px' });
        }

        function updateStockInfo(selectElement, infoDivId, quantityInputId, prescribedQtyDefault = 0) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const available = selectedOption.getAttribute('data-available');
            const expiry = selectedOption.getAttribute('data-expiry');
            const infoDiv = document.getElementById(infoDivId);
            const quantityInput = document.getElementById(quantityInputId);

            if (infoDiv && quantityInput) {
                if (selectElement.value && available && expiry) { // Check if a valid stock is selected
                    infoDiv.innerHTML = `<i class="fas fa-archive fa-xs"></i> متاح: <strong class="text-success">${available}</strong> | <i class="fas fa-calendar-times fa-xs"></i> تنتهي: <strong class="text-info">${expiry}</strong>`;
                    infoDiv.classList.remove('text-danger');
                    infoDiv.classList.add('text-muted');
                    quantityInput.setAttribute('max', available);
                    // Set quantity to prescribed or available, whichever is less
                    const availableNum = parseInt(available);
                    const prescribedNum = parseInt(prescribedQtyDefault);
                    quantityInput.value = Math.min(prescribedNum > 0 ? prescribedNum : (availableNum > 0 ? 1 : 0), availableNum);

                } else {
                    infoDiv.innerHTML = 'الرجاء اختيار دفعة لعرض تفاصيلها.';
                    quantityInput.removeAttribute('max');
                    quantityInput.value = prescribedQtyDefault > 0 ? prescribedQtyDefault : 0;
                }
            }
        }


        $(document).ready(function() {
            AOS.init({ duration: 600, once: true, offset: 50 });

            $('.select2-stock-batch').each(function() {
                const $select = $(this);
                initializeStockBatchSelect2(this); // Pass the DOM element
                // Trigger updateStockInfo if a value is already selected (e.g. from old() input)
                if ($select.val()) {
                    const infoDivId = $select.closest('.form-group').find('.stock-info').attr('id');
                    const quantityInputId = $select.closest('.stock-selection').find('input[name$="[dispensed_quantity]"]').attr('id');
                    const prescribedQty = parseInt($select.closest('.prescription-item-card').find('.badge.bg-primary-light').text().match(/\d+/)[0] || 0);

                    if(infoDivId && quantityInputId){
                        updateStockInfo($select[0], infoDivId, quantityInputId, prescribedQty);
                    }
                }
            });

            function initializeStockBatchSelect2(selector) {
                const $selectElement = $(selector);
                if ($selectElement.data('select2')) { $selectElement.select2('destroy');}

                $selectElement.select2({
                    placeholder: $selectElement.data('placeholder') || "-- اختر دفعة --",
                    width: '100%',
                    dir: "rtl",
                    theme: "bootstrap-5",
                    allowClear: true,
                    dropdownParent: $selectElement.closest('.form-group'),
                    templateResult: function(data) {
                        if (!data.id) { return data.text; }
                        var displayText = $(data.element).data('displaytext') || data.text;
                        var available = $(data.element).data('available');
                        var expiry = $(data.element).data('expiry');
                        return $(
                            '<div class="select2-results__option--stock-batch">' +
                                '<span class="stock-batch-main">' + displayText.split('(تنتهي')[0] + '</span>' +
                                '<span class="stock-batch-details">متاح: ' + available + ' - تنتهي: ' + (expiry || 'N/A') + '</span>' +
                            '</div>'
                        );
                    },
                    templateSelection: function(data) {
                        if (!data.id) { return data.text; }
                        var displayText = $(data.element).data('displaytext') || data.text;
                        // لعرض نص أقصر في الحقل المختار
                        const batchMatch = displayText.match(/دفعة: ([\w\/-]+)/);
                        return batchMatch ? `دفعة: ${batchMatch[1]}` : (displayText.length > 30 ? displayText.substring(0, 27) + "..." : displayText);
                    }
                }).on('change', function() {
                     // عند تغيير Select2، تأكد من إزالة أي كلاس خطأ
                    $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                    $(this).closest('.form-group').find('.invalid-feedback').hide();
                });
            }


            const dispenseForm = document.getElementById('dispenseForm');
            if (dispenseForm) {
                dispenseForm.addEventListener('submit', function(event) {
                    let formIsValid = true;
                    dispenseForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    $('.select2-stock-batch').next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                    dispenseForm.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');

                    if (!dispenseForm.checkValidity()) { formIsValid = false; }

                    $('#dispenseForm .prescription-item-card').each(function(){
                        const $itemCard = $(this);
                        const $quantityInput = $itemCard.find('input[name$="[dispensed_quantity]"]');
                        const $stockSelect = $itemCard.find('select[name$="[pharmacy_stock_id]"]');
                        const dispensedQty = parseInt($quantityInput.val());

                        if (isNaN(dispensedQty) || dispensedQty < 0) {
                             formIsValid = false;
                             $quantityInput.addClass('is-invalid');
                             $quantityInput.closest('.form-group').find('.invalid-feedback').text('الكمية يجب أن تكون رقمًا موجبًا أو صفر.').show();
                        }

                        if (dispensedQty > 0) {
                            if (!$stockSelect.val()) {
                                formIsValid = false;
                                $stockSelect.addClass('is-invalid').next('.select2-container').find('.select2-selection').addClass('is-invalid');
                                $stockSelect.closest('.form-group').find('.invalid-feedback').text('يجب اختيار دفعة المخزون.').show();
                            } else {
                                const selectedOption = $stockSelect.find('option:selected');
                                if (selectedOption.length && selectedOption.val() !== "") {
                                    const availableQty = parseInt(selectedOption.data('available'));
                                    if (!isNaN(availableQty) && dispensedQty > availableQty) {
                                        formIsValid = false;
                                        $quantityInput.addClass('is-invalid');
                                        $quantityInput.closest('.form-group').find('.invalid-feedback').text('الكمية المصروفة ('+ dispensedQty +') تتجاوز المتاح (' + availableQty + ').').show();
                                    }
                                }
                            }
                        }
                    });

                    if (!formIsValid) {
                        event.preventDefault();
                        event.stopPropagation();
                        const firstInvalidElement = dispenseForm.querySelector(':invalid:not(fieldset), .is-invalid, .select2-selection.is-invalid');
                        if (firstInvalidElement) {
                            if ($(firstInvalidElement).hasClass('select2-hidden-accessible') || $(firstInvalidElement).hasClass('select2-stock-batch')) {
                                $(firstInvalidElement).select2('open');
                            } else if ($(firstInvalidElement).hasClass('select2-selection')) {
                                $(firstInvalidElement).closest('.select2-container').prev('select').select2('open');
                            }
                            else {
                                firstInvalidElement.focus({preventScroll:true});
                                $('html, body').animate({ scrollTop: $(firstInvalidElement).offset().top - 120 }, 300);
                            }
                        }
                        showNotification("يرجى مراجعة الحقول والتأكد من صحة البيانات.", "warning");
                    } else {
                        const submitDispenseBtn = document.getElementById('submitDispenseBtn');
                        if(submitDispenseBtn){
                            submitDispenseBtn.disabled = true;
                            $(submitDispenseBtn).html('<span class="btn-text d-none"></span><i class="fas fa-spinner fa-spin me-2"></i> جاري المعالجة...');
                        }
                    }
                    if (!formIsValid) {
                        dispenseForm.classList.add('was-validated');
                    }
                });
            }

            @if(session('success')) showNotification("{{ session('success') }}", "success", "top-center"); @endif
            @if(session('error')) showNotification("{{ session('error') }}", "error", "top-center", false); @endif
            @if ($errors->any())
                let errorList = "<strong><i class='fas fa-exclamation-triangle me-2'></i> حدثت الأخطاء التالية:</strong><ul class='mb-0 mt-2' style='list-style-type:none; padding-right:0;'>";
                @foreach ($errors->all() as $error) errorList += "<li class='mb-1'>- {{ $error }}</li>"; @endforeach
                errorList += "</ul>";
                showNotification(errorList, "error", "top-center", false);
            @endif
        });
    </script>
@endsection
