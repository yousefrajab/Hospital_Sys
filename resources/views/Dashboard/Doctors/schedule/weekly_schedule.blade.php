{{-- resources/views/Dashboard/Doctors/profile/show_schedule.blade.php --}}
@extends('Dashboard.layouts.master') {{-- تأكد من اسم الـ Layout --}}

{{-- ========================== CSS Section ========================== --}}
@section('css')
    {{-- استيراد Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Animate.css لتأثيرات الدخول --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { /* استخدام نفس الألوان للاتساق */
            --primary-color: #4A90E2; --secondary-color: #4A4A4A; --accent-color: #50E3C2;
            --light-bg: #f9fbfd; --border-color: #e5e9f2; --white-color: #ffffff;
            --success-color: #2ecc71; --warning-color: #f39c12; --danger-color: #e74c3c; --info-color: #3498db;
            --text-dark: #34495e; --text-muted: #95a5a6;
            --card-shadow: 0 8px 25px rgba(140, 152, 164, 0.1);
        }
        body { background: var(--light-bg); font-family: 'Cairo', sans-serif; }

        .schedule-view-container { background: var(--white-color); border-radius: 16px; box-shadow: var(--card-shadow); padding: 2rem 2.5rem; margin-bottom: 2rem; }
        .schedule-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color); }
        .schedule-title { font-size: 1.5rem; font-weight: 700; color: var(--secondary-color); display: flex; align-items: center; gap: 0.75rem; }
        .schedule-title i { color: var(--primary-color); }
        .doctor-badge { background: #eef2ff; color: var(--primary-color); padding: 0.4rem 0.8rem; border-radius: 50px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; }
        .doctor-badge i { opacity: 0.8; }

        /* تصميم جدول عرض الساعات */
        .weekly-schedule-table { width: 100%; border-collapse: separate; /* فصل الحدود */ border-spacing: 0 10px; /* مسافة بين الصفوف */ margin-top: 1rem; }
        .weekly-schedule-table th, .weekly-schedule-table td { padding: 1rem 1.25rem; text-align: right; vertical-align: middle; }
        .weekly-schedule-table thead th {
            background-color: var(--primary-color); color: white; font-weight: 600; font-size: 0.95rem;
            border: none; /* إزالة حدود الهيدر */
        }
        .weekly-schedule-table thead th:first-child { border-radius: 0 10px 10px 0; } /* زوايا دائرية للهيدر (RTL) */
        .weekly-schedule-table thead th:last-child { border-radius: 10px 0 0 10px; }

        .weekly-schedule-table tbody tr { background-color: #fdfdff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .weekly-schedule-table tbody tr:hover { transform: scale(1.02); box-shadow: 0 8px 20px rgba(0,0,0,0.1); z-index: 10; position: relative; }
        .weekly-schedule-table tbody td { border: 1px solid #f0f3f8; border-width: 1px 0; /* حدود أفقية فقط */ color: var(--text-dark); font-size: 0.9rem; }
        .weekly-schedule-table tbody td:first-child { border-right: 1px solid #f0f3f8; border-radius: 0 10px 10px 0; } /* حدود وزوايا (RTL) */
        .weekly-schedule-table tbody td:last-child { border-left: 1px solid #f0f3f8; border-radius: 10px 0 0 10px; }

        .day-cell { display: flex; align-items: center; gap: 0.75rem; font-weight: 600; color: var(--secondary-color); }
        .day-icon { color: var(--primary-color); font-size: 1.2rem; }
        .time-cell { font-weight: 500; direction: ltr; text-align: center; } /* لضمان عرض الوقت بشكل صحيح */
        .duration-cell { text-align: center; }
        .status-cell .badge { font-size: 0.8rem; padding: 0.4rem 0.8rem; }
        .badge-success-light { background-color: rgba(40, 167, 69, 0.1); color: #1d643b; }
        .badge-secondary-light { background-color: rgba(108, 117, 125, 0.1); color: #5a6268; }
        .breaks-list { list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: var(--danger-color); }
        .breaks-list li { margin-top: 3px; }
        .breaks-list i { margin-left: 4px; opacity: 0.8; }

        .no-schedule-alert { border-radius: 12px; }

         /* زر التعديل */
         .btn-edit-schedule { background: var(--primary-gradient); color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 50px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2); }
         .btn-edit-schedule:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(67, 97, 238, 0.3); }

    </style>
@endsection

@section('title')
    جدول عمل الطبيب - {{ $doctor->name }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
     <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">لوحة تحكم الطبيب</h4>
                 <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('doctor.profile.show') }}">الملف الشخصي</a></span>
                 <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض جدول العمل</span>
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
             {{-- زر للانتقال لصفحة تعديل الجدول (التي أنشأناها) --}}
             <a href="{{ route('doctors.schedule.edit', $doctor->id) }}" class="btn btn-primary-gradient btn-sm">
                 <i class="fas fa-edit me-1"></i> تعديل جدول العمل
             </a>
             <a href="{{ route('doctor.profile.show') }}" class="btn btn-outline-secondary btn-sm mr-2">
                 <i class="fas fa-user me-1"></i> العودة للملف الشخصي
            </a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

{{-- ====================== HTML Content Section ===================== --}}
@section('content')
    @include('Dashboard.messages_alert') {{-- لعرض رسائل النجاح/الخطأ --}}

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="schedule-view-container animate__animated animate__fadeIn">
                 <div class="schedule-header">
                    <h2 class="schedule-title">
                        <i class="fas fa-calendar-week"></i>
                        جدول ساعات العمل الأسبوعي
                    </h2>
                     <span class="doctor-badge">
                        <i class="fas fa-user-md"></i>
                        {{ $doctor->name }}
                    </span>
                </div>

                 @if($workingDaysCollection && $workingDaysCollection->isNotEmpty()) {{-- استخدام المتغير الجديد --}}
                     <div class="table-responsive">
                         <table class="table weekly-schedule-table">
                             <thead>
                                 <tr>
                                     <th>اليوم</th>
                                     <th class="text-center">وقت البدء</th>
                                     <th class="text-center">وقت الانتهاء</th>
                                     <th class="text-center">مدة الموعد</th>
                                     <th class="text-center">الحالة</th>
                                     <th>الاستراحات</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @php
                                     $daysOrder = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                                     // تحويل المجموعة المفهرسة إلى مصفوفة عادية للفرز (إذا لزم الأمر)
                                     // $workingDaysMapped = $workingDaysCollection->keyBy('day'); // لا نحتاج keyBy هنا
                                 @endphp

                                 @foreach($daysOrder as $dayName)
                                     @php
                                         // البحث عن اليوم في المجموعة الممررة
                                         $workingDay = $workingDaysCollection->first(function ($item) use ($dayName) {
                                             return strcasecmp($item->day, $dayName) === 0;
                                         });
                                         $dayIcons = ['saturday' => 'fa-star-of-david', 'sunday' => 'fa-sun', 'monday' => 'fa-moon', 'tuesday' => 'fa-calendar-day', 'wednesday' => 'fa-calendar-day', 'thursday' => 'fa-calendar-week', 'friday' => 'fa-mosque'];
                                     @endphp
                                     <tr class="animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.07 }}s;">
                                         <td class="day-cell">
                                              <span class="day-icon"><i class="fas {{ $dayIcons[strtolower($dayName)] ?? 'fa-calendar' }}"></i></span>
                                              <span>{{ trans('doctors.days.' . strtolower($dayName)) ?? $dayName }}</span>
                                         </td>
                                         @if($workingDay && $workingDay->active)
                                             <td class="time-cell"><span>{{ \Carbon\Carbon::parse($workingDay->start_time)->format('h:i A') }}</span></td>
                                             <td class="time-cell"><span>{{ \Carbon\Carbon::parse($workingDay->end_time)->format('h:i A') }}</span></td>
                                             <td class="duration-cell"><span>{{ $workingDay->appointment_duration }} دقيقة</span></td>
                                             <td class="status-cell text-center"><span class="badge badge-success-light"><i class="fas fa-check-circle me-1"></i>نشط</span></td>
                                             <td>
                                                 @if($workingDay->breaks->isNotEmpty())
                                                     <ul class="breaks-list">
                                                         @foreach($workingDay->breaks as $break)
                                                             <li><i class="fas fa-mug-hot"></i> {{ \Carbon\Carbon::parse($break->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($break->end_time)->format('h:i A') }} {{ $break->reason ? "({$break->reason})" : '' }}</li>
                                                         @endforeach
                                                     </ul>
                                                 @else
                                                     <span class="text-muted small">لا يوجد</span>
                                                 @endif
                                             </td>
                                         @else
                                             <td colspan="5" class="text-center text-muted font-italic">غير متاح / إجازة</td>
                                         @endif
                                     </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>
                 @else
                     <div class="alert alert-warning no-schedule-alert text-center">
                          <i class="fas fa-exclamation-triangle fa-2x mb-3 d-block"></i>
                          لم يتم تحديد جدول ساعات العمل لهذا الطبيب بعد.
                          <a href="{{ route('doctors.schedule.edit', $doctor->id) }}" class="btn btn-primary btn-sm mt-3">إضافة / تعديل الجدول</a>
                     </div>
                 @endif

                 {{-- زر العودة لصفحة تعديل الجدول --}}
                 <div class="text-center mt-5">
                       <a href="{{ route('doctors.schedule.edit', $doctor->id) }}" class="btn btn-edit-schedule">
                            <i class="fas fa-edit"></i> تعديل جدول العمل
                       </a>
                 </div>

            </div>
        </div>
    </div>
@endsection

{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    @parent
    {{-- لا نحتاج JS خاص لهذه الصفحة في الغالب، إلا إذا أضفنا تفاعلات أخرى --}}
    <script>
        console.log('Doctor schedule view page loaded.');
    </script>
@endsection
