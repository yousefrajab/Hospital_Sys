<div>
    <div class="main-content-left main-content-left-chat">
        {{-- (اختياري) شريط البحث --}}
        {{-- <div class="input-group p-3 border-bottom"> ... </div> --}}

        {{-- قائمة المحادثات الفعلية --}}
        <div class="main-chat-list ps ps--active-y" id="ChatList" style="position: relative; overflow: auto; height: calc(100vh - 200px);">

            {{-- التحقق من أن $conversations ليست null قبل العد --}}
            @if($conversations && $conversations->count() > 0)
                @foreach ($conversations as $conversation)
                    @php
                        // --- >> تحديد المستخدم الآخر باستخدام العلاقات المحملة << ---
                        $otherUser = null;
                        $otherUserType = null;

                        // نفترض أن المستخدم الحالي تم تحديده في $this->auth_user_guard و $this->auth_email
                        if ($this->auth_user_guard === 'patient') {
                            // إذا أنا مريض، الطرف الآخر هو الطبيب (المستقبل أو المرسل حسب هيكل جدولك)
                            // الخيار الأوضح هو استخدام العلاقات المباشرة إذا كانت محملة
                            $otherUser = $conversation->doctor; // افترضنا أن علاقة doctor محملة
                            if($otherUser) $otherUserType = 'doctor';

                        } elseif ($this->auth_user_guard === 'doctor') {
                            // إذا أنا طبيب، الطرف الآخر هو المريض
                            $otherUser = $conversation->patient; // افترضنا أن علاقة patient محملة
                            if($otherUser) $otherUserType = 'patient';
                        }

                        // Fallback (في حالة عدم تحميل العلاقات بشكل صحيح أو الاعتماد على الإيميل)
                        // يجب تجنب هذا قدر الإمكان
                        if (!$otherUser) {
                             Log::warning("Could not determine other user via direct relations for conversation ID {$conversation->id}. Falling back to email lookup (inefficient).");
                             if ($conversation->sender_email == $this->auth_email) {
                                 $otherUser = \App\Models\Doctor::where('email', $conversation->receiver_email)->first() ?? \App\Models\Patient::where('email', $conversation->receiver_email)->first();
                             } else {
                                 $otherUser = \App\Models\Doctor::where('email', $conversation->sender_email)->first() ?? \App\Models\Patient::where('email', $conversation->sender_email)->first();
                             }
                             if ($otherUser instanceof \App\Models\Doctor) $otherUserType = 'doctor';
                             elseif ($otherUser instanceof \App\Models\Patient) $otherUserType = 'patient';
                        }

                    @endphp

                    {{-- عرض عنصر المحادثة فقط إذا تم تحديد الطرف الآخر بنجاح --}}
                    @if ($otherUser)
                        <div class="list-group-item conversation-item {{ $selected_conversation && $selected_conversation->id == $conversation->id ? 'active-conversation' : '' }}"
                             wire:click="chatUserSelected({{ $conversation->id }})" {{-- تمرير ID المحادثة فقط --}}
                             id="conversation-{{ $conversation->id }}"
                             role="button" tabindex="0"
                             aria-current="{{ $selected_conversation && $selected_conversation->id == $conversation->id ? 'true' : 'false' }}"
                             title="محادثة مع {{ $otherUser->name ?? $otherUser->email }}">

                            <div class="d-flex align-items-center">
                                {{-- الصورة الرمزية --}}
                                <div class="flex-shrink-0 me-3">
                                    @php

                                        $imagePath = null;
                                        $defaultImage = URL::asset('Dashboard/img/faces/user_default.png'); // صورة افتراضية عامة
                                        if($otherUser->image && $otherUser->image->filename) {
                                            if($otherUserType === 'doctor') {
                                                $imagePath = URL::asset('Dashboard/img/doctors/' . $otherUser->image->filename);



                                                $defaultImage = URL::asset('Dashboard/img/faces/user_default.png');

                                            } elseif($otherUserType === 'patient') {
                                                $imagePath = URL::asset('Dashboard/img/patients/'.$otherUser->image->filename);
                                                $defaultImage = URL::asset('Dashboard/img/faces/user_default.png');
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $imagePath ?? $defaultImage }}" width="50" height="50" style="margin: 10px"
                                         class="user-avatar rounded-circle" alt="صورة {{ $otherUser->name ?? '' }}">
                                </div>

                                {{-- تفاصيل المحادثة --}}
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        {{-- اسم المستخدم الآخر --}}
                                        <span class="user-name fw-bold text-dark">{{ $otherUser->name ?? $otherUser->email }}</span>
                                        {{-- وقت آخر رسالة --}}
                                        @if($conversation->lastMessage)
                                        <small class="message-time text-muted">
                                            {{ $conversation->lastMessage->created_at->locale('ar')->shortAbsoluteDiffForHumans() }}
                                        </small>
                                        @endif
                                    </div>
                                    {{-- معاينة آخر رسالة --}}
                                    @if($conversation->lastMessage)
                                        <p class="message-preview text-muted mb-0 text-truncate">
                                            {{ $conversation->lastMessage->body }}
                                        </p>
                                    @else
                                         <p class="message-preview text-muted mb-0 fst-italic">لا توجد رسائل بعد.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                         {{-- رسالة خطأ إذا لم يتم تحديد الطرف الآخر --}}
                         <div class="list-group-item text-danger small">
                             خطأ في تحميل بيانات المحادثة #{{ $conversation->id }}.
                         </div>
                    @endif
                @endforeach
            @else
                 {{-- رسالة في حالة عدم وجود محادثات --}}
                 <div class="text-center text-muted p-5">
                     <i class="far fa-comments fa-3x mb-3"></i>
                     <p>لا توجد محادثات لعرضها.</p>
                     {{-- زر بدء محادثة (اختياري) --}}
                     {{-- ... --}}
                 </div>
            @endif

        </div> {{-- نهاية main-chat-list --}}
    </div> {{-- نهاية main-content-left --}}
