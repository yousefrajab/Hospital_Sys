{{-- resources/views/livewire/chat/main.blade.php --}}
@extends('Dashboard.layouts.master')

@section('title')
    مركز المحادثات
@endsection

@push('css')
    {{-- يمكنك وضع CSS عام للمحادثات هنا إذا أردت --}}
    <link href="{{ URL::asset('Dashboard/plugins/lightslider/css/lightslider.min.css') }}" rel="stylesheet">
    {{-- تأكد من المسار --}}
    <style>
        body {
            overflow: hidden;
            /* لمنع شريط التمرير المزدوج إذا كانت المحادثة تملأ الصفحة */
        }

        .chat-app-container {
            display: flex;
            height: calc(100vh - 120px);
            /* اضبط الارتفاع حسب الهيدر والفوتر في layout.master */
            background-color: #f4f7f9;
            /* لون خلفية فاتح */
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            /* مهم جداً */
            margin-top: 20px;
            /* مسافة من الأعلى */
        }

        .chat-sidebar {
            width: 340px;
            /* عرض ثابت للشريط الجانبي */
            min-width: 300px;
            background-color: #ffffff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
        }

        .chat-main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: #f9fafb;
            /* لون مختلف قليلاً لمنطقة المحتوى */
        }

        /* لصفحة بدء المحادثة (قائمة المستخدمين) */
        .users-list-container {
            padding: 20px;
            overflow-y: auto;
            height: calc(100vh - 180px);
            /* مثال لارتفاع */
        }

        .user-card-item {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border-radius: 10px;
        }

        .user-card-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .user-card-item .card-body {
            padding: 1rem;
        }

        .user-card-item img {
            border: 2px solid #eee;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .chat-sidebar {
                /* يمكن جعلها تظهر/تختفي في الموبايل */
                position: absolute;
                left: -340px;
                /* تبدأ مخفية */
                z-index: 1000;
                height: 100%;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            }

            .chat-sidebar.active {
                left: 0;
            }

            .main-header-arrow {
                /* زر إظهار/إخفاء الشريط الجانبي */
                display: block !important;
            }
        }

        .main-header-arrow {
            display: none;
            /* يكون مخفيًا على الشاشات الكبيرة */
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 1.5rem;
            z-index: 1001;
            cursor: pointer;
        }
    </style>
@endpush

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المحادثات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ مركز الرسائل</span>
            </div>
        </div>
        {{-- يمكنك إضافة أزرار إجراءات هنا --}}
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="chat-app-container">
        {{-- الشريط الجانبي لقائمة المحادثات --}}
        <div class="chat-sidebar" id="chatSidebar">
            {{-- يمكن إضافة هيدر للشريط الجانبي هنا (بحث، زر محادثة جديدة) --}}
            <div class="p-3 border-bottom">
                <h5 class="mb-0 font-weight-semibold">المحادثات</h5>
                {{-- مثال لزر بدء محادثة جديدة إذا كنت طبيبًا --}}
                @if (Auth::guard('doctor')->check())
                    <a href="{{ route('doctor.chat.patients') }}" class="btn btn-primary btn-sm w-100 mt-2">
                        <i class="fas fa-plus-circle me-1"></i> محادثة جديدة مع مريض
                    </a>
                @elseif(Auth::guard('patient')->check())
                    <a href="{{ route('chat.doctors') }}" class="btn btn-info btn-sm w-100 mt-2">
                        <i class="fas fa-plus-circle me-1"></i> محادثة جديدة مع طبيب
                    </a>
                @endif
            </div>
            @livewire('chat.chatlist')
        </div>

        {{-- المحتوى الرئيسي للمحادثة --}}
        <div class="chat-main-content">
            {{-- زر إظهار/إخفاء الشريط الجانبي في شاشات الجوال --}}
            <a class="main-header-arrow" href="javascript:void(0);" id="ChatSidebarToggle"><i
                    class="icon ion-md-menu"></i></a>

            @livewire('chat.chatbox')
            @livewire('chat.send-message')
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('Dashboard/plugins/lightslider/js/lightslider.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/chat.js') }}"></script> {{-- ملف JS القالب الافتراضي للمحادثات --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // لمنع التمرير المزدوج إذا كان الـ layout الرئيسي به شريط تمرير بالفعل
            // document.body.style.overflow = 'hidden';

            // لتشغيل/إيقاف الشريط الجانبي في شاشات الجوال
            const chatSidebar = document.getElementById('chatSidebar');
            const chatSidebarToggle = document.getElementById('ChatSidebarToggle');
            const chatBodyHide = document.getElementById('ChatBodyHide'); //  إذا كان لديك زر الإخفاء من Chatbox

            if (chatSidebarToggle) {
                chatSidebarToggle.addEventListener('click', function() {
                    chatSidebar.classList.toggle('active');
                });
            }

            // لإخفاء الشريط الجانبي عند اختيار محادثة في شاشات الجوال
            window.addEventListener('conversation-selected', event => {
                if (window.innerWidth < 992) { // نفس نقطة التوقف في CSS
                    if (chatSidebar) chatSidebar.classList.remove('active');
                }
            });

            // إذا كان لديك زر "إخفاء" داخل الـ chatbox للعودة لقائمة المحادثات في الموبايل
            if (chatBodyHide) { // هذا الزر من القالب الأصلي قد لا يكون ضرورياً مع التصميم الجديد
                chatBodyHide.addEventListener('click', function(e) {
                    e.preventDefault();
                    // يمكن إضافة منطق هنا لإظهار الشريط الجانبي إذا أردت
                    // chatSidebar.classList.add('active');
                    console.log('ChatBodyHide clicked - you might want to show sidebar here on mobile.');
                });
            }
        });

        // الكود الخاص بـ scrollTop من ملفك الأصلي
        document.addEventListener('livewire:load', function() {
            // لا حاجة لهذا الآن، التمرير يتم في Chatbox و SendMessage
            // Livewire.on('messageSent', () => {
            //     const chatBody = document.getElementById('ChatBody'); //  تأكد من وجود عنصر بهذا الـ ID
            //     if (chatBody) {
            //        chatBody.scrollTop = chatBody.scrollHeight;
            //     }
            // });
        });
    </script>
@endsection
