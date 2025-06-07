{{-- resources/views/livewire/chat/chatlist.blade.php --}}
<div>
    {{-- (اختياري) شريط بحث داخل قائمة المحادثات --}}
    {{-- <div class="p-3 border-bottom">
        <input type="text" wire:model.debounce.300ms="searchTerm" class="form-control form-control-sm rounded-pill" placeholder="بحث في المحادثات...">
    </div> --}}

    <div class="main-chat-list ps" id="ChatListContainer" style="position: relative; overflow-y: auto; height: calc(100% - 130px);"> {{-- تعديل الارتفاع ليناسب البحث والهيدر --}}
        @if($conversations && $conversations->count() > 0)
            @foreach ($conversations as $conversation)
                @php
                    $otherUser = null;
                    $otherUserType = null;
                    $imagePath = null;
                    $defaultImage = URL::asset('Dashboard/img/faces/user_default.png'); // صورة افتراضية عامة

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

                    // Fallback إذا لم يتم تحميل otherUser من العلاقة (يجب تجنبه)
                     if (!$otherUser) {
                         Log::warning("[Chatlist Blade] otherUser not loaded via relation for ConvID {$conversation->id}. Using email fallback.");
                         if ($conversation->sender_email == $this->auth_email) {
                             $otherUser = \App\Models\Doctor::where('email', $conversation->receiver_email)->first() ?? \App\Models\Patient::where('email', $conversation->receiver_email)->first();
                         } else {
                             $otherUser = \App\Models\Doctor::where('email', $conversation->sender_email)->first() ?? \App\Models\Patient::where('email', $conversation->sender_email)->first();
                         }
                         // ... (تحديد imagePath و defaultImage للـ fallback)
                     }
                @endphp

                @if ($otherUser)
                    <div class="conversation-item list-group-item {{ $selected_conversation && $selected_conversation->id == $conversation->id ? 'active-conversation' : '' }} {{ $conversation->unread_messages_for_user_count > 0 ? 'unread-conversation' : '' }}"
                         wire:click="chatUserSelected({{ $conversation->id }})"
                         id="conversation-{{ $conversation->id }}"
                         role="button" tabindex="0"
                         aria-current="{{ $selected_conversation && $selected_conversation->id == $conversation->id ? 'true' : 'false' }}"
                         title="محادثة مع {{ $otherUser->name ?? $otherUser->email }}">

                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 position-relative">
                                <img src="{{ $imagePath ?? $defaultImage }}"
                                     class="user-avatar rounded-circle" alt="صورة {{ $otherUser->name ?? '' }}">
                                {{-- (اختياري) نقطة حالة الاتصال --}}
                                {{-- <span class="user-status-dot {{ $otherUser->is_online ? 'online' : 'offline' }}"></span> --}}
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="user-name fw-bold text-dark text-truncate" style="max-width: 150px;">{{ $otherUser->name ?? $otherUser->email }}</span>
                                    @if($conversation->lastMessage)
                                    <small class="message-time text-muted">
                                        {{ $conversation->lastMessage->created_at->locale('ar')->shortAbsoluteDiffForHumans() }}
                                    </small>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($conversation->lastMessage)
                                        <p class="message-preview text-muted mb-0 text-truncate" style="max-width: 180px;">
                                            @if($conversation->lastMessage->sender_email == $this->auth_email)
                                                <i class="fas fa-reply me-1" title="أنت:"></i>
                                            @endif
                                            {{ $conversation->lastMessage->body }}
                                        </p>
                                    @else
                                         <p class="message-preview text-muted mb-0 fst-italic">ابدأ المحادثة...</p>
                                    @endif

                                    @if($conversation->unread_messages_for_user_count > 0)
                                        <span class="badge bg-danger rounded-pill unread-count ms-2" title="{{ $conversation->unread_messages_for_user_count }} رسائل غير مقروءة">
                                            {{ $conversation->unread_messages_for_user_count }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                     <div class="list-group-item text-danger small p-2">
                         خطأ في تحميل محادثة (ID: {{ $conversation->id }}).
                     </div>
                @endif
            @endforeach
        @else
             <div class="text-center text-muted p-5">
                 <i class="far fa-comments fa-3x mb-3"></i>
                 <p>لا توجد محادثات بعد.</p>
                 <p class="small">ابدأ محادثة جديدة من القائمة.</p>
             </div>
        @endif
        <div wire:loading wire:target="loadConversations, searchTerm" class="text-center p-3 text-muted">
            <i class="fas fa-spinner fa-spin me-2"></i>جاري تحميل المحادثات...
        </div>
    </div>
</div>

@section('css')
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
@endsection

@section('js')
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
        const initialSelectedId = @json($selected_conversation ? $selected_conversation->id : null);
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
                const currentSelectedId = @this.get('selected_conversation') ? @this.get('selected_conversation').id : null;
                applyActiveClassAndScroll(currentSelectedId);
            }
        });
    });
</script>
@endsection
