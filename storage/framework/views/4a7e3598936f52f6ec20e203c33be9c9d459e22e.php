<div class="chat-box-container">
    <?php if($selected_conversation && isset($receviverUser)): ?> 
        <div class="chat-header">
            <div class="user-info">
                <div class="avatar">
                    <?php
                        $imagePath = null;
                        $defaultImage = URL::asset('Dashboard/img/default_avatar.png');

                        if ($receviverUser instanceof \App\Models\Doctor) {
                            $defaultImage = URL::asset('Dashboard/img/faces/doctor_default.png');
                            if ($receviverUser->image && $receviverUser->image->filename) {
                                $imagePath = URL::asset('Dashboard/img/doctors/' . $receviverUser->image->filename);
                            }
                        } elseif ($receviverUser instanceof \App\Models\Patient) {
                            $defaultImage = URL::asset('Dashboard/img/default_patient_avatar.png');
                            if ($receviverUser->image && $receviverUser->image->filename) {
                                $imagePath = URL::asset('Dashboard/img/patients/' . $receviverUser->image->filename);
                            }
                        }
                    ?>

                    <img src="<?php echo e($imagePath ?? $defaultImage); ?>"
                         alt="<?php echo e(trans('doctors.chat_box_user_image_alt', ['name' => $receviverUser->name ?? trans('doctors.chat_box_username_placeholder')])); ?>"
                         width="40" height="40"
                         style="border-radius: 50%; object-fit: cover;">
                </div>
                <div class="user-details">
                    <h6><?php echo e($receviverUser->name ?? trans('doctors.chat_box_username_placeholder')); ?></h6>
                    
                </div>
            </div>
            <div class="chat-actions">
                <div class="dropdown">
                    <button class="btn btn-sm btn-light rounded-circle" type="button" id="chatActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chatActionsDropdown">
                        <li><a class="dropdown-item" href="#"><?php echo e(trans('doctors.chat_box_view_profile')); ?></a></li>
                        <li><a class="dropdown-item" href="#"><?php echo e(trans('doctors.chat_box_contact_info')); ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><?php echo e(trans('doctors.chat_box_delete_conversation')); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="messages-container" id="messagesContainer">
            <?php if(isset($messages) && $messages->count() > 0): ?>
                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="message-wrapper <?php echo e($auth_email == $message->sender_email ? 'sent' : 'received'); ?>"
                        wire:key="message-<?php echo e($message->id); ?>-<?php echo e($message->created_at->timestamp); ?>">
                        <div class="message-bubble">
                            <div class="message-content">
                                <?php echo e($message->body); ?>

                            </div>
                            <div class="message-meta">
                                <span class="message-time">
                                    <?php echo e(optional($message->created_at)->locale(app()->getLocale())->translatedFormat('h:i A')); ?>

                                </span>
                                <?php if($auth_email == $message->sender_email): ?>
                                    <span class="message-status ms-1">
                                        <i class="fas fa-check-double <?php echo e($message->read ? 'text-primary' : 'text-muted'); ?>"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="empty-chat" style="text-align: center; padding-top: 50px;">
                    <i class="far fa-comment" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <p><?php echo e(trans('doctors.chat_box_no_messages_yet')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="empty-chat">
            <i class="far fa-comment-dots fa-3x"></i>
            <p style="margin-top: 15px; font-size: 1.1rem;"><?php echo e(trans('doctors.chat_box_select_conversation_prompt')); ?></p>
        </div>
    <?php endif; ?>
</div>

<style>
    /* تحسينات عامة */
    .chat-box-container {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #f5f7fb;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    /* تحسينات الهيدر */
    .chat-header {
        padding: 14px 20px;
        background: #ffffff;
        border-bottom: 1px solid #eaeef5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        z-index: 10;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #eaeef5;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-details h6 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
    }

    .chat-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-actions button {
        background: #f8fafc;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .chat-actions button:hover {
        background: #e2e8f0;
        color: #475569;
    }

    /* تحسينات منطقة الرسائل */
    .messages-container {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f5f7fb;
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 20px 20px;
    }

    /* تحسينات فقاعات الرسائل */
    .message-wrapper {
        max-width: 80%;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }

    .message-wrapper.sent {
        align-self: flex-end;
    }

    .message-wrapper.received {
        align-self: flex-start;
    }

    .message-bubble {
        padding: 12px 16px;
        border-radius: 18px;
        position: relative;
        word-wrap: break-word;
        line-height: 1.5;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .message-wrapper.sent .message-bubble {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-wrapper.received .message-bubble {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 4px;
    }

    /* تحسينات ميتا البيانات */
    .message-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        font-size: 0.75rem;
    }

    .message-wrapper.sent .message-meta {
        justify-content: flex-end;
        color: rgba(255, 255, 255, 0.7);
    }

    .message-wrapper.received .message-meta {
        justify-content: flex-start;
        color: #64748b;
    }

    .message-status i {
        font-size: 0.8em;
    }

    /* تحسينات حالة المحادثة الفارغة */
    .empty-chat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #94a3b8;
        text-align: center;
        padding: 30px;
    }

    .empty-chat i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-chat p {
        font-size: 1.1rem;
        max-width: 300px;
        line-height: 1.6;
    }

    /* تأثيرات حركية */
    .message-wrapper {
        animation: fadeInUp 0.3s ease forwards;
    }

    @keyframes  fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* شريط التمرير */
    .messages-container::-webkit-scrollbar {
        width: 6px;
    }

    .messages-container::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
    }

    .messages-container::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 3px;
    }

    /* تأثيرات hover للرسائل */
    .message-wrapper:hover {
        transform: translateX(2px);
    }

    .message-wrapper.sent:hover {
        transform: translateX(-2px);
    }
