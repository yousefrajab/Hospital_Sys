<div class="modal fade" id="edit<?php echo e($section->id); ?>" tabindex="-1" role="dialog" aria-labelledby="editLabel<?php echo e($section->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="editLabel<?php echo e($section->id); ?>">
                    <i class="fas fa-edit mr-2"></i><?php echo e(trans('Dashboard/sections_trans.edit_sections')); ?>

                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo e(route('admin.Sections.update', $section->id)); ?>" method="post" class="ajax-form">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="id" value="<?php echo e($section->id); ?>">

                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold"><?php echo e(trans('Dashboard/sections_trans.name_sections')); ?></label>
                        <input type="text" name="name" value="<?php echo e($section->name); ?>"
                               class="form-control rounded-lg shadow-sm" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold"><?php echo e(trans('sections_trans.description')); ?></label>
                        <textarea name="description" class="form-control rounded-lg shadow-sm" rows="3"><?php echo e($section->description); ?></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-dismiss="modal">
                        <?php echo e(trans('Dashboard/sections_trans.Close')); ?>

                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save mr-2"></i><?php echo e(trans('Dashboard/sections_trans.submit')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Sections/edit.blade.php ENDPATH**/ ?>