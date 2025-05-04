@extends('Dashboard.layouts.master')

@section('css')
    <!--Internal Notify -->
    <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
    @include('Style.Style')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366F1, #8B5CF6);
            --secondary-gradient: linear-gradient(135deg, #06B6D4, #0EA5E9);
            --glass-effect: rgba(255, 255, 255, 0.25);
        }

        .insurance-container {
            background: #F8FAFC;
            font-family: 'Inter', sans-serif;
        }

        .card-3d {
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                        0 10px 10px -5px rgba(0, 0, 0, 0.04);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-3d:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                        0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            padding-bottom: 12px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .form-control-modern {
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            padding: 12px 16px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.7);
        }

        .form-control-modern:focus {
            border-color: #8B5CF6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
            background: white;
        }

        .floating-label {
            position: relative;
            margin-bottom: 24px;
        }

        .floating-label label {
            position: absolute;
            top: -10px;
            left: 16px;
            background: white;
            padding: 0 8px;
            font-size: 13px;
            color: #6366F1;
            font-weight: 600;
            z-index: 1;
        }

        /* زر الإلغاء */
        .btn-cancel {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            color: #6c757d;
            box-shadow: 0 4px 6px rgba(108, 117, 125, 0.1);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(108, 117, 125, 0.15);
        }

        /* زر الحفظ */
        .btn-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            box-shadow: 0 4px 6px rgba(0, 178, 255, 0.2);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #3a9ffd 0%, #00d9e9 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 178, 255, 0.3);
        }

        /* تأثيرات خاصة بصفحة التأمين */
        .insurance-header {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .percentage-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            background: rgba(6, 182, 212, 0.1);
            color: #06B6D4;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .notes-textarea {
            min-height: 120px;
            resize: none;
        }

        /* تأثيرات الحركة */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
@endsection

@section('title') {{trans('insurance.Add_Insurance')}} @stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex align-items-center">
            <h4 class="content-title mb-0 my-auto">{{trans('insurance.Insurance')}}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{trans('insurance.Add_Insurance')}}</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <a href="{{ route('admin.insurance.index') }}" class="btn btn-outline-primary" style="color: white">
            <i class="fas fa-arrow-left"></i> {{trans('insurance.back_to_list')}}
        </a>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
@include('Dashboard.messages_alert')

<div class="insurance-container">
    <div class="row justify-content-center animate-fade">
        <div class="col-lg-10 col-md-12">
            <div class="card-3d p-5">
                <div class="insurance-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="section-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i> {{trans('insurance.Add_Insurance')}}
                        </h3>
                        {{-- <span class="percentage-badge">
                            <i class="fas fa-percentage me-1"></i> {{trans('insurance.discount_percentage')}}
                        </span> --}}
                    </div>
                    <p class="text-muted mt-2 mb-0">{{trans('insurance.add_new_company')}}</p>
                </div>

                <form action="{{route('admin.insurance.store')}}" method="post" autocomplete="off">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="floating-label">
                                <label>{{trans('insurance.Company_code')}}</label>
                                <input type="text" name="insurance_code" value="{{old('insurance_code')}}"
                                    class="form-control-modern w-100 @error('insurance_code') is-invalid @enderror " required
                                    placeholder="أدخل كود الشركة">
                                @error('insurance_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="floating-label">
                                <label>{{trans('insurance.Company_name')}}</label>
                                <input type="text" name="name" value="{{old('name')}}"
                                    class="form-control-modern w-100 @error('name') is-invalid @enderror" required
                                    placeholder="أدخل اسم الشركة">
                                @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="floating-label">
                                <label>{{trans('insurance.discount_percentage')}} (%)</label>
                                <input type="number" name="discount_percentage"
                                    class="form-control-modern w-100 @error('discount_percentage') is-invalid @enderror" required
                                    min="0" max="100" step="0.01"
                                    placeholder="0.00">
                                @error('discount_percentage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="floating-label">
                                <label>{{trans('insurance.Insurance_bearing_percentage')}} (%)</label>
                                <input type="number" name="Company_rate"
                                    class="form-control-modern w-100 @error('Company_rate') is-invalid @enderror" required
                                    min="0" max="100" step="0.01"
                                    placeholder="0.00">
                                @error('Company_rate')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="floating-label">
                                <label>{{trans('insurance.notes')}}</label>
                                <textarea name="notes" class="form-control-modern notes-textarea w-100"
                                    placeholder="أدخل ملاحظاتك هنا..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5 gap-3">
                        <a href="{{ route('admin.insurance.index') }}" class="btn-cancel">
                            <i class="fas fa-times me-2"></i> {{trans('insurance.cancel')}}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i> {{trans('insurance.save')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>
    <script>
        // يمكنك إضافة أي JavaScript إضافي هنا
        document.addEventListener('DOMContentLoaded', function() {
            // تأثيرات إضافية عند تحميل الصفحة
            const inputs = document.querySelectorAll('.form-control-modern');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('label').style.color = '#8B5CF6';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.querySelector('label').style.color = '#6366F1';
                });
            });
        });
    </script>
@endsection
