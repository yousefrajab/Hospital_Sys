<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('Dashboard/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('Dashboard/plugins/datatable/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet">

   <?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    <?php echo e(trans('Services.Group Services')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('Services.Services')); ?></h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/  <?php echo e(trans('Services.Group Services')); ?></span>
            </div>
        </div>
    </div>
 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('create-group-services', [])->html();
} elseif ($_instance->childHasBeenRendered('zMjcIyE')) {
    $componentId = $_instance->getRenderedChildComponentId('zMjcIyE');
    $componentTag = $_instance->getRenderedChildComponentTagName('zMjcIyE');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('zMjcIyE');
} else {
    $response = \Livewire\Livewire::mount('create-group-services', []);
    $html = $response->html();
    $_instance->logRenderedChild('zMjcIyE', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                </div>
            </div>
        </div>

    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/livewire/GroupServices/include_create.blade.php ENDPATH**/ ?>