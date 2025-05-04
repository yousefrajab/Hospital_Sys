<!-- Delete Radiology Employee Modal -->
<div class="modal fade" id="delete{{ $ray_employee->id }}" tabindex="-1" aria-labelledby="deleteRayEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteRayEmployeeModalLabel">تأكيد حذف الموظف</h5>
                        <p class="mb-0 small">سيتم حذف جميع البيانات المرتبطة</p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <form action="{{ route('admin.ray_employee.destroy', $ray_employee->id) }}" method="post" id="deleteRayForm{{ $ray_employee->id }}">
                    @method('DELETE')
                    @csrf

                    <div class="text-center mb-4">
                        <div class="delete-icon-container">
                            <i class="fas fa-user-slash text-danger"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3">تحذير!</h4>
                        <p class="text-muted">أنت على وشك حذف السجل التالي:</p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3">
                        <h5 class="font-weight-bold mb-1">{{ $ray_employee->name }}</h5>
                        <p class="text-muted small mb-0">رقم الموظف: #{{ $ray_employee->id }}</p>
                    </div>

                    <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmRayCheck{{ $ray_employee->id }}" required>
                        <label class="custom-control-label text-danger" for="confirmRayCheck{{ $ray_employee->id }}">
                            أنا أدرك أن هذا الإجراء لا يمكن التراجع عنه
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> إلغاء
                        </button>
                        <button type="submit" id="deleteRayBtn{{ $ray_employee->id }}" class="btn btn-danger bg-gradient-danger">
                            <i class="fas fa-trash-alt mr-2"></i> تأكيد الحذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Shared Styles -->
<style>
    .delete-icon-container {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background-color: rgba(220, 53, 69, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .delete-icon-container i {
        font-size: 2.5rem;
        color: #dc3545;
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }

    .bg-gradient-light {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    .custom-checkbox .custom-control-label::before {
        border-radius: 0.25rem;
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #dc3545;
        background-color: #dc3545;
    }
</style>

<!-- Deletion Button Animation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteRayForm = document.getElementById('deleteRayForm{{ $ray_employee->id }}');
        if (deleteRayForm) {
            deleteRayForm.addEventListener('submit', function(e) {
                const deleteBtn = document.getElementById('deleteRayBtn{{ $ray_employee->id }}');
                if (deleteBtn) {
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> جاري الحذف...';
                    deleteBtn.disabled = true;
                }
            });
        }
    });
</script>
