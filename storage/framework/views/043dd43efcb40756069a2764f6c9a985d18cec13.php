 <?php $__env->startSection('title'); ?>
     <?php echo e(trans('Dashboard/main-sidebar_trans.sections')); ?>

 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('css'); ?>
     <!-- Internal Data table css -->
     <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">
     <!--Internal   Notify -->
     <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet"/>
 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('page-header'); ?>
 				<!-- breadcrumb -->
 				<div class="breadcrumb-header justify-content-between">
 					<div class="my-auto">
 						<div class="d-flex">
 							<h4 class="content-title mb-0 my-auto"><?php echo e(trans('patients.patient_operations')); ?></h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('patients.laboratory')); ?></span>
 						</div>
 					</div>
 				</div>
 				<!-- breadcrumb -->
 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('content'); ?>
 <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 				<!-- row -->
                     <!-- row opened -->
                     <div class="row row-sm">
                         <div class="col-xl-12">
                             <div class="card">
                                 <div class="card-header pb-0">
                                 </div>
                                 <div class="card-body">
                                     <div class="table-responsive">
                                         <table style="text-align: center" class="table text-md-nowrap" id="example1">
                                             <thead>
                                             <tr>
                                                 <th>#</th>
                                                 <th><?php echo e(trans('patients.required_test')); ?></th>
                                                 <th ><?php echo e(trans('patients.doctor_name')); ?></th>
                                                 <th><?php echo e(trans('patients.lab_doctor_name')); ?></th>
                                                 <th><?php echo e(trans('patients.lab_notes')); ?></th>
                                                 <th><?php echo e(trans('patients.actions_col')); ?></th>
                                             </tr>
                                             </thead>
                                             <tbody>
                                            <?php $__currentLoopData = $laboratories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laboratorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($loop->iteration); ?></td>
                                                    <td><?php echo e($laboratorie->description); ?></td>
                                                    <td><?php echo e($laboratorie->doctor->name); ?></td>
                                                    <td><?php echo e($laboratorie->employee->name); ?></td>
                                                    <td><?php echo e($laboratorie->description_employee); ?></td>
                                                    <td>
                                                        <?php if($laboratorie->employee_id !== null): ?>
                                                            <a class="btn btn-primary btn-sm" href="<?php echo e(route('laboratories.view',$laboratorie->id)); ?>" role="button"><?php echo e(trans('patients.view_analysis')); ?></a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                             </tbody>
                                         </table>
                                     </div>
                                 </div><!-- bd -->
                             </div><!-- bd -->
                         </div>
                         <!--/div-->

                     <?php echo $__env->make('Dashboard.Sections.add', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                     <!-- /row -->

 				</div>
 				<!-- row closed -->

 			<!-- Container closed -->

 		<!-- main-content closed -->
 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('js'); ?>


     <!--Internal  Notify js -->
     <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/plugins/notify/js/notifit-custom.js')); ?>"></script>

 <?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/dashboard_patient/laboratories.blade.php ENDPATH**/ ?>