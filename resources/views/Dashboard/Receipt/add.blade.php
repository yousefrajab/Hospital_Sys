@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('Dashboard/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('title', 'اضافة سند قبض جديد')
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto"><div class="d-flex"><h4 class="content-title mb-0 my-auto">الحسابات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة سند قبض جديد</span></div></div>
    </div>
@endsection
@section('content')
    @include('Dashboard.messages_alert')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.Receipt.store') }}" method="post" autocomplete="off">
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-2"><label for="patient_id">اسم المريض <span class="text-danger">*</span></label></div>
                                <div class="col-md-10 mg-t-5 mg-md-t-0">
                                   <select name="patient_id" id="patient_id" class="form-control select2 @error('patient_id') is-invalid @enderror" required>
                                        <option value="" selected disabled>{{ trans('forms.select_option') }}</option>
                                        @foreach($Patients as $Patient)
                                           <option value="{{$Patient->id}}" {{ old('patient_id') == $Patient->id ? 'selected' : '' }}>{{$Patient->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- قائمة منسدلة مدمجة للخدمات والباقات --}}
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-2">
                                    <label for="item_type_id">الخدمة / الباقة (اختياري)</label>
                                </div>
                                <div class="col-md-10 mg-t-5 mg-md-t-0">
                                   <select name="item_type_id" id="item_type_id" class="form-control select2 @error('item_type_id') is-invalid @enderror">
                                        <option value="" selected data-price="0" data-name="">{{ trans('forms.select_option_optional') }}</option>
                                        @if(isset($Services) && $Services->count() > 0)
                                            <optgroup label="الخدمات المفردة">
                                                @foreach($Services as $Service)
                                                   <option value="service_{{$Service->id}}" data-price="{{$Service->price}}" data-name="{{$Service->name}}" {{ old('item_type_id') == 'service_'.$Service->id ? 'selected' : '' }}>
                                                       {{$Service->name}} ({{number_format($Service->price, 2)}} {{ config('app.currency_symbol', 'ر.س') }})
                                                   </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                        @if(isset($GroupedServices) && $GroupedServices->count() > 0)
                                            <optgroup label="الباقات المجمعة">
                                                @foreach($GroupedServices as $Group)
                                                   {{-- افترض أن سعر الباقة هو Total_with_tax واسمها name --}}
                                                   <option value="group_{{$Group->id}}" data-price="{{$Group->Total_with_tax ?? 0}}" data-name="{{$Group->name}}" {{ old('item_type_id') == 'group_'.$Group->id ? 'selected' : '' }}>
                                                       باقة: {{$Group->name}} ({{number_format($Group->Total_with_tax ?? 0, 2)}} {{ config('app.currency_symbol', 'ر.س') }})
                                                   </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                    @error('item_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-2"><label for="Debit">المبلغ <span class="text-danger">*</span></label></div>
                                <div class="col-md-10 mg-t-5 mg-md-t-0">
                                    <input class="form-control @error('Debit') is-invalid @enderror" name="Debit" id="Debit" value="{{ old('Debit') }}" type="number" step="0.01" required>
                                    @error('Debit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-2"><label for="description">البيان</label></div>
                                <div class="col-md-10 mg-t-5 mg-md-t-0">
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">{{ trans('Doctors.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('dashboard/plugins/notify/js/notifit-custom.js')}}"></script>
    <script src="{{URL::asset('Dashboard/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{URL::asset('dashboard/js/form-elements.js')}}"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2').select2({
                    placeholder: "{{ trans('forms.select_option') }}",
                    allowClear: true
                });
            }

            $('#item_type_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var price = selectedOption.data('price');
                var itemName = selectedOption.data('name'); // اسم الخدمة أو الباقة
                var currentDescription = $('#description').val();
                var itemValue = $(this).val(); //  "service_X" or "group_Y"

                if (price !== undefined && itemValue !== "") {
                    $('#Debit').val(parseFloat(price).toFixed(2));
                } else if (itemValue === "") { // إذا تم اختيار "اختر من القائمة"
                     $('#Debit').val(''); // مسح المبلغ
                }


                if (itemName !== undefined && itemValue !== "" && (currentDescription === "" || currentDescription.startsWith("رسوم خدمة:") || currentDescription.startsWith("رسوم باقة:"))) {
                    var prefix = itemValue.startsWith("service_") ? "رسوم خدمة: " : (itemValue.startsWith("group_") ? "رسوم باقة: " : "");
                    $('#description').val(prefix + itemName);
                } else if (itemValue === "" && (currentDescription.startsWith("رسوم خدمة:") || currentDescription.startsWith("رسوم باقة:"))) {
                    // $('#description').val(''); // أو اتركه إذا كان المستخدم قد كتب شيئاً آخر
                }
            });
        });
    </script>
@endsection
