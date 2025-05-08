<div wire:ignore> {{-- wire:ignore ضروري إذا كنت تستخدم DataTables --}}
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card custom-card shadow-sm"> {{-- إضافة shadow-sm لمسة بسيطة --}}
                {{-- 1. الهيدر: مطابق تقريبًا --}}
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title-white"><i class="fas fa-users me-2"></i>قائمة المرضى</h5>
                    {{-- استخدام me-2 لـ margin --}}
                    <div class="search-box" style="width: 300px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="fas fa-search text-muted"></i></span> {{-- تحسين بسيط --}}
                            <input type="text" class="form-control border-0" placeholder="ابحث عن مريض..."
                                id="searchInputPatients"> {{-- تغيير ID ليكون فريدًا --}}
                        </div>
                    </div>
                </div>
                {{-- 2. جسم البطاقة والجدول --}}
                <div class="card-body p-0"> {{-- إزالة padding الافتراضي --}}
                    <div class="table-responsive">
                        {{-- 3. تطبيق تنسيق الجدول من مثال الدكاترة --}}
                        <table class="table table-hover table-bordered table-striped mb-0" id="patientsTable">
                            {{-- 4. تطبيق تنسيق رأس الجدول thead من مثال الدكاترة --}}
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    {{-- 5. تغيير اسم العمود وتوسيطه --}}
                                    <th class="text-center">اسم المريض</th>
                                    {{-- 6. إضافة عمود جديد مشابه (تاريخ التسجيل كمثال) --}}
                                    <th class="text-center" width="150">تاريخ التسجيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- التأكد من أن المتغير هو $users أو تعديله --}}
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="text-center align-middle text-muted">{{ $loop->iteration }}</td>
                                        {{-- 7. تطبيق تنسيق عرض اسم المريض كزر محادثة --}}
                                        <td class="align-middle"> {{-- إزالة text-center لجعل المحاذاة لليمين طبيعية --}}
                                            <div class="d-flex align-items-center">
                                                {{-- الصورة بنفس الطريقة --}}
                                                <div class="me-3"> {{-- استخدام me-3 لـ margin --}}
                                                    {{-- التأكد من مسار الصورة الصحيح للمرضى --}}
                                                    @if ($user->image)
                                                        <img src="{{ asset('Dashboard/img/doctors/' . $user->image->filename) }}"
                                                            height="50px" width="50px"
                                                            class="rounded-circle border shadow-sm" alt="صورة الطبيب">
                                                    @else
                                                        <img src="{{ asset('Dashboard/img/faces/doctor_default.png') }}"
                                                            height="50px" width="50px"
                                                            class="rounded-circle border shadow-sm"
                                                            alt="الصورة الافتراضية">
                                                    @endif
                                                </div>
                                                <div>
                                                    {{-- جعل الاسم زرًا للمحادثة --}}
                                                    <button wire:click="createConversation('{{ $user->email }}')"
                                                        {{-- أو $user->id إذا كان هذا ما تتوقعه الدالة --}}
                                                        class="btn btn-link text-dark fw-bold p-0 chat-btn"
                                                        {{-- استخدام fw-bold بدلاً من font-weight-bold --}} {{-- data-patient-id="{{ $user->id }}" --}}
                                                        {{-- يمكن إضافة data attribute إذا احتجت إليه --}} title="محادثة مع {{ $user->name }}">
                                                        {{ $user->name }}
                                                        <i class="fas fa-comment-dots text-primary ms-2"></i>
                                                        {{-- استخدام ms-2 --}}
                                                    </button>
                                                    {{-- عرض الإيميل تحت الاسم --}}
                                                    <span class="d-block text-muted small">{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- 8. عرض بيانات العمود الجديد (تاريخ التسجيل) --}}
                                        <td class="text-center align-middle">
                                            {{-- التأكد من وجود created_at وتنسيقه --}}
                                            <span
                                                class="text-muted small">{{ $user->created_at ? $user->created_at->format('Y-m-d') : 'غير معروف' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">لا يوجد مرضى لعرضهم
                                            حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========================== JavaScript Section ========================== --}}
