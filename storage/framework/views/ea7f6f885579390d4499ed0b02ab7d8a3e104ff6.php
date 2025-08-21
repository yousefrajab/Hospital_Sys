
<div>
    <div class="main-chat-list ps" id="ChatListContainer" style="position: relative; overflow-y: auto; height: calc(100% - 130px);">
        <?php if($conversations && $conversations->count() > 0): ?>
            <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $otherUser = null;
                    $otherUserType = null;
                    $imagePath = null;
                    $defaultImage = URL::asset('Dashboard/img/faces/user_default.png');

                    if ($this->auth_user_guard === 'patient') {
                        $otherUser = $conversation->doctor;
                        if($otherUser) {
                            $otherUserType = 'doctor';
                            if($otherUser->image && $otherUser->image->filename) {
                                $imagePath = URL::asset('Dashboard/img/doctors/' . $otherUser->image->filename);
                            }
                            $defaultImage = URL::asset('Dashboard/img/faces/doctor_default.png');
                        }
                    } elseif ($this->auth_user_guard === 'doctor') {
                        $otherUser = $conversation->patient;
                        if($otherUser) {
                            $otherUserType = 'patient';
                            if($otherUser->image && $otherUser->image->filename) {
                                $imagePath = URL::asset('Dashboard/img/patients/' . $otherUser->image->filename);
                            }
                             $defaultImage = URL::asset('Dashboard/img/default_patient_avatar.png');
                        }
                    }

                     if (!$otherUser) {
                         Log::warning("[Chatlist Blade] otherUser not loaded via relation for ConvID {$conversation->id}. Using email fallback.");
                         if ($conversation->sender_email == $this->auth_email) {
                             $otherUser = \App\Models\Doctor::where('email', $conversation->receiver_email)->first() ?? \App\Models\Patient::where('email', $conversation->receiver_email)->first();
                         } else {
                             $otherUser = \App\Models\Doctor::where('email', $conversation->sender_email)->first() ?? \App\Models\Patient::where('email', $conversation->sender_email)->first();
                         }
                     }
                ?>

                <?php if($otherUser): ?>
                    <div class="conversation-item list-group-item <?php echo e($selected_conversation && $selected_conversation->id == $conversation->id ? 'active-conversation' : ''); ?> <?php echo e($conversation->unread_messages_for_user_count > 0 ? 'unread-conversation' : ''); ?>"
                         wire:click="chatUserSelected(<?php echo e($conversation->id); ?>)"
                         id="conversation-<?php echo e($conversation->id); ?>"
                         role="button" tabindex="0"
                         aria-current="<?php echo e($selected_conversation && $selected_conversation->id == $conversation->id ? 'true' : 'false'); ?>"
                         title="<?php echo e(trans('doctors.chat_list_conversation_with', ['name' => $otherUser->name ?? $otherUser->email])); ?>">

                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 position-relative">
                                <img src="<?php echo e($imagePath ?? $defaultImage); ?>"
                                     class="user-avatar rounded-circle" alt="<?php echo e(trans('doctors.chat_list_user_image_alt', ['name' => $otherUser->name ?? ''])); ?>">
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="user-name fw-bold text-dark text-truncate" style="max-width: 150px;"><?php echo e($otherUser->name ?? $otherUser->email); ?></span>
                                    <?php if($conversation->lastMessage): ?>
                                    <small class="message-time text-muted">
                                        <?php echo e($conversation->lastMessage->created_at->locale(app()->getLocale())->shortAbsoluteDiffForHumans()); ?>

                                    </small>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <?php if($conversation->lastMessage): ?>
                                        <p class="message-preview text-muted mb-0 text-truncate" style="max-width: 180px;">
                                            <?php if($conversation->lastMessage->sender_email == $this->auth_email): ?>
                                                <i class="fas fa-reply me-1" title="<?php echo e(trans('doctors.chat_list_you_prefix_tooltip')); ?>"></i>
                                            <?php endif; ?>
                                            <?php echo e($conversation->lastMessage->body); ?>

                                        </p>
                                    <?php else: ?>
                                         <p class="message-preview text-muted mb-0 fst-italic"><?php echo e(trans('doctors.chat_list_start_conversation_placeholder')); ?></p>
                                    <?php endif; ?>

                                    <?php if($conversation->unread_messages_for_user_count > 0): ?>
                                        <span class="badge bg-danger rounded-pill unread-count ms-2" title="<?php echo e(trans('doctors.chat_list_unread_messages_tooltip', ['count' => $conversation->unread_messages_for_user_count])); ?>">
                                            <?php echo e($conversation->unread_messages_for_user_count); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                     <div class="list-group-item text-danger small p-2">
                         <?php echo e(trans('doctors.chat_list_load_error', ['id' => $conversation->id])); ?>

                     </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
             <div class="text-center text-muted p-5">
                 <i class="far fa-comments fa-3x mb-3"></i>
                 <p><?php echo e(trans('doctors.chat_list_no_conversations_yet')); ?></p>
                 <p class="small"><?php echo e(trans('doctors.chat_list_start_new_conversation_prompt')); ?></p>
             </div>
        <?php endif; ?>
        <div wire:loading wire:target="loadConversations, searchTerm" class="text-center p-3 text-muted">
            <i class="fas fa-spinner fa-spin me-2"></i><?php echo e(trans('doctors.chat_list_loading_conversations')); ?>

        </div>
    </div>
