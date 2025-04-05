<div wire:ignore>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users mr-2"></i>قائمة الدكاترة</h5>
                    <div class="search-box" style="width: 300px;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control border-0"
                                   placeholder="ابحث عن دكتور..." id="searchInput">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped" id="doctorsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">اسم الدكتور</th>
                                    <th class="text-center">التخصص</th>
                                    <th class="text-center">حالة الحساب</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
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
                                                    <!-- زر المحادثة مع تأثيرات -->
                                                    <button wire:click="createConversation('{{ $user->email }}')"
                                                        class="btn btn-link text-dark font-weight-bold p-0 chat-btn"
                                                        data-doctor-id="{{ $user->id }}"
                                                        title="محادثة مع {{ $user->name }}">
                                                        {{ $user->name }}
                                                        <i class="fas fa-comment-dots text-primary ml-2"></i>
                                                    </button>
                                                    <span class="d-block text-muted small">{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span
                                                class="badge badge-info p-2">{{ $user->section->name ?? 'عام' }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span
                                                class="badge {{ $user->status ? 'badge-success' : 'badge-danger' }} p-2">
                                                {{ $user->status ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- JavaScript -->
<script>
    $(document).ready(function() {
        // تفعيل أدوات التلميحات
        $('[data-toggle="tooltip"]').tooltip();

        // وظيفة البحث
        $("#searchInput").on("keyup", function() {
            const value = $(this).val().toLowerCase();
            $("#doctorsTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // تفعيل DataTable
        $('#doctorsTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
            },
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            pageLength: 10
        });

        // عند النقر على زر المحادثة
        $('.chat-btn').click(function() {
            const doctorId = $(this).data('doctor-id');
            const doctorName = $(this).data('doctor-name');

            // تعبئة بيانات الطبيب في النافذة
            $('#doctorName').text(doctorName);

            // هنا يمكنك جلب رسائل المحادثة السابقة عبر AJAX
            // loadChatMessages(doctorId);

            // عرض النافذة
            $('#chatModal').modal('show');
        });

        // إرسال رسالة جديدة
        $('#sendMessageBtn').click(function() {
            const message = $('#messageInput').val().trim();
            if (message) {
                // هنا يمكنك إرسال الرسالة عبر AJAX
                // sendMessage(doctorId, message);

                // إضافة الرسالة إلى العرض (مؤقتاً)
                $('#chatMessages').append(`
                <div class="message sent mb-2">
                    <div class="message-content bg-light p-2 rounded">
                        ${message}
                        <div class="message-time small text-muted text-left">الآن</div>
                    </div>
                </div>
            `);

                // مسح حقل الإدخال
                $('#messageInput').val('');

                // التمرير إلى الأسفل
                $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
            }
        });
    });

    // دالة لجلب الرسائل (مثال)
    function loadChatMessages(doctorId) {
        $.ajax({
            url: '/get-messages/' + doctorId,
            method: 'GET',
            success: function(response) {
                $('#chatMessages').html('');
                response.messages.forEach(function(message) {
                    // إضافة الرسائل إلى العرض
                });
            }
        });
    }

    // دالة لإرسال الرسائل (مثال)
    function sendMessage(doctorId, message) {
        $.ajax({
            url: '/send-message',
            method: 'POST',
            data: {
                doctor_id: doctorId,
                message: message,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // معالجة الاستجابة
            }
        });
    }
</script>

<style>
    .chat-btn {
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none !important;
    }

    .chat-btn:hover {
        color: #007bff !important;
        transform: translateX(3px);
    }

    .chat-container {
        height: 300px;
        display: flex;
        flex-direction: column;
    }

    .chat-messages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .message.received {
        text-align: right;
    }

    .message.sent .message-content {
        background-color: #e3f2fd;
    }

    .message-time {
        font-size: 0.75rem;
    }

    /* تأثيرات إضافية */
    .chat-btn:active {
        transform: scale(0.95);
    }

    #sendMessageBtn {
        transition: all 0.2s;
    }

    #sendMessageBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>
