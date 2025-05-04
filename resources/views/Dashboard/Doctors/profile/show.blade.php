{{-- resources/views/Dashboard/Doctors/profile/show.blade.php --}}
@extends('Dashboard.layouts.master') {{-- تأكد من اسم الـ layout الصحيح --}}

@section('title')
    الملف الشخصي - {{ $doctor->name }}
@endsection

@section('css')
    {{-- استيراد Font Awesome إذا لم يكن مستورداً --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --profile-primary-color: #435ebe;
            /* لون أساسي (أزرق بنفسجي) */
            --profile-secondary-color: #5a6cec;
            /* لون ثانوي أفتح */
            --profile-gradient: linear-gradient(135deg, var(--profile-primary-color), var(--profile-secondary-color));
            --profile-text-light: #f8f9fa;
            --profile-text-dark: #495057;
            --profile-text-muted: #6c757d;
            --profile-bg-light: #f8f9fa;
            --profile-border-color: #dee2e6;
            --profile-success-color: #28a745;
            --profile-danger-color: #dc3545;
        }

        /* بطاقة الملف الشخصي الرئيسية */
        .doctor-profile-page {
            padding-top: 20px;
        }

        .profile-card-modern {
            background-color: #fff;
            border-radius: 1rem;
            /* زوايا أكثر دائرية */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
            /* لإخفاء أي تجاوز */
        }

        /* رأس البطاقة */
        .profile-header-modern {
            background: var(--profile-gradient);
            color: var(--profile-text-light);
            padding: 40px 30px;
            display: flex;
            align-items: center;
            gap: 30px;
            position: relative;
        }

        /* يمكن إضافة نمط خلفية هادئ */
        .profile-header-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z' /%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 0;
        }

        .profile-avatar-modern {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.8);
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            /* ليكون فوق النمط */
            z-index: 1;
        }

        .profile-info-modern {
            flex-grow: 1;
            position: relative;
            z-index: 1;
        }

        .profile-info-modern h3 {
            margin: 0 0 5px;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .profile-info-modern .specialty {
            display: block;
            font-size: 1rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 10px;
        }

        .profile-status-modern {
            display: inline-flex;
            /* لعرضها بجانب النص */
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .profile-status-modern.active {
            background-color: rgba(40, 167, 69, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .profile-status-modern.inactive {
            background-color: rgba(220, 53, 69, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .profile-status-modern i {
            font-size: 0.8em;
        }

        /* جسم البطاقة والمعلومات */
        .profile-body-modern {
            padding: 30px;
        }

        .info-section {
            margin-bottom: 35px;
        }

        .info-section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--profile-primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--profile-secondary-color);
            display: inline-block;
            /* لمنع الخط من أخذ كامل العرض */
        }

        /* شبكة لعرض بطاقات المعلومات */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .info-card {
            background-color: var(--profile-bg-light);
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #eee;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .info-card .info-icon {
            font-size: 1.8rem;
            color: var(--profile-primary-color);
            width: 45px;
            height: 45px;
            background-color: rgba(67, 94, 190, 0.1);
            /* خلفية شفافة للأيقونة */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .info-card .info-content label {
            display: block;
            font-size: 0.85rem;
            color: var(--profile-text-muted);
            margin-bottom: 3px;
            font-weight: 500;
        }

        .info-card .info-content p {
            margin: 0;
            font-size: 1rem;
            color: var(--profile-text-dark);
            font-weight: 600;
            word-break: break-word;
            /* لكسر النصوص الطويلة */
        }

        .info-card .info-content .badge {
            font-size: 0.9rem;
            padding: 5px 10px;
        }

        /* جدول ساعات العمل */
        .working-hours-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .working-hours-table th,
        .working-hours-table td {
            padding: 12px 15px;
            text-align: right;
            /* تعديل لمحاذاة النص للعربية */
            border-bottom: 1px solid var(--profile-border-color);
        }

        .working-hours-table thead th {
            background-color: var(--profile-bg-light);
            font-weight: 600;
            color: var(--profile-text-dark);
            font-size: 0.95rem;
        }

        .working-hours-table tbody tr:last-child td {
            border-bottom: none;
        }

        .working-hours-table td {
            color: var(--profile-text-muted);
            font-size: 0.9rem;
        }

        .working-hours-table td span {
            font-weight: 500;
            color: var(--profile-text-dark);
        }

        .working-hours-table .day-inactive td {
            color: #ccc;
            font-style: italic;
        }

        .working-hours-table .day-inactive span {
            color: #ccc;
        }

        .working-hours-table .breaks {
            font-size: 0.8rem;
            color: var(--profile-danger-color);
            display: block;
            margin-top: 5px;
        }

        /* زر التعديل */
        .profile-actions-modern {
            padding: 25px 30px;
            text-align: center;
            /* جعله في الوسط */
            border-top: 1px solid #eee;
        }

        /* --- أزرار الحفظ والإلغاء --- */
        .form-actions {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--profile-border-color);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .edit-profile-btn {
            background: var(--profile-gradient);
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            /* زر دائري الحواف */
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .edit-profile-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 94, 190, 0.3);
        }

        .edit-profile-btn i {
            margin-left: 8px;
        }

        /* تعديل هامش الأيقونة للعربية */
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                {{-- يمكنك تعديل مسار التنقل هنا --}}
                <h4 class="content-title mb-0 my-auto">لوحة تحكم الطبيب</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الملف الشخصي</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="doctor-profile-page">
        <div class="profile-card-modern">
            {{-- رأس الملف الشخصي --}}
            <div class="profile-header-modern">
                <img class="profile-avatar-modern" alt="{{ $doctor->name }}"
                    src="{{ $doctor->image ? asset('Dashboard/img/doctors/' . $doctor->image->filename) : asset('Dashboard/img/doctor_default.png') }}">
                <div class="profile-info-modern">
                    <h3>{{ $doctor->name }}</h3>
                    {{-- افترض أن لديك حقل تخصص أو يمكنك عرض اسم القسم --}}
                    <span class="specialty">{{ $doctor->section->name ?? 'تخصص غير محدد' }}</span>
                    <span class="profile-status-modern {{ $doctor->status ? 'active' : 'inactive' }}">
                        <i class="fas fa-circle"></i>
                        {{ $doctor->status ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
            </div>

            {{-- جسم الملف الشخصي --}}
            <div class="profile-body-modern">

                {{-- قسم معلومات الاتصال والمعلومات الأساسية --}}
                <div class="info-section">
                    <h5 class="info-section-title">المعلومات الأساسية والشخصية</h5>
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-envelope"></i></div>
                            <div class="info-content">
                                <label>{{ trans('doctors.email') }}</label>
                                <p>{{ $doctor->email }}</p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                            <div class="info-content">
                                <label>{{ trans('doctors.phone') }}</label>
                                <p dir="ltr">{{ $doctor->phone ?: 'غير محدد' }}</p> {{-- استخدام dir="ltr" للهاتف --}}
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-id-card"></i></div>
                            <div class="info-content">
                                <label>{{ trans('doctors.national_id') }}</label>
                                <p>{{ $doctor->national_id ?: 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-building"></i></div>
                            <div class="info-content">
                                <label>{{ trans('doctors.section') }}</label>
                                <p>{{ $doctor->section->name ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-clipboard-list"></i></div>
                            <div class="info-content">
                                <label>{{ trans('doctors.number_of_statements') }}</label>
                                <p>{{ $doctor->number_of_statements }} (الحد الأقصى للمواعيد اليومية)</p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="info-content">
                                <label>{{ trans('doctors.created_at') }}</label>
                                <p>{{ $doctor->created_at ? $doctor->created_at->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn edit-profile-btn">
                    <a href="{{ route('doctor.profile.edit') }}" class="btn-cancel" style="color: white"> {{-- تأكد من اسم مسار عرض ملف الطبيب --}}
                        <i class="fas fa-edit"></i> تعديل الملف الشخصي
                    </a>
                </button>
            </div>

        </div>
    </div>
@endsection

@section('js')
    {{-- يمكنك إضافة أي JS خاص بهذه الصفحة هنا --}}
    <script>
        console.log("Doctor profile page loaded for: {{ $doctor->name }}");
    </script>
@endsection
