<div wire:ignore>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users mr-2"></i>قائمة المرضى</h5>
                    <div class="search-box" style="width: 300px;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control border-0"
                                   placeholder="ابحث عن مريض..." id="searchInput">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="patientsTable">
                            <thead class="bg-light-primary">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th>معلومات المريض</th>
                                    <th width="120" class="text-center">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr class="patient-row">
                                    <td class="text-center align-middle text-muted">{{ $loop->iteration }}</td>
                                    <td>
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
                                                <h6 class="mb-1 text-dark">{{ $user->name }}</h6>
                                                <span class="text-muted d-block">
                                                    <i class="fas fa-envelope mr-1"></i>{{ $user->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button wire:click="createConversation('{{ $user->email }}')"
                                            class="btn btn-sm btn-icon btn-primary rounded-circle"
                                            title="بدء محادثة">
                                            <i class="fas fa-comment-dots"></i>
                                        </button>
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

<script>
    $(document).ready(function() {
        // تكوين DataTable
        $('#patientsTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
            },
            dom: '<"top"f>rt<"bottom"ip><"clear">',
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [2] }
            ],
            initComplete: function() {
                $('.dataTables_filter input').attr('placeholder', 'ابحث باسم المريض أو الإيميل...');
            }
        });

        // البحث الفوري
        $("#searchInput").on("keyup", function() {
            $('#patientsTable').DataTable().search($(this).val()).draw();
        });
    });
</script>

<style>
    .patient-row:hover {
        background-color: #f8f9fa;
        transform: translateX(2px);
        transition: all 0.2s ease;
    }

    .symbol {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .symbol-50 {
        width: 50px;
        height: 50px;
    }

    .symbol-light-primary {
        background-color: #e1f0ff;
    }

    .symbol-label {
        color: #3699ff;
        font-weight: bold;
    }

    .search-box .input-group-text {
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
    }

    .search-box input {
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .bg-light-primary {
        background-color: #f3f6f9;
    }
</style>
