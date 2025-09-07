<div>
    <?php if($catchError): ?>
        <div class="alert alert-danger" id="success-danger">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <?php echo e($catchError); ?>

        </div>
    <?php endif; ?>

    <?php if($InvoiceSaved): ?>
        <div class="alert alert-info"><?php echo e(trans('AdminSb.data_saved_successfully')); ?></div>
    <?php endif; ?>

    <?php if($InvoiceUpdated): ?>
        <div class="alert alert-info"><?php echo e(trans('AdminSb.data_updated_successfully')); ?></div>
    <?php endif; ?>

    <?php if($show_table): ?>
        <?php echo $__env->make('livewire.single_invoices.Table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <form wire:submit.prevent="store" autocomplete="off">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col">
                    <label><?php echo e(trans('AdminSb.patient_name')); ?></label>
                    <select wire:model="patient_id" class="form-control" required>
                        <option value=""><?php echo e(trans('AdminSb.choose_from_list')); ?></option>
                        <?php $__currentLoopData = $Patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($Patient->id); ?>"><?php echo e($Patient->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col">
                    <label><?php echo e(trans('AdminSb.doctor_name')); ?></label>
                    <select wire:model="doctor_id" wire:change="get_section" class="form-control" id="exampleFormControlSelect1" required>
                        <option value=""><?php echo e(trans('AdminSb.choose_from_list')); ?></option>
                        <?php $__currentLoopData = $Doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($Doctor->id); ?>"><?php echo e($Doctor->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col">
                    <label><?php echo e(trans('AdminSb.department')); ?></label>
                    <input wire:model="section_id" type="text" class="form-control" readonly>
                </div>

                <div class="col">
                    <label><?php echo e(trans('AdminSb.invoice_type')); ?></label>
                    <select wire:model="type" class="form-control" <?php echo e($updateMode == true ? 'disabled':''); ?>>
                        <option value=""><?php echo e(trans('AdminSb.choose_from_list')); ?></option>
                        <option value="1"><?php echo e(trans('AdminSb.cash')); ?></option>
                        <option value="2"><?php echo e(trans('AdminSb.deferred')); ?></option>
                    </select>
                </div>
            </div><br>

            <div class="row row-sm">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mg-b-0"></h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mg-b-0 text-md-nowrap" style="text-align: center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo e(trans('AdminSb.service_name')); ?></th>
                                            <th><?php echo e(trans('AdminSb.service_price')); ?></th>
                                            <th><?php echo e(trans('AdminSb.discount_value')); ?></th>
                                            <th><?php echo e(trans('AdminSb.tax_percentage')); ?></th>
                                            <th><?php echo e(trans('AdminSb.tax_value')); ?></th>
                                            <th><?php echo e(trans('AdminSb.total_with_tax')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <select wire:model="Service_id" class="form-control" wire:change="get_price" id="exampleFormControlSelect1">
                                                    <option value=""><?php echo e(trans('AdminSb.choose_service')); ?></option>
                                                    <?php $__currentLoopData = $Services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($Service->id); ?>"><?php echo e($Service->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td><input wire:model="price" type="text" class="form-control" readonly></td>
                                            <td><input wire:model="discount_value" type="text" class="form-control"></td>
                                            <th><input wire:model="tax_rate" type="text" class="form-control"></th>
                                            <td><input type="text" class="form-control" value="<?php echo e($tax_value); ?>" readonly></td>
                                            <td><input type="text" class="form-control" readonly value="<?php echo e($subtotal + $tax_value); ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input class="btn btn-outline-success" type="submit" value="<?php echo e(trans('AdminSb.Data_confirmation')); ?>">
        </form>
    <?php endif; ?>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/livewire/single_invoices/single-invoices.blade.php ENDPATH**/ ?>