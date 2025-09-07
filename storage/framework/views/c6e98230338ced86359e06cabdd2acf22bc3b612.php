<div>
    <?php if($ServiceSaved): ?>
        <div class="alert alert-info"><?php echo e(trans('Services.Data has been saved successfully.')); ?></div>
    <?php endif; ?>

    <?php if($ServiceUpdated): ?>
        <div class="alert alert-info"><?php echo e(trans('Services.Data has been updated successfully.')); ?></div>
    <?php endif; ?>

    <?php if($show_table): ?>
        <?php echo $__env->make('livewire.GroupServices.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <form wire:submit.prevent="saveGroup" autocomplete="off">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label><?php echo e(trans('AdminSb.Group_Name')); ?></label>
                <input wire:model="name_group" type="text" name="name_group" class="form-control" required>
            </div>

            <div class="form-group">
                <label><?php echo e(trans('AdminSb.description')); ?></label>
                <textarea wire:model="notes" name="notes" class="form-control" rows="5"></textarea>
            </div>

            <div class="form-group">
                <label for="group_doctor_id_field"><?php echo e(trans('AdminSb.package_responsible_doctor_optional')); ?></label>
                <select wire:model.defer="group_doctor_id" id="group_doctor_id_field"
                    class="form-control <?php $__errorArgs = ['group_doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value=""><?php echo e(trans('AdminSb.select_doctor_for_package')); ?></option>
                    <?php if(isset($availableDoctorsForGroup)): ?>
                        <?php $__currentLoopData = $availableDoctorsForGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['group_doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <div class="col-md-12">
                        <button class="btn btn-outline-primary" style="color: white"
                            wire:click.prevent="addService"><?php echo e(trans('AdminSb.Add_sub_service')); ?></button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th><?php echo e(trans('AdminSb.Service_Name')); ?></th>
                                    <th width="200"><?php echo e(trans('AdminSb.Number')); ?></th>
                                    <th width="200"><?php echo e(trans('AdminSb.responsible_doctor')); ?></th>
                                    <th width="200"><?php echo e(trans('AdminSb.Operations')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $GroupsItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $groupItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($groupItem['is_saved']): ?>
                                                <input type="hidden"
                                                    name="GroupsItems[<?php echo e($index); ?>][service_id]"
                                                    wire:model="GroupsItems.<?php echo e($index); ?>.service_id" />
                                                <?php if($groupItem['service_name'] && $groupItem['service_price']): ?>
                                                    <?php echo e($groupItem['service_name']); ?>

                                                    (<?php echo e(number_format($groupItem['service_price'], 2)); ?>)
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <select name="GroupsItems[<?php echo e($index); ?>][service_id]"
                                                    class="form-control<?php echo e($errors->has('GroupsItems.' . $index) ? ' is-invalid' : ''); ?>"
                                                    wire:model="GroupsItems.<?php echo e($index); ?>.service_id">
                                                    <option value=""><?php echo e(trans('AdminSb.choose_service')); ?>

                                                    </option>
                                                    <?php $__currentLoopData = $allServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($service->id); ?>">
                                                            <?php echo e(\App\Models\ServiceTranslation::where(['Service_id' => $service->id])->pluck('name')->first()); ?>

                                                            (<?php echo e(number_format($service->price, 2)); ?>)
                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php if($errors->has('GroupsItems.' . $index)): ?>
                                                    <em
                                                        class="invalid-feedback"><?php echo e($errors->first('GroupsItems.' . $index)); ?></em>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($groupItem['is_saved']): ?>
                                                <input type="hidden" name="GroupsItems[<?php echo e($index); ?>][quantity]"
                                                    wire:model="GroupsItems.<?php echo e($index); ?>.quantity" />
                                                <?php echo e($groupItem['quantity']); ?>

                                            <?php else: ?>
                                                <input type="number" name="GroupsItems[<?php echo e($index); ?>][quantity]"
                                                    class="form-control"
                                                    wire:model="GroupsItems.<?php echo e($index); ?>.quantity" />
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($groupItem['is_saved']): ?>
                                                
                                            <?php else: ?>
                                                
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($groupItem['is_saved']): ?>
                                                <button class="btn btn-sm btn-primary"
                                                    wire:click.prevent="editService(<?php echo e($index); ?>)"><?php echo e(trans('AdminSb.Edit')); ?></button>
                                            <?php elseif($groupItem['service_id']): ?>
                                                <button class="btn btn-sm btn-success mr-1"
                                                    wire:click.prevent="saveService(<?php echo e($index); ?>)"><?php echo e(trans('AdminSb.Confirm')); ?></button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click.prevent="removeService(<?php echo e($index); ?>)"><?php echo e(trans('AdminSb.Delete')); ?></button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-lg-4 ml-auto text-right">
                        <table class="table pull-right">
                            <tr>
                                <td style="color: red"><?php echo e(trans('AdminSb.Total')); ?></td>
                                <td><?php echo e(number_format($subtotal, 2)); ?></td>
                            </tr>
                            <tr>
                                <td style="color: red"><?php echo e(trans('AdminSb.Discount_Amount')); ?></td>
                                <td width="125">
                                    <input type="number" name="discount_value" class="form-control w-75 d-inline"
                                        wire:model="discount_value">
                                </td>
                            </tr>
                            <tr>
                                <td style="color: red"><?php echo e(trans('AdminSb.Tax_Rate')); ?></td>
                                <td>
                                    <input type="number" name="taxes" class="form-control w-75 d-inline"
                                        min="0" max="100" wire:model="taxes"> %
                                </td>
                            </tr>
                            <tr>
                                <td style="color: red"><?php echo e(trans('AdminSb.Total_including_tax')); ?></td>
                                <td><?php echo e(number_format($total, 2)); ?></td>
                            </tr>
                        </table>
                    </div>
                    <br />
                    <div>
                        <input class="btn btn-outline-success" type="submit"
                            value="<?php echo e(trans('AdminSb.Data_confirmation')); ?>">
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>


</div>
<?php /**PATH H:\Hospital_Sys\resources\views/livewire/GroupServices/create-group-services.blade.php ENDPATH**/ ?>