</style>

<script>
    // تحسينات التمرير التلقائي
    function scrollToBottomMessages(smooth = true) {
        const container = document.getElementById('messagesContainer');
        if (container) {
            setTimeout(() => {
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: smooth ? 'smooth' : 'auto'
                });
            }, 100);
        }
    }

    // تهيئة المحادثة
    document.addEventListener('DOMContentLoaded', function() {
        // التمرير للأسفل عند التحميل
        scrollToBottomMessages(false);

        // إضافة تأثيرات للرسائل الجديدة
        const messages = document.querySelectorAll('.message-wrapper');
        messages.forEach((msg, index) => {
            msg.style.animationDelay = `${index * 0.05}s`;
        });

        // تحديث حالة القراءة عند التمرير للأسفل
        const messagesContainer = document.getElementById('messagesContainer');
        if (messagesContainer) {
            messagesContainer.addEventListener('scroll', function() {
                if (this.scrollTop + this.clientHeight >= this.scrollHeight - 50) {
                    // يمكنك إضافة كود لتحديث حالة القراءة هنا
                    console.log('تم الوصول إلى آخر الرسائل');
                }
            });
        }
    });

    // Livewire Events
    document.addEventListener('livewire:load', function() {
        scrollToBottomMessages(false);

        // تحديث تلقائي عند تلقي رسائل جديدة
        Livewire.hook('message.processed', (message, component) => {
            if (component.fingerprint.name === 'chat-component') {
                scrollToBottomMessages();
            }
        });
    });

    // إدارة المرفقات
    function handleFileUpload(event) {
        const files = event.target.files;
        if (files.length > 5) {
            alert('الحد الأقصى للمرفقات هو 5 ملفات');
            event.target.value = '';
            return;
        }

        // عرض معاينة المرفقات
        const previewContainer = document.getElementById('attachmentsPreview');
        if (previewContainer) {
            previewContainer.innerHTML = '';
            Array.from(files).forEach(file => {
                const preview = document.createElement('div');
                preview.className = 'attachment-preview';
                preview.innerHTML = `
                    <span>${file.name}</span>
                    <small>${(file.size / 1024).toFixed(1)} KB</small>
                `;
                previewContainer.appendChild(preview);
            });
        }
    }
</script>
<?php /**PATH H:\Hospital_Sys\resources\views/livewire/chat/chatbox.blade.php ENDPATH**/ ?>