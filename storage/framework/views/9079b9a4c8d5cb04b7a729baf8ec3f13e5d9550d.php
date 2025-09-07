<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<!-- Edit Service Modal - Modern Design -->
<div class="modal fade" id="edit<?php echo e($service->id); ?>" tabindex="-1" role="dialog" aria-labelledby="editServiceModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header glass-header text-white py-4 position-relative">
                <div class="d-flex w-100 align-items-center">
                    <div class="icon-wrapper mr-3">
                        <div class="icon-circle bg-white-20">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" id="editServiceModal">
                            <?php echo e(trans('AdminSb.edit_Service')); ?>

                        </h5>
                        <small class="text-white-80"><?php echo e(trans('AdminSb.update_service_details')); ?></small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="wave-effect"></div>
            </div>

            <form action="<?php echo e(route('admin.Service.update', 'test')); ?>" method="post" class="needs-validation"
                novalidate autocomplete="off">
                <?php echo csrf_field(); ?>
                <?php echo method_field('patch'); ?>
                <input type="hidden" name="id" value="<?php echo e($service->id); ?>">

                <div class="modal-body p-5">
                    <div class="form-group floating-label-group">
                        <input type="text" name="name" id="name" class="form-control floating-input"
                            value="<?php echo e($service->name); ?>" required>
                        <label for="name" class="floating-label">
                            <i class="fas fa-tag mr-2"></i><?php echo e(trans('AdminSb.name')); ?>

                        </label>
                        <div class="invalid-feedback animated fadeIn">
                            <?php echo e(trans('validation.required', ['attribute' => trans('AdminSb.name')])); ?>

                        </div>
                    </div>

                    <div class="form-group floating-label-group mt-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text currency-prepend">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                            </div>
                            <input type="number" name="price" id="price" value="<?php echo e($service->price); ?>"
                                class="form-control floating-input" min="0" step="0.01" required>
                            <label for="price" class="floating-label">
                                <?php echo e(trans('AdminSb.price')); ?>

                            </label>
                        </div>
                        <div class="invalid-feedback animated fadeIn">
                            <?php echo e(trans('validation.min.numeric', ['attribute' => trans('AdminSb.price'), 'min' => 0])); ?>

                        </div>
                    </div>

                    <div class="form-group floating-label-group mt-4">
                        <label for="doctor_id_edit_<?php echo e($service->id); ?>" class="floating-label active">
                            <i class="fas fa-user-md mr-2"></i><?php echo e(trans('AdminSb.Doctor')); ?>

                        </label>
                        <select name="doctor_id" id="doctor_id_edit_<?php echo e($service->id); ?>"
                            class="form-control custom-select floating-input">
                            <option value=""><?php echo e(trans('AdminSb.select_doctor_optional')); ?></option>
                            <?php $allDoctorsCollection = collect($doctors ?? []); ?>
                            <?php $__currentLoopData = $allDoctorsCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($doctor->id); ?>"
                                    <?php echo e($service->doctor_id == $doctor->id ? 'selected' : ''); ?>>
                                    <?php echo e($doctor->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group floating-label-group mt-5">
                        <textarea class="form-control floating-input" name="description" id="description<?php echo e($service->id); ?>" rows="3"
                            placeholder="<?php echo e(trans('AdminSb.description')); ?>" data-maxlength="500" style="min-height: 100px;"><?php echo e($service->description); ?></textarea>
                        <label for="description<?php echo e($service->id); ?>" class="floating-label">
                            <i class="fas fa-align-left mr-2"></i><?php echo e(trans('AdminSb.description')); ?>

                        </label>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">
                                <span id="charCount<?php echo e($service->id); ?>">0</span>/500
                            </small>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="font-weight-bold text-primary d-block"><?php echo e(trans('AdminSb.Status')); ?></label>
                        <div class="custom-control custom-switch custom-switch-lg">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" class="custom-control-input" id="status<?php echo e($service->id); ?>"
                                name="status" value="1" <?php echo e($service->status == 1 ? 'checked' : ''); ?>>
                            <label class="custom-control-label" for="status<?php echo e($service->id); ?>">
                                <span
                                    class="font-weight-bold <?php echo e($service->status == 1 ? 'text-success' : 'text-danger'); ?>">
                                    <?php echo e($service->status == 1 ? trans('AdminSb.Enabled') : trans('AdminSb.Not_enabled')); ?>

                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light-5">
                    <button type="button" class="btn btn-outline-secondary btn-rounded px-4 btn-hover-transform"
                        data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i><?php echo e(trans('AdminSb.close')); ?>

                    </button>
                    <button type="submit"
                        class="btn btn-primary-gradient btn-rounded px-4 btn-hover-transform shadow-sm">
                        <i class="fas fa-save mr-2"></i><?php echo e(trans('AdminSb.submit')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Services/Single Service/edit.blade.php ENDPATH**/ ?>