@section('scripts')
    {{-- استخدام @push لتجنب مشاكل التضمين المتعدد --}}
    <script>
        // التأكد من تحميل jQuery و DataTables قبل تنفيذ الكود
        // يمكن وضع هذا داخل $(document).ready لضمان ذلك
        $(document).ready(function() {
            // 9. تفعيل DataTable بنفس إعدادات مثال الدكاترة
            var patientsDataTable = $('#patientsTable').DataTable({
                responsive: true,
                language: {
                    // استخدام الرابط المباشر لملف اللغة العربية
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json' // استخدام رابط أحدث إذا أمكن
                },
                // تحديد العناصر التي تظهر (f=filtering input, r=processing display, t=table, i=information summary, p=pagination)
                // إزالة 'f' من هنا لأننا سنستخدم البحث الخارجي
                dom: '<"top">rt<"bottom"ip><"clear">',
                pageLength: 10, // عدد الصفوف في كل صفحة
                columnDefs: [{
                        orderable: false,
                        targets: [1]
                    } // مثال: جعل عمود اسم المريض غير قابل للفرز (إذا أردت)
                    // يمكن إضافة المزيد من الإعدادات هنا
                ],
                // يمكنك إضافة initComplete لتعديل placeholder إذا أردت (لكن البحث خارجي الآن)
                // initComplete: function() {
                //     // لا حاجة لتعديل placeholder هنا لأن البحث خارجي
                // }
            });

            // 10. ربط البحث الخارجي بـ DataTable
            $("#searchInputPatients").on("keyup", function() {
                patientsDataTable.search($(this).val()).draw(); // استخدام قيمة الحقل الخارجي لتطبيق البحث
            });

            // 11. (اختياري) تفعيل Tooltips إذا استخدمتها (مثل title على الأزرار)
            // تأكد من تضمين مكتبة Bootstrap JS أو مكتبة tooltips أخرى
            if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !==
                'undefined') { // Check if Bootstrap Tooltip is available
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    // إضافة خيار fallbackPlacement لتحسين العرض
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        fallbackPlacement: ['top', 'bottom']
                    });
                });
            }

            // 12. إعادة تهيئة DataTables عند تحديث Livewire (مهم جداً!)
            // إذا كان هذا الجزء داخل مكون Livewire ويتم تحديثه، يجب إعادة تهيئة DT
            document.addEventListener('livewire:load', function() {
                // هذا سيعمل عند التحميل الأول
                // لكن نحتاج لـ hook عند إعادة العرض
            });
            // استخدام hook الخاص بـ Livewire لإعادة التهيئة بعد التحديث
            window.addEventListener('livewire:update', event => {
                // قد تحتاج إلى تدمير الجدول القديم قبل إعادة التهيئة لتجنب التحذيرات
                if ($.fn.DataTable.isDataTable('#patientsTable')) {
                    $('#patientsTable').DataTable().destroy();
                }
                // إعادة تهيئة DataTable (بنفس الكود أعلاه)
                var reinitializedTable = $('#patientsTable').DataTable({
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
                    },
                    dom: '<"top">rt<"bottom"ip><"clear">',
                    pageLength: 10,
                    columnDefs: [{
                        orderable: false,
                        targets: [1]
                    }]
                });
                // إعادة ربط البحث الخارجي بالجدول الجديد
                $("#searchInputPatients").off('keyup').on("keyup",
            function() { // استخدام off لإزالة المستمع القديم
                    reinitializedTable.search($(this).val()).draw();
                });
                // إعادة تفعيل tooltips
                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl, {
                            fallbackPlacement: ['top', 'bottom']
                        });
                    });
                }
                console.log('DataTables reinitialized after Livewire update.'); // رسالة للتحقق
            });

        });
    </script>
@endsection

{{-- ========================== CSS Section ========================== --}}
@section('css')
    {{-- استخدام @push لـ CSS أيضاً --}}
    <style>
        /* 13. نسخ أنماط زر المحادثة .chat-btn */
        .chat-btn {
            background: none !important;
            /* لضمان عدم وجود خلفية */
            border: none !important;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            text-decoration: none !important;
            display: inline-block;
            /* لضمان تطبيق transform */
        }

        .chat-btn:hover {
            color: var(--bs-primary) !important;
            /* استخدام متغير Bootstrap للون الأساسي */
            transform: translateX(3px);
            /* نفس التأثير */
        }

        .chat-btn:active {
            transform: scale(0.95);
            /* نفس التأثير */
        }

        /* 14. (اختياري) تعديل بسيط للبحث الخارجي */
        .search-box .input-group-text {
            border-radius: 0 0.375rem 0.375rem 0 !important;
            /* تعديل لليمين */
        }

        .search-box input.form-control {
            border-radius: 0.375rem 0 0 0.375rem !important;
            /* تعديل لليسار */
            border-left: 0;
            /* إزالة الحد الفاصل */
        }

        .search-box .input-group-text i {
            color: #6c757d;
            /* لون أيقونة البحث */
        }

        /* 15. الحفاظ على أو تكييف أنماط card-header */
        .card-header.bg-primary.text-white .card-title-white {
            color: #ffffff;
            /* ضمان لون العنوان */
        }

        /* تخصيص بسيط لشكل DataTable */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.3em 0.7em;
            /* تصغير padding قليلاً */
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 0.85em;
            font-size: 0.85rem;
        }

        /* تعديل شكل حقل البحث الافتراضي لـ DT إذا قررت استخدامه */
        /* .dataTables_wrapper .dataTables_filter input { ... } */
    </style>
@endsection
