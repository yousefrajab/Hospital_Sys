<div>
    <div class="container-fluid users-list-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="fas fa-user-friends me-2"></i><?php echo e(trans('doctors.chat_select_patient_title')); ?></h4>
            <input type="text" wire:model.debounce.300ms="searchPatients" class="form-control form-control-sm w-25" placeholder="<?php echo e(trans('doctors.chat_search_patient_placeholder')); ?>">
        </div>

        <?php if($users && $users->count() > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                    <div class="col">
                        <div class="card user-card-item h-100 shadow-hover" wire:click="createConversation('<?php echo e($patient->email); ?>')" title="<?php echo e(trans('doctors.chat_start_conversation_with', ['name' => $patient->name])); ?>">
                            <div class="card-body text-center">
                                <img src="<?php echo e($patient->image ? asset('Dashboard/img/patients/' . $patient->image->filename) : asset('Dashboard/img/default_patient_avatar.png')); ?>"
                                     alt="<?php echo e(trans('doctors.chat_patient_image_alt', ['name' => $patient->name])); ?>" class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover; border: 3px solid #e9ecef;">
                                <h6 class="card-title mb-1 fw-bold"><?php echo e($patient->name); ?></h6>
                                <p class="card-text text-muted small mb-2"><?php echo e($patient->email); ?></p>
                                
                                <button class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-comments me-1"></i> <?php echo e(trans('doctors.chat_start_conversation_button')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            
        <?php else: ?>
            <div class="text-center text-muted p-5">
                <i class="fas fa-users-slash fa-3x mb-3"></i>
                <p><?php echo e(trans('doctors.chat_no_patients_found')); ?></p>
            </div>
        <?php endif; ?>
        <div wire:loading wire:target="searchPatients" class="text-center p-3 text-muted">
            <i class="fas fa-spinner fa-spin me-2"></i><?php echo e(trans('doctors.chat_searching')); ?>

        </div>
    </div>
</div>

<?php $__env->startPush('css'); ?>
<style>
.shadow-hover:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.1)!important; transform: translateY(-2px); }
.users-list-container .card-title { font-size: 1rem; }
</style>
<?php $__env->stopPush(); ?>


<?php /**PATH H:\Hospital_Sys\resources\views/livewire/chat/chat-window.blade.php ENDPATH**/ ?>