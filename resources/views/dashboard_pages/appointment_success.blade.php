{{-- resources/views/dashboard_pages/appointment_success.blade.php --}}
@extends('Dashboard.layouts.master') {{-- أو أي layout تستخدمه لصفحات المرضى --}}

@section('title', 'تم الحجز بنجاح')

@section('css')
    @parent
    {{-- أي CSS إضافي إذا احتجت --}}
    <style>
        .success-container {
            text-align: center;
            padding: 50px 20px;
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745; /* أخضر */
            margin-bottom: 20px;
        }
        .success-message h2 {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #333;
        }
        .success-message p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn-home {
            padding: 10px 30px;
            font-size: 1rem;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المواعيد</h4>
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">/ تأكيد الحجز</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="success-container">
                        <i class="fas fa-check-circle success-icon"></i>
                        <div class="success-message">
                            <h2>تم حجز موعدك بنجاح!</h2>
                            @if (isset($message) && $message)
                                <p>{{ $message }}</p>
                            @else
                                <p>سيتم التواصل معك قريباً لتأكيد تفاصيل الموعد النهائية.</p>
                            @endif
                            <a href="{{ route('dashboard.patient') }}" class="btn btn-primary btn-home">
                                <i class="fas fa-home me-2"></i> العودة إلى لوحة التحكم
                            </a>
                            {{-- يمكنك إضافة زر لعرض "مواعيدي" إذا كان لديك هذا الـ route --}}
                            {{-- <a href="{{ route('patient.appointments.index') }}" class="btn btn-outline-secondary btn-home ms-2">
                                <i class="fas fa-calendar-alt me-2"></i> عرض مواعيدي
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    {{-- أي JS إضافي إذا احتجت --}}
@endsection
