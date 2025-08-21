<?php if(count($errors) > 0): ?>
    <script>
        window.onload = function() {
            notif({
                msg: "<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($error); ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>",
                type: "error",
                position: "right"
            });
        }
    </script>
<?php endif; ?>

<?php if(session()->has('edit')): ?>
    <script>
        window.onload = function() {
            notif({
                msg: "<?php echo e(trans('Dashboard/messages.edit')); ?>",
                type: "info",
                position: "right"
            });
        }
    </script>
<?php endif; ?>
<?php if(session()->has('delete')): ?>
    <script>
        window.onload = function() {
            notif({
                msg: "<?php echo e(trans('Dashboard/messages.delete')); ?>",
                type: "error",
                position: "right"
            });
        }
    </script>
<?php endif; ?>

<?php if(session()->has('add')): ?>
    <script>
        window.onload = function() {
            notif({
                msg: "<?php echo e(trans('Dashboard/messages.add')); ?>",
                type: "success",
                position: "right"
            });
        }
    </script>
<?php endif; ?>



<?php if(session()->has('update_password')): ?>
    <script>
        window.onload = function() {
            notif({
                msg: "<?php echo e(trans('Dashboard/messages.update_password')); ?>",
                type: "warning",
                position: "right"
            });
        }
    </script>
<?php endif; ?>

<?php if(session()->has('update_status')): ?>
    <script>
        window.onload = function() {
            notif({
                msg: "<?php echo e(trans('Dashboard/messages.update_status')); ?>",
                type: "warning",
                position: "right"
            });
        }
    </script>
<?php endif; ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/messages_alert.blade.php ENDPATH**/ ?>