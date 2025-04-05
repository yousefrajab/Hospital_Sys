<div class="message-input-container">
    @if($selected_conversation)
    <form wire:submit.prevent="sendMessage" class="message-form">
        <div class="input-group">
            <input
                class="form-control message-input"
                wire:model="body"
                placeholder="اكتب رسالتك هنا..."
                type="text"
                aria-label="رسالة جديدة"
            >
            <div class="input-group-append">
                <button
                    class="btn btn-send"
                    type="submit"
                    title="إرسال"
                    :disabled="!$body"
                >
                    <i class="fas fa-paper-plane send-icon"></i>
                </button>
            </div>
        </div>
        <div class="typing-indicator" wire:loading>
            <i class="fas fa-circle-notch fa-spin"></i> جاري الإرسال...
        </div>
    </form>
    @endif
</div>

<style>
    .message-input-container {
        padding: 1rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }

    .message-input {
        border-radius: 25px !important;
        border: 1px solid #ced4da;
        padding: 0.75rem 1.25rem;
        transition: all 0.3s ease;
    }

    .message-input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-send {
        background: linear-gradient(135deg, #6c5ce7, #a29bfe);
        border: none;
        border-radius: 50% !important;
        width: 45px;
        height: 45px;
        margin-left: 0.5rem;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-send:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 92, 231, 0.3);
    }

    .btn-send:disabled {
        background: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
    }

    .send-icon {
        font-size: 1.1rem;
    }

    .typing-indicator {
        color: #6c757d;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        text-align: center;
    }
</style>

<script>
    document.addEventListener('livewire:load', function() {
        // التركيز التلقائي على حقل الإدخال
        Livewire.hook('message.processed', () => {
            const input = document.querySelector('.message-input');
            if (input) input.focus();
        });

        // إرسال بالضغط على Enter (بدون Shift)
        document.querySelector('.message-input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                Livewire.emit('sendMessage');
            }
        });
    });
</script>
