<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    فاتورة خدمة مفردة
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.invoices')); ?></h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('AdminSb.Single service invoice')); ?></span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <!-- breadcrumb -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('single-invoices', [])->html();
} elseif ($_instance->childHasBeenRendered('0eQosH6')) {
    $componentId = $_instance->getRenderedChildComponentId('0eQosH6');
    $componentTag = $_instance->getRenderedChildComponentTagName('0eQosH6');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('0eQosH6');
} else {
    $response = \Livewire\Livewire::mount('single-invoices', []);
    $html = $response->html();
    $_instance->logRenderedChild('0eQosH6', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
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

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/livewire/single_invoices/index.blade.php ENDPATH**/ ?>