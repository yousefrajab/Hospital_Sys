@foreach($groups as $group)
<!-- Delete Group Modal - Styled Version -->
<div class="modal fade" id="deleteGroup{{$group->id}}" tabindex="-1" aria-labelledby="deleteGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteGroupModalLabel">تأكيد حذف مجموعة الخدمات</h5>
                        <p class="mb-0 small">سيتم حذف جميع البيانات المرتبطة</p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <div class="delete-icon-container">
                        <i class="fas fa-layer-group text-danger"></i>
                    </div>
                    <h4 class="text-danger font-weight-bold mt-3">تحذير!</h4>
                    <p class="text-muted">أنت على وشك حذف المجموعة التالية:</p>
                </div>

                <div class="alert alert-light border-danger border text-center py-3">
                    <h5 class="font-weight-bold mb-1">{{ $group->name }}</h5>
                    <p class="text-muted small mb-0">رقم المجموعة: #{{ $group->id }}</p>
                </div>

                <div class="custom-control custom-checkbox my-4 text-center">
                    <input type="checkbox" class="custom-control-input" id="confirmGroupCheck{{ $group->id }}" required>
                    <label class="custom-control-label text-danger" for="confirmGroupCheck{{ $group->id }}">
                        أنا أدرك أن هذا الإجراء لا يمكن التراجع عنه
                    </label>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-2"></i> إلغاء
                    </button>
                    <button type="button"
                            wire:click="delete({{ $group->id }})"
                            class="btn btn-danger bg-gradient-danger"
                            onclick="document.getElementById('confirmGroupCheck{{ $group->id }}').checked ? '' : event.preventDefault()">
                        <i class="fas fa-trash-alt mr-2"></i> تأكيد الحذف
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    /* نفس ستايل المودال الأول */
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

<script>
    document.addEventListener('livewire:load', function() {
        // إضافة تأثير عند النقر على زر الحذف
        document.querySelectorAll('[wire\\:click^="delete"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const groupId = this.getAttribute('wire:click').match(/\d+/)[0];
                const checkBox = document.getElementById('confirmGroupCheck' + groupId);

                if (checkBox && !checkBox.checked) {
                    return;
                }

                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> جاري الحذف...';
                this.disabled = true;
            });
        });
    });
</script>