</div>

{{-- تضمين الأنماط والـ JavaScript اللازمة (نفس الكود السابق) --}}
@section('css')
<style>
    /* ... (نفس أنماط CSS من الرد السابق لـ .conversation-item و .active-conversation) ... */
    .conversation-item { padding: 0.8rem 1rem; border: none; border-bottom: 1px solid var(--profile-border-color, #e5e7eb); cursor: pointer; transition: background-color 0.2s ease-in-out; background-color: var(--profile-card-bg, #ffffff); }
    .conversation-item:last-child { border-bottom: none; }
    .conversation-item:hover { background-color: #f8f9fa; }
    .conversation-item.active-conversation { background-color: #e9ecef; border-right: 4px solid var(--profile-primary, #4f46e5); padding-right: calc(1rem - 4px); }
    @media (prefers-color-scheme: dark) { .conversation-item { border-bottom-color: var(--profile-border-color, #374151); background-color: var(--profile-card-bg, #1f2937); } .conversation-item:hover { background-color: #374151; } .conversation-item.active-conversation { background-color: #4b5563; border-right-color: var(--profile-primary, #6366f1); } .user-name { color: var(--profile-text-primary, #f3f4f6); } .message-preview { color: var(--profile-text-secondary, #9ca3af); } .message-time { color: var(--profile-text-secondary, #9ca3af); } }
    .user-avatar { width: 5px; height: 45px; object-fit: cover; }
    .user-name { font-size: 0.95rem; }
    .message-time { font-size: 0.75rem; white-space: nowrap; }
    .message-preview { font-size: 0.85rem; }
    .conversation-item:focus { outline: none; background-color: #f0f0f0; }
    @media (prefers-color-scheme: dark) { .conversation-item:focus { background-color: #4b5563; } }
</style>
@endsection

@section('js')
<script>
    // --- نفس كود JavaScript من الرد السابق ---
    document.addEventListener('livewire:load', function () {
        const applyActiveClass = (selectedId) => { /* ... (نفس الكود) ... */
             document.querySelectorAll('.conversation-item').forEach(item => { item.classList.remove('active-conversation'); item.setAttribute('aria-current', 'false'); });
             if (selectedId) { const selectedElement = document.getElementById('conversation-' + selectedId); if (selectedElement) { selectedElement.classList.add('active-conversation'); selectedElement.setAttribute('aria-current', 'true'); } }
         };
        window.addEventListener('conversation-selected', event => { if(event.detail && event.detail.id) { applyActiveClass(event.detail.id); } });
        const initialSelectedId = @json($selected_conversation ? $selected_conversation->id : null);
        applyActiveClass(initialSelectedId);
        Livewire.hook('message.processed', (message, component) => { const currentSelectedId = @this.selected_conversation ? @this.selected_conversation.id : null; applyActiveClass(currentSelectedId); });
    });
</script>
@endsection