</div>

<?php $__env->startSection('css'); ?>
<style>
    .main-chat-list { background-color: #fff; }
    .conversation-item {
        padding: 0.85rem 1rem;
        border: none;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out, border-left-color 0.2s ease-in-out;
        position: relative;
    }
    .conversation-item:last-child { border-bottom: none; }
    .conversation-item:hover { background-color: #f8f9fa; }
    .conversation-item.active-conversation {
        background-color: #eef2ff; /* لون أزرق فاتح للتحديد */
        border-left: 4px solid #4f46e5; /* شريط جانبي للتمييز */
    }
    .user-avatar { width: 48px; height: 48px; object-fit: cover; }
    .user-name { font-size: 0.9rem; }
    .message-time { font-size: 0.75rem; white-space: nowrap; }
    .message-preview { font-size: 0.8rem; color: #6c757d !important; }
    .conversation-item.unread-conversation .user-name,
    .conversation-item.unread-conversation .message-preview {
        font-weight: 600 !important; /* جعل النص أغمق */
        color: #212529 !important;
    }
    .unread-count {
        font-size: 0.7rem;
        padding: 0.25em 0.5em;
        line-height: 1;
    }
    .user-status-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 2px solid white;
    }
    .user-status-dot.online { background-color: #28a745; }
    .user-status-dot.offline { background-color: #6c757d; }

    /* شريط التمرير */
    #ChatListContainer::-webkit-scrollbar { width: 5px; }
    #ChatListContainer::-webkit-scrollbar-track { background: #f1f1f1; }
    #ChatListContainer::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px;}
    #ChatListContainer::-webkit-scrollbar-thumb:hover { background: #aaa; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatListContainer = document.getElementById('ChatListContainer');

        function applyActiveClassAndScroll(selectedId) {
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('active-conversation');
                item.setAttribute('aria-current', 'false');
            });
            if (selectedId) {
                const selectedElement = document.getElementById('conversation-' + selectedId);
                if (selectedElement) {
                    selectedElement.classList.add('active-conversation');
                    selectedElement.setAttribute('aria-current', 'true');
                    // التمرير لرؤية العنصر النشط إذا لم يكن مرئيًا
                    // selectedElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        }

        // عند تحميل الصفحة لأول مرة
        const initialSelectedId = <?php echo json_encode($selected_conversation ? $selected_conversation->id : null, 15, 512) ?>;
        if (initialSelectedId) {
            applyActiveClassAndScroll(initialSelectedId);
        }

        // عند اختيار محادثة
        window.addEventListener('conversation-selected', event => {
            if(event.detail && event.detail.id) {
                applyActiveClassAndScroll(event.detail.id);
            }
        });

        // بعد تحديث Livewire (مثل فلترة البحث أو وصول رسالة جديدة)
        Livewire.hook('message.processed', (message, component) => {
            if (component.fingerprint.name === 'chat.chatlist') { // تأكد أنه مكون الـ chatlist
                const currentSelectedId = window.livewire.find('<?php echo e($_instance->id); ?>').get('selected_conversation') ? window.livewire.find('<?php echo e($_instance->id); ?>').get('selected_conversation').id : null;
                applyActiveClassAndScroll(currentSelectedId);
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php /**PATH H:\Hospital_Sys\resources\views/livewire/chat/chatlist.blade.php ENDPATH**/ ?>