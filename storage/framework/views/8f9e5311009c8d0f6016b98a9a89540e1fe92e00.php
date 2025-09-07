<button class="btn btn-primary pull-right" wire:click="show_form_add" type="button"><?php echo e(trans('AdminSb.add_new_invoice')); ?></button><br><br>
<div class="table-responsive">
    <table class="table text-md-nowrap" id="example1" data-page-length="50" style="text-align: center">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo e(trans('AdminSb.service_name')); ?></th>
                <th><?php echo e(trans('AdminSb.patient_name')); ?></th>
                <th><?php echo e(trans('AdminSb.invoice_date')); ?></th>
                <th><?php echo e(trans('AdminSb.doctor_name')); ?></th>
                <th><?php echo e(trans('AdminSb.department')); ?></th>
                <th><?php echo e(trans('AdminSb.service_price')); ?></th>
                <th><?php echo e(trans('AdminSb.discount_value')); ?></th>
                <th><?php echo e(trans('AdminSb.tax_percentage')); ?></th>
                <th><?php echo e(trans('AdminSb.tax_value')); ?></th>
                <th><?php echo e(trans('AdminSb.total_with_tax')); ?></th>
                <th><?php echo e(trans('AdminSb.invoice_type')); ?></th>
                <th><?php echo e(trans('AdminSb.Processes')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $single_invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $single_invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($single_invoice->Service->name); ?></td>
                    <td><?php echo e($single_invoice->Patient->name); ?></td>
                    <td><?php echo e($single_invoice->invoice_date); ?></td>
                    <td><?php echo e($single_invoice->Doctor->name); ?></td>
                    <td><?php echo e($single_invoice->Section->name); ?></td>
                    <td><?php echo e(number_format($single_invoice->price, 2)); ?></td>
                    <td><?php echo e(number_format($single_invoice->discount_value, 2)); ?></td>
                    <td><?php echo e($single_invoice->tax_rate); ?>%</td>
                    <td><?php echo e(number_format($single_invoice->tax_value, 2)); ?></td>
                    <td><?php echo e(number_format($single_invoice->total_with_tax, 2)); ?></td>
                    <td><?php echo e($single_invoice->type == 1 ? trans('AdminSb.cash') : trans('AdminSb.deferred')); ?></td>
                    <td>
                        <button wire:click="edit(<?php echo e($single_invoice->id); ?>)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#delete_invoice" wire:click="prepareForDelete(<?php echo e($single_invoice->id); ?>)"
                            title="<?php echo e(trans('AdminSb.delete_invoice')); ?>">
                            <i class="fa fa-trash"></i></button>
                        <button wire:click="print(<?php echo e($single_invoice->id); ?>)" class="btn btn-primary btn-sm"><i class="fas fa-print"></i></button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>

    <?php echo $__env->make('livewire.single_invoices.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/livewire/single_invoices/Table.blade.php ENDPATH**/ ?>