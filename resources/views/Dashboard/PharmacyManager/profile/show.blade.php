{{-- resources/views/Dashboard/ray_employee/profile/show.blade.php --}}
@extends('Dashboard.layouts.master')

@section('title')
    الملف الشخصي - {{ $employee->name }}
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --ray-primary: #5e72e4;
            --ray-secondary: #11cdef;
            --ray-gradient: linear-gradient(135deg, var(--ray-primary), var(--ray-secondary));
            --ray-dark: #32325d;
            --ray-light: #f7fafc;
            --ray-success: #2dce89;
            --ray-danger: #f5365c;
            --ray-warning: #fb6340;
            --ray-info: #11cdef;
            --ray-text: #525f7f;
            --ray-border: rgba(0, 0, 0, 0.1);
            --ray-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            --ray-radius: 0.375rem;
            --ray-radius-lg: 0.5rem;
            --ray-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .ray-profile-container {
            perspective: 1000px;
            padding: 2rem 0;
        }

        .ray-profile-card {
            background: white;
            border-radius: var(--ray-radius-lg);
            box-shadow: var(--ray-shadow);
            overflow: hidden;
            transition: var(--ray-transition);
            transform-style: preserve-3d;
            position: relative;
            margin-bottom: 2rem;
        }

        .ray-profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(50, 50, 93, 0.15), 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .ray-profile-header {
            background: var(--ray-gradient);
            padding: 3rem 2rem 1.5rem;
            text-align: center;
            position: relative;
            color: white;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .ray-profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.2;
        }

        .ray-profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: var(--ray-transition);
            background: white;
            padding: 5px;
        }

        .ray-profile-avatar:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .ray-profile-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .ray-profile-title {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            backdrop-filter: blur(5px);
        }

        .ray-profile-status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .ray-profile-status.active {
            background: rgba(45, 206, 137, 0.1);
            color: var(--ray-success);
        }

        .ray-profile-status.inactive {
            background: rgba(245, 54, 92, 0.1);
            color: var(--ray-danger);
        }

        .ray-profile-status i {
            font-size: 0.5rem;
            margin-left: 0.5rem;
        }

        .ray-profile-body {
            padding: 2rem;
        }

        .ray-section-title {
            position: relative;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--ray-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
        }

        .ray-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50px;
            height: 3px;
            background: var(--ray-gradient);
            border-radius: 3px;
        }

        .ray-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .ray-info-card {
            background: var(--ray-light);
            border-radius: var(--ray-radius);
            padding: 1.5rem;
            transition: var(--ray-transition);
            border: 1px solid var(--ray-border);
            position: relative;
            overflow: hidden;
        }

        .ray-info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .ray-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 3px;
            height: 0;
            background: var(--ray-primary);
            transition: var(--ray-transition);
        }

        .ray-info-card:hover::before {
            height: 100%;
        }

        .ray-info-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: white;
            background: var(--ray-gradient);
            box-shadow: 0 5px 10px rgba(94, 114, 228, 0.2);
        }

        .ray-info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--ray-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .ray-info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--ray-dark);
            margin-bottom: 0;
        }

        .ray-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .ray-stat-card {
            background: white;
            border-radius: var(--ray-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--ray-border);
            transition: var(--ray-transition);
        }

        .ray-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .ray-stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--ray-primary);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .ray-stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--ray-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.7;
        }

        .ray-profile-actions {
            display: flex;
            justify-content: center;
            padding: 1.5rem;
            border-top: 1px solid var(--ray-border);
            background: rgba(247, 250, 252, 0.5);
        }

        .ray-edit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            background: var(--ray-gradient);
            border: none;
            box-shadow: 0 5px 15px rgba(94, 114, 228, 0.3);
            transition: var(--ray-transition);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .ray-edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(94, 114, 228, 0.4);
            color: white;
        }

        .ray-edit-btn i {
            margin-left: 0.5rem;
            font-size: 0.9rem;
        }

        .ray-edit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.3));
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .ray-edit-btn:hover::before {
            transform: translateX(100%);
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

        .ray-animate {
            animation: fadeInUp 0.6s ease forwards;
        }

        .ray-delay-1 { animation-delay: 0.1s; }
        .ray-delay-2 { animation-delay: 0.2s; }
        .ray-delay-3 { animation-delay: 0.3s; }
        .ray-delay-4 { animation-delay: 0.4s; }
        .ray-delay-5 { animation-delay: 0.5s; }

        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .ray-info-grid {
                grid-template-columns: 1fr;
            }

            .ray-profile-name {
                font-size: 1.5rem;
            }

            .ray-profile-body {
                padding: 1.5rem;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">المدير الصيدلي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الملف الشخصي</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="ray-profile-container">
        <div class="ray-profile-card ray-animate">
            <!-- رأس الملف الشخصي -->
            <div class="ray-profile-header">
                <img class="ray-profile-avatar"
                     src="{{ $employee->image ? asset('Dashboard/img/pharmacy_managers/' . $employee->image->filename) : asset('Dashboard/img/default_avatar.png') }}"
                     alt="{{ $employee->name }}">
                <h2 class="ray-profile-name">{{ $employee->name }}</h2>
                <span class="ray-profile-title">
                    <i class="fas fa-x-ray me-2"></i> المدير الصيدلي
                </span>
                @if(property_exists($employee, 'status'))
                    <span class="ray-profile-status {{ $employee->status ? 'active' : 'inactive' }}">
                        <i class="fas fa-circle"></i>
                        {{ $employee->status ? 'نشط' : 'غير نشط' }}
                    </span>
                @endif
            </div>

            <!-- جسم الملف الشخصي -->
            <div class="ray-profile-body">
                <!-- قسم المعلومات الأساسية -->
                <h3 class="ray-section-title ray-animate ray-delay-1">المعلومات الشخصية</h3>
                <div class="ray-info-grid">
                    <div class="ray-info-card ray-animate ray-delay-2">
                        <div class="ray-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="ray-info-label">البريد الإلكتروني</div>
                        <div class="ray-info-value">{{ $employee->email }}</div>
                    </div>

                    @if($employee->phone)
                    <div class="ray-info-card ray-animate ray-delay-3">
                        <div class="ray-info-icon" style="background: linear-gradient(135deg, #11cdef, #1171ef);">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="ray-info-label">رقم الهاتف</div>
                        <div class="ray-info-value" dir="ltr">{{ $employee->phone }}</div>
                    </div>
                    @endif

                    @if($employee->national_id)
                    <div class="ray-info-card ray-animate ray-delay-4">
                        <div class="ray-info-icon" style="background: linear-gradient(135deg, #fb6340, #fbb140);">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="ray-info-label">رقم الهوية</div>
                        <div class="ray-info-value">{{ $employee->national_id }}</div>
                    </div>
                    @endif

                    <div class="ray-info-card ray-animate ray-delay-5">
                        <div class="ray-info-icon" style="background: linear-gradient(135deg, #2dce89, #2dcecc);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="ray-info-label">تاريخ الانضمام</div>
                        <div class="ray-info-value">
                            {{ $employee->created_at ? $employee->created_at->translatedFormat('d M Y') : '-' }}
                        </div>
                    </div>
                </div>


            </div>

            <!-- أزرار التحكم -->
            <div class="ray-profile-actions">
                <a href="{{ route('pharmacy_manager.profile.edit') }}" class="ray-edit-btn">
                    <i class="fas fa-edit"></i> تعديل الملف الشخصي
                </a>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        // عرض رسائل التنبيه
        @if (session('success'))
            notif({
                msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                type: "success",
                position: "center",
                width: 350,
                timeout: 5000,
                animation: "slide"
            });

            // تأثير اهتزاز للبطاقة عند النجاح
            document.querySelector('.ray-profile-card').classList.add('animate__animated', 'animate__tada');
            setTimeout(() => {
                document.querySelector('.ray-profile-card').classList.remove('animate__animated', 'animate__tada');
            }, 1000);
        @endif

        @if (session('error'))
            notif({
                msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                type: "error",
                position: "center",
                width: 350,
                timeout: 7000,
                animation: "slide"
            });
        @endif

        // تأثير تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.ray-animate');
            elements.forEach(el => {
                el.style.opacity = '0';
            });

            setTimeout(() => {
                elements.forEach(el => {
                    el.style.opacity = '1';
                });
            }, 100);
        });
    </script>
@endsection
