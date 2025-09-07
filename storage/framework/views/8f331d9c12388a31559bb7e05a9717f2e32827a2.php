<div >

    <?php if($InvoiceSaved): ?>
        <div class="alert alert-info">تم حفظ البيانات بنجاح.</div>
    <?php endif; ?>

    <?php if($InvoiceUpdated): ?>
        <div class="alert alert-info">تم تعديل البيانات بنجاح.</div>
    <?php endif; ?>

    <?php if($show_table): ?>
        <?php echo $__env->make('livewire.group_invoices.Table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <form wire:submit.prevent="store" autocomplete="off">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col">
                    <label>اسم المريض</label>
                    <select wire:model="patient_id" class="form-control" required>
                        <option value=""  >-- اختار من القائمة --</option>
                        <?php $__currentLoopData = $Patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($Patient->id); ?>"><?php echo e($Patient->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>


                <div class="col">
                    <label>اسم الدكتور</label>
                    <select wire:model="doctor_id"  wire:change="get_section" class="form-control"  id="exampleFormControlSelect1" required>
                        <option value="" >-- اختار من القائمة --</option>
                        <?php $__currentLoopData = $Doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($Doctor->id); ?>"><?php echo e($Doctor->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>


                <div class="col">
                    <label>القسم</label>
                    <input wire:model="section_id" type="text" class="form-control" readonly >
                </div>

                <div class="col">
                    <label>نوع الفاتورة</label>
                    <select wire:model="type" class="form-control" <?php echo e($updateMode == true ? 'disabled':''); ?>>
                        <option value="" >-- اختار من القائمة --</option>
                        <option value="1">نقدي</option>
                        <option value="2">اجل</option>
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
                                        <th>اسم الخدمة</th>
                                        <th>سعر الخدمة</th>
                                        <th>قيمة الخصم</th>
                                        <th>نسبة الضريبة</th>
                                        <th>قيمة الضريبة</th>
                                        <th>الاجمالي مع الضريبة</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>
                                            <select wire:model="Group_id" class="form-control" wire:change="get_price" id="exampleFormControlSelect1">
                                                <option value="">-- اختار الخدمة --</option>
                                                <?php $__currentLoopData = $Groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($Group->id); ?>"><?php echo e($Group->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <td><input wire:model="price" type="text" class="form-control" readonly></td>
                                        <td><input wire:model="discount_value" type="text" class="form-control" readonly></td>
                                        <th><input wire:model="tax_rate" type="text" class="form-control" readonly ></th>
                                        <td><input type="text" class="form-control" value="<?php echo e($tax_value); ?>" readonly  ></td>
                                        <td><input type="text" class="form-control" readonly value="<?php echo e($subtotal + $tax_value); ?>"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!-- bd -->
                        </div><!-- bd -->
                    </div><!-- bd -->
                </div>
            </div>
            <input class="btn btn-outline-success" type="submit" value="تاكيد البيانات">
        </form>
    <?php endif; ?>

</div>
<?php /**PATH H:\Hospital_Sys\resources\views/livewire/group_invoices/group-invoices.blade.php ENDPATH**/ ?>