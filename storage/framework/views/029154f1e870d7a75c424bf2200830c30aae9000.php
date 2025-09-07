 <?php $__env->startSection('title'); ?>
     الكشوفات
 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('css'); ?>


 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('page-header'); ?>
     <!-- breadcrumb -->
     <div class="breadcrumb-header justify-content-between">
         <div class="my-auto">
             <div class="d-flex">
                 <h4 class="content-title mb-0 my-auto">صور الاشعة</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e($rays->Patient->name); ?></span>
             </div>
         </div>
     </div>
     <!-- breadcrumb -->
 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('content'); ?>

     <div class="form-group">
         <label for="exampleFormControlTextarea1">ملاحظات دكتور الاشعة</label>
         <textarea readonly class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo e($rays->description_employee); ?></textarea>
     </div>



    <div class="demo-gallery">
        <ul id="lightgallery" class="list-unstyled row row-sm pr-0">
            <?php $__currentLoopData = $rays->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0);" onclick="openImage('<?php echo e(URL::asset('Dashboard/img/Rays/'.$image->filename)); ?>')">
                        <img width="100%" height="350px" class="img-responsive" src="<?php echo e(URL::asset('Dashboard/img/Rays/'.$image->filename)); ?>" alt="NoImg">
                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>

    <script>
        function openImage(imageUrl) {
            // فتح نافذة جديدة بمقاس معين
            window.open(imageUrl, '_blank', 'width=800,height=600,top=100,left=200,scrollbars=yes,resizable=yes');
        }
    </script>

     <!-- row closed -->
     </div>
     <!-- Container closed -->
     </div>
     <!-- main-content closed -->

 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('js'); ?>
 <?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/dashboard_RayEmployee/invoices/patient_details.blade.php ENDPATH**/ ?>