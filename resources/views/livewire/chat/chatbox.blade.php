<div class="chat-box-container">
    @if ($selected_conversation)
        <div class="chat-header">
            <div class="user-info">
                <div class="avatar">
                    <img src="{{ $receviverUser->image ? asset('Dashboard/img/doctors/' . $receviverUser->image->filename) : asset('Dashboard/img/faces/doctor_default.png') }}"
                         alt="صورة المستخدم">
                </div>
                <div class="user-details">
                    <h6>{{ $receviverUser->name }}</h6>
                    <small class="user-status"> </small>
                </div>
            </div>
            <div class="chat-actions">
                <button><i class="fas fa-phone"></i></button>
                <button><i class="fas fa-video"></i></button>
                <button><i class="fas fa-ellipsis-v"></i></button>
            </div>
        </div>


        <div class="messages-container" id="messagesContainer">
            @foreach ($messages as $message)
                <div class="message-wrapper {{ $auth_email == $message->sender_email ? 'sent' : 'received' }}"
                     wire:key="message-{{ $message->id }}">
                    <div class="message-bubble">
                        <div class="message-content">
                            {{ $message->body }}
                        </div>
                        <div class="message-meta">
                            <span class="message-time">
                                {{ $message->created_at->locale('ar')->translatedFormat('h:i A') }}
                            </span>
                            @if($auth_email == $message->sender_email)
                                <span class="message-status">
                                    <i class="fas fa-check-double {{ $message->read_at ? 'text-primary' : 'text-muted' }}"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-chat">
            <i class="far fa-comment-dots"></i>
            <p>اختر محادثة لبدء الدردشة</p>
        </div>
    @endif
</div>

<style>
    .chat-box-container {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #fff;
    }

    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    /* .user-info {
        display: flex;
        align-items: center;
    } */

    .user-info .avatar {
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }

    .user-info .avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-details h6 {
        margin: 0;
        font-size: 1rem;
    }

    .user-status {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .chat-actions button {
        background: none;
        border: none;
        color: #6c757d;
        margin-left: 10px;
        cursor: pointer;
    }

    .messages-container {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        background: #f5f5f5;
    }

    .message {
        margin-bottom: 15px;
        max-width: 70%;
    }

    .message.sent {
        margin-left: auto;
    }

    .message.received {
        margin-right: auto;
    }

    .message-content {
        position: relative;
        padding: 10px 15px;
        border-radius: 18px;
    }

    .message.sent .message-content {
        background: #007bff;
        color: white;
        border-top-right-radius: 0;
    }

    .message.received .message-content {
        background: white;
        border-top-left-radius: 0;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }

    .message-time {
        display: block;
        font-size: 0.7rem;
        margin-top: 5px;
        opacity: 0.8;
    }

    .message.sent .message-time {
        text-align: left;
        color: rgba(255,255,255,0.8);
    }

    .message.received .message-time {
        text-align: right;
        color: #6c757d;
    }

    .empty-chat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #adb5bd;
    }

    .empty-chat i {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .messages-container {
        padding: 15px;
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .message-wrapper {
        max-width: 80%;
        display: flex;
        flex-direction: column;
    }

    .message-wrapper.sent {
        align-self: flex-end;
        align-items: flex-end;
    }

    .message-wrapper.received {
        align-self: flex-start;
        align-items: flex-start;
    }

    .message-bubble {
        padding: 10px 14px;
        border-radius: 18px;
        position: relative;
        word-wrap: break-word;
    }

    .message-wrapper.sent .message-bubble {
        background: #007bff;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-wrapper.received .message-bubble {
        background: #f1f1f1;
        color: #333;
        border-bottom-left-radius: 4px;
    }

    .message-meta {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 4px;
        font-size: 0.75rem;
    }

    .message-wrapper.sent .message-meta {
        justify-content: flex-end;
        color: rgba(255,255,255,0.7);
    }

    .message-wrapper.received .message-meta {
        justify-content: flex-start;
        color: #6c757d;
    }

    .message-status {
        font-size: 0.65rem;
    }
</style>

<script>
    document.addEventListener('livewire:load', function() {
        // التمرير للأسفل عند تحميل الرسائل
        scrollToBottom();

        // التمرير للأسفل عند استقبال رسالة جديدة
        Livewire.hook('message.processed', () => {
            scrollToBottom();
        });

        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
    });
</script>



{{-- <!-- باقي كود الـ blade -->
@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        @if(auth()->check())
            const channelName = @json(auth()->guard('patient')->check() ? 'chat2' : 'chat');
            const userId = @json(auth()->id());

            window.Echo.private(`${channelName}.${userId}`)
                .listen('MassageSent', (e) => {
                    if (e.message_id) {
                        Livewire.emit('pushMessage', e.message_id);
                    }
                });
        @endif

        Livewire.on('pushMessage', () => {
            const container = document.getElementById('messagesContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    });
    </script>
@endpush --}}
