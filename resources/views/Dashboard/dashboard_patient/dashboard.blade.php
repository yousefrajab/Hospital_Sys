@extends('Dashboard.layouts.master')

@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('Dashboard/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('Dashboard/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <style>
        /* لتنسيق أفضل لقسم المواعيد */
        .appointment-list .list-group-item {
            border-left: 3px solid transparent;
            transition: all 0.3s ease-in-out;
        }
        .appointment-list .list-group-item:hover {
            background-color: #f8f9fa;
            border-left-color: var(--primary-bg-color, #007bff); /* استخدم متغير اللون الأساسي من القالب إذا توفر */
        }
        .appointment-list .appointment-time {
            font-weight: bold;
            color: var(--primary-bg-color, #007bff);
        }
        .appointment-list .appointment-status-pending {
            color: #ffc107; /* أصفر للانتظار أو غير مؤكد */
        }
        .appointment-list .appointment-status-confirmed {
            color: #28a745; /* أخضر للمؤكد */
        }
        .appointment-list .appointment-status-completed {
            color: #6c757d; /* رمادي للمكتمل */
        }
        .appointment-list .appointment-status-cancelled {
            color: #dc3545; /* أحمر للملغي */
            text-decoration: line-through;
        }
        .card-header-appointments {
            background-color: #f1f4fb; /* لون خلفية خفيف للهيدر */
            border-bottom: 1px solid #e3e6f0;
        }
        .card-header-appointments .card-title {
            color: #5a5c69; /* لون أغمق قليلاً للعنوان */
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">لوحة تحكم المريض</h2><br>
                <p class="mg-b-0">مرحبا بعودتك مرة اخرى {{ auth()->user()->name }}</p>
            </div>
        </div>
        <div class="right-content">
            <a href="{{ url('/') }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-home"></i>
                الذهاب للموقع الرئيسي
            </a>
        </div>
    </div>
    <!-- /breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-6 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">اجمالي عدد الفواتير</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">{{ App\Models\Invoice::where('patient_id', auth()->user()->id)->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">اجمالي المدفوعات</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white"><a style="color: white"
                                        href="{{ route('payments.patient') }}">{{ App\Models\PatientAccount::where('patient_id', auth()->user()->id)->sum('credit') }}</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
            </div>
        </div>
    </div>
    <!-- row closed -->

    {{-- قسم الفواتير --}}
    <div class="row row-sm row-deck">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card card-table-two">
                <div class="card-header card-header-appointments"> {{-- استخدام كلاس مميز للهيدر --}}
                    <h4 class="card-title mb-1"><i class="fas fa-file-invoice-dollar me-2"></i>اخر 5 فواتير على النظام</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive country-table">
                        <table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>تاريخ الفاتورة</th>
                                    {{-- <th>اسم المريض</th> --}} {{-- لا داعي لاسم المريض في صفحته --}}
                                    <th>اسم الطبيب</th>
                                    <th>حالة الفاتورة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $patient_invoices = App\Models\Invoice::where('patient_id',auth()->user()->id)->latest()->take(5)->get();
                                @endphp
                                @forelse($patient_invoices as $invoice)
                                    <tr>
                                        <td><a href="{{route('invoices.patient',$invoice->id)}}">{{$invoice->id}}</a></td>
                                        <td>{{ $invoice->invoice_date }}</td>
                                        {{-- <td>{{ $invoice->Patient->name }}</td> --}}
                                        <td>{{ $invoice->Doctor->name }}</td>
                                        <td>
                                            @if ($invoice->invoice_status == 1)
                                                <span class="badge badge-danger">تحت الاجراء</span>
                                            @elseif ($invoice->invoice_status == 2)
                                                <span class="badge badge-warning">مراجعة</span>
                                            @else
                                                <span class="badge badge-success">مكتملة</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">لا توجد فواتير لعرضها حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->

    <!-- ========================= قسم المواعيد الجديد ========================= -->
    <div class="row row-sm row-deck mt-4"> {{-- mt-4 لإضافة مسافة علوية --}}
        {{-- بطاقة المواعيد القادمة --}}
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-appointments">
                    <h4 class="card-title mb-0"><i class="far fa-calendar-alt me-2"></i> مواعيدي القادمة</h4>
                </div>
                <div class="card-body">
                    @php
                        $upcoming_appointments = App\Models\Appointment::with(['doctor', 'section'])
                            ->where('patient_id', auth()->user()->id)
                            ->where('appointment', '>=', now()) // المواعيد من الآن فصاعداً
                            ->whereNotIn('type', ['ملغي', 'مكتمل']) // استثناء الملغاة والمكتملة
                            ->orderBy('appointment', 'asc')
                            ->take(5) // عرض أحدث 5 مواعيد قادمة مثلاً
                            ->get();
                    @endphp
                    @if($upcoming_appointments->isNotEmpty())
                        <ul class="list-group list-group-flush appointment-list">
                            @foreach ($upcoming_appointments as $appointment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="appointment-time">{{ \Carbon\Carbon::parse($appointment->appointment)->translatedFormat(' يوم D M Y - h:i A') }}</div>
                                        <div>مع الدكتور: <strong>{{ $appointment->doctor->name }}</strong></div>
                                        <div>القسم: <small>{{ $appointment->section->name }}</small></div>
                                        @if($appointment->notes)
                                        <div class="text-muted small mt-1">ملاحظات: {{ Str::limit($appointment->notes, 50) }}</div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $statusClass = '';
                                            $statusText = $appointment->type; // القيمة من قاعدة البيانات
                                            if ($appointment->type == 'غير مؤكد') {
                                                $statusClass = 'appointment-status-pending';
                                                $statusText = 'غير مؤكد';
                                            } elseif ($appointment->type == 'مؤكد') {
                                                $statusClass = 'appointment-status-confirmed';
                                                $statusText = 'مؤكد';
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }} p-2">{{ $statusText }}</span>
                                        {{-- يمكنك إضافة زر لإلغاء الموعد هنا إذا أردت --}}
                                        {{-- @if($appointment->type != 'مؤكد' && \Carbon\Carbon::parse($appointment->appointment)->isFuture())
                                            <form action="{{ route('patient.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline-block mt-2" onsubmit="return confirm('هل أنت متأكد من رغبتك في إلغاء هذا الموعد؟');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">إلغاء الموعد</button>
                                            </form>
                                        @endif --}}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if(App\Models\Appointment::where('patient_id', auth()->user()->id)->where('appointment', '>=', now())->whereNotIn('type', ['ملغي', 'مكتمل'])->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('patient.appointments.index') }}" class="btn btn-sm btn-outline-primary">عرض كل المواعيد القادمة</a> {{-- افترض أن هذا المسار موجود --}}
                            </div>
                        @endif
                    @else
                        <p class="text-center text-muted">لا توجد لديك مواعيد قادمة حالياً.</p>
                        <div class="text-center mt-2">
                             <a href="{{route('patient.appointments.create')}}" class="btn btn-success"><i class="fas fa-calendar-plus"></i> حجز موعد جديد</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- بطاقة المواعيد السابقة --}}
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-appointments">
                    <h4 class="card-title mb-0"><i class="far fa-calendar-check me-2"></i> مواعيدي السابقة</h4>
                </div>
                <div class="card-body">
                    @php
                        $past_appointments = App\Models\Appointment::with(['doctor', 'section'])
                            ->where('patient_id', auth()->user()->id)
                            ->where(function ($query) {
                                $query->where('appointment', '<', now()) // المواعيد التي مضى وقتها
                                      ->orWhereIn('type', ['مكتمل', 'ملغي']); // أو المواعيد المكتملة/الملغاة بغض النظر عن وقتها
                            })
                            ->orderBy('appointment', 'desc') // عرض الأحدث أولاً
                            ->take(5) // عرض أحدث 5 مواعيد سابقة
                            ->get();
                    @endphp
                    @if($past_appointments->isNotEmpty())
                        <ul class="list-group list-group-flush appointment-list">
                            @foreach ($past_appointments as $appointment)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="appointment-time">{{ \Carbon\Carbon::parse($appointment->appointment)->translatedFormat('يوم D M Y - h:i A') }}</div>
                                            <div>مع الدكتور: <strong>{{ $appointment->doctor->name }}</strong></div>
                                            <div>القسم: <small>{{ $appointment->section->name }}</small></div>
                                        </div>
                                        @php
                                            $statusClass = '';
                                            $statusText = $appointment->type;
                                            if ($appointment->type == 'مكتمل') {
                                                $statusClass = 'appointment-status-completed';
                                            } elseif ($appointment->type == 'ملغي') {
                                                $statusClass = 'appointment-status-cancelled';
                                            } else { // إذا كان الموعد قد فات ولم يتم تحديث حالته
                                                $statusClass = 'appointment-status-completed'; // اعتبره مكتمل ضمنياً
                                                $statusText = 'فات الموعد';
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }} p-2">{{ $statusText }}</span>
                                    </div>
                                    {{-- يمكنك إضافة رابط لعرض تفاصيل الفاتورة إذا كانت مرتبطة --}}
                                </li>
                            @endforeach
                        </ul>
                         @if(App\Models\Appointment::where('patient_id', auth()->user()->id)->where(function ($q) { $q->where('appointment', '<', now())->orWhereIn('type', ['مكتمل', 'ملغي']); })->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('patient.appointments.history') }}" class="btn btn-sm btn-outline-secondary">عرض كل المواعيد السابقة</a> {{-- افترض أن هذا المسار موجود --}}
                            </div>
                        @endif
                    @else
                        <p class="text-center text-muted">لا توجد لديك مواعيد سابقة لعرضها.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- ========================= نهاية قسم المواعيد ========================= -->

    </div> {{-- .row --}}
    </div> {{-- .container-fluid or similar --}}
    <!-- Container closed -->
@endsection

@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('Dashboard/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ URL::asset('Dashboard/plugins/raphael/raphael.min.js') }}"></script>
    <!--Internal  Flot js-->
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/chart.flot.sampledata.js') }}"></script>
    <!--Internal Apexchart js-->
    <script src="{{ URL::asset('Dashboard/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    <script src="{{ URL::asset('Dashboard/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/modal-popup.js') }}"></script>
    <!--Internal  index js -->
    <script src="{{ URL::asset('Dashboard/js/index.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/jquery.vmap.sampledata.js') }}"></script>
@endsection
