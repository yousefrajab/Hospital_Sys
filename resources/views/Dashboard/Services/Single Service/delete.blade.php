@section('css')
    @include('Style.Style')
@endsection

@foreach($services as $service)
<!-- Delete Patient Modal - Simplified Version -->
<div class="modal fade" id="delete{{ $service->id }}" tabindex="-1" aria-labelledby="deletePatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deletePatientModalLabel">تأكيد حذف خدمة</h5>
                        <p class="mb-0 small">سيتم حذف جميع البيانات المرتبطة</p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <form action="{{ route('admin.Service.destroy', $service->id) }}" method="post" id="deleteForm{{ $service->id }}">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="id" value="{{ $service->id }}">

                    <div class="text-center mb-4">
                        <div class="delete-icon-container">
                            <i class="fas fa-user-slash text-danger"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3">تحذير!</h4>
                        <p class="text-muted">أنت على وشك حذف السجل التالي:</p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3">
                        <h5 class="font-weight-bold mb-1">{{ $service->name }}</h5>
                        <p class="text-muted small mb-0">رقم الخدمة: #{{ $service->id }}</p>
                    </div>

                    <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmCheck{{ $service->id }}" required>
                        <label class="custom-control-label text-danger" for="confirmCheck{{ $service->id }}">
                            أنا أدرك أن هذا الإجراء لا يمكن التراجع عنه
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> إلغاء
                        </button>
                        <button type="submit" id="deleteBtn{{ $service->id }}"
                                class="btn btn-danger bg-gradient-danger">
                            <i class="fas fa-trash-alt mr-2"></i> تأكيد الحذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@section('js')
    <!-- 5. ملفات JS المخصصة (إذا وجدت) -->
    @include('Script.Script')
@endsection
