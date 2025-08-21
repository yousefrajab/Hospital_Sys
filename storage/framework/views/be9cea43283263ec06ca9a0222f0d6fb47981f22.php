<?php $__env->startSection('title'); ?>
    مركز المحادثات
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    
    <link href="<?php echo e(URL::asset('Dashboard/plugins/lightslider/css/lightslider.min.css')); ?>" rel="stylesheet">
    
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('doctors.conversations')); ?></h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('doctors.message_center')); ?></span>
            </div>
        </div>
        
    </div>
    <!-- breadcrumb -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="chat-app-container">
        
        <div class="chat-sidebar" id="chatSidebar">
            
            <div class="p-3 border-bottom">
                <h5 class="mb-0 font-weight-semibold"><?php echo e(trans('doctors.conversations')); ?></h5>
                
                <?php if(Auth::guard('doctor')->check()): ?>
                    <a href="<?php echo e(route('doctor.chat.patients')); ?>" class="btn btn-primary btn-sm w-100 mt-2">
                        <i class="fas fa-plus-circle me-1"></i> <?php echo e(trans('doctors.new_conversation_with_patient')); ?>

                    </a>
                <?php elseif(Auth::guard('patient')->check()): ?>
                    <a href="<?php echo e(route('chat.doctors')); ?>" class="btn btn-info btn-sm w-100 mt-2">
                        <i class="fas fa-plus-circle me-1"></i> <?php echo e(trans('doctors.new_conversation_with_doctor')); ?>

                    </a>
                <?php endif; ?>
            </div>
            <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('chat.chatlist')->html();
} elseif ($_instance->childHasBeenRendered('l1773497252-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l1773497252-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l1773497252-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l1773497252-0');
} else {
    $response = \Livewire\Livewire::mount('chat.chatlist');
    $html = $response->html();
    $_instance->logRenderedChild('l1773497252-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
        </div>

        
        <div class="chat-main-content">
            
            <a class="main-header-arrow" href="javascript:void(0);" id="ChatSidebarToggle"><i
                    class="icon ion-md-menu"></i></a>

            <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('chat.chatbox')->html();
} elseif ($_instance->childHasBeenRendered('l1773497252-1')) {
    $componentId = $_instance->getRenderedChildComponentId('l1773497252-1');
    $componentTag = $_instance->getRenderedChildComponentTagName('l1773497252-1');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l1773497252-1');
} else {
    $response = \Livewire\Livewire::mount('chat.chatbox');
    $html = $response->html();
    $_instance->logRenderedChild('l1773497252-1', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
            <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('chat.send-message')->html();
} elseif ($_instance->childHasBeenRendered('l1773497252-2')) {
    $componentId = $_instance->getRenderedChildComponentId('l1773497252-2');
    $componentTag = $_instance->getRenderedChildComponentTagName('l1773497252-2');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l1773497252-2');
} else {
    $response = \Livewire\Livewire::mount('chat.send-message');
    $html = $response->html();
    $_instance->logRenderedChild('l1773497252-2', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/lightslider/js/lightslider.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/js/chat.js')); ?>"></script> 

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/livewire/chat/main.blade.php ENDPATH**/ ?>