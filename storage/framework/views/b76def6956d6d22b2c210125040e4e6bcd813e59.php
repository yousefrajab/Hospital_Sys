<div class="modal fade" id="deleteRoomConfirmModal<?php echo e($room->id); ?>" tabindex="-1"
    aria-labelledby="deleteRoomModalLabel<?php echo e($room->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white"
                style="background: linear-gradient(135deg, var(--admin-danger, #ef4444), var(--admin-danger-dark, #c82333)); border-bottom: none;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i> 
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteRoomModalLabel<?php echo e($room->id); ?>">
                            تأكيد حذف الغرفة
                        </h5>
                        <p class="mb-0 small">يرجى التأكد قبل المتابعة.</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button> 
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                
                <form action="<?php echo e(route('admin.rooms.destroy', $room->id)); ?>" method="post"
                    id="confirmDeleteRoomForm<?php echo e($room->id); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    

                    <div class="text-center mb-4">
                        <div style="font-size: 3rem; color: var(--admin-danger, #ef4444); margin-bottom: 1rem;">
                            <i class="fas fa-door-closed"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3">تحذير!</h4>
                        <p class="text-muted">أنت على وشك حذف الغرفة بشكل نهائي.</p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3"
                        style="background-color: rgba(239,68,68,0.05);">
                        <h5 class="font-weight-bold mb-1">الغرفة: <?php echo e($room->room_number); ?></h5>
                        <?php if($room->section): ?>
                            <p class="text-muted small mb-0">القسم: <?php echo e($room->section->name); ?></p>
                        <?php endif; ?>
                        <p class="text-muted small mb-0">معرف الغرفة: #<?php echo e($room->id); ?></p>
                    </div>

                    <p class="text-center text-muted small my-3">
                        هذا الإجراء لا يمكن التراجع عنه. سيتم أيضًا حذف أي أسرة مرتبطة بهذه الغرفة إذا كانت فارغة
                    </p>

                    
                    

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> <?php echo e(trans('sections_trans.Close')); ?></button>
                        <button type="submit" id="confirmDeleteBtnRoom<?php echo e($room->id); ?>" class="btn btn-delete-form"
                            style="min-width: 120px;">
                            <i class="fas fa-trash-alt me-2"></i> نعم، قم بالحذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Rooms/delete.blade.php ENDPATH**/ ?>