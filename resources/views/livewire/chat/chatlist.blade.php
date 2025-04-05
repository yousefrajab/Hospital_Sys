<div>
    <div class="main-chat-list" id="ChatList">
        @foreach ($conversations as $conversation)
            @php
                $user = $this->getUsers($conversation, 'name') ? $this->receviverUser : null;
            @endphp
            @if ($user)
                <div class="conversation-card"
                    wire:click="chatUserSelected({{ $conversation }},'{{ $this->getUsers($conversation, $name = 'id') }}')">
                    <div class="conversation-content">
                        <div class="conversation-header">
                            <div class="user-info">
                                @if ($user && $user instanceof \App\Models\Doctor && $user->image)
                                    <img src="{{ asset('Dashboard/img/doctors/' . $user->image->filename) }}"
                                        class="user-avatar" alt="صورة الطبيب">
                                @else
                                    <img src="{{ asset('Dashboard/img/faces/doctor_default.png') }}"
                                        class="user-avatar" alt="الصورة الافتراضية">
                                @endif
                                <span class="user-name">{{ $this->getUsers($conversation, $name = 'name') }}</span>
                            </div>
                            <span class="message-time">{{ $conversation->messages->last()->created_at->shortAbsoluteDiffForHumans() }}</span>
                        </div>
                        <p class="message-preview">{{ $conversation->messages->last()->body }}</p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<style>
    .main-chat-list {
        padding: 10px;
    }

    .conversation-card {
        padding: 12px 15px;
        margin-bottom: 8px;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #f0f0f0;
    }

    .conversation-card:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-left: 10px;
    }

    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    /* .user-info {
        display: flex;
        align-items: center;
    } */

    .user-name {
        font-weight: 600;
        color: #2d3748;
        font-size: 15px;
    }

    .message-time {
        font-size: 12px;
        color: #718096;
        background: #edf2f7;
        padding: 3px 8px;
        border-radius: 10px;
    }

    .message-preview {
        color: #4a5568;
        font-size: 13px;
        margin: 5px 0 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 55px;
    }
</style>
