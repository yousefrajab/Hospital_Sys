<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title', 'اضافة سند قبض جديد'); ?>
<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.accounts')); ?></h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('AdminSb.add_new_receipt')); ?></span>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.Receipt.store')); ?>" method="post" autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-2"><label for="patient_id"><?php echo e(trans('AdminSb.patient_name')); ?> <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-10 mg-t-5 mg-md-t-0">
                                    <select name="patient_id" id="patient_id"
                                        class="form-control select2 <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="" selected disabled><?php echo e(trans('AdminSb.select_option')); ?>

                                        </option>
                                        <?php $__currentLoopData = $Patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($Patient->id); ?>"
                                                <?php echo e(old('patient_id') == $Patient->id ? 'selected' : ''); ?>>
                                                <?php echo e($Patient->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-2">
                                    <label for="item_type_id"><?php echo e(trans('AdminSb.service_package_optional')); ?></label>
                                </div>
                                <div class="col-md-10 mg-t-5 mg-md-t-0">
                                    <select name="item_type_id" id="item_type_id"
                                        class="form-control select2 <?php $__errorArgs = ['item_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="" selected data-price="0" data-name="">
                                            <?php echo e(trans('AdminSb.select_option_optional')); ?></option>
                                        <?php if(isset($Services) && $Services->count() > 0): ?>
                                            <optgroup label="<?php echo e(trans('AdminSb.Single_service')); ?>">
                                                <?php $__currentLoopData = $Services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="service_<?php echo e($Service->id); ?>"
                                                        data-price="<?php echo e($Service->price); ?>"
                                                        data-name="<?php echo e($Service->name); ?>"
                                                        <?php echo e(old('item_type_id') == 'service_' . $Service->id ? 'selected' : ''); ?>>
                                                        <?php echo e($Service->name); ?> (<?php echo e(number_format($Service->price, 2)); ?>

                                                        <?php echo e(config('app.currency_symbol', 'NIS')); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                        <?php endif; ?>
                                        <?php if(isset($GroupedServices) && $GroupedServices->count() > 0): ?>
                                            <optgroup label="<?php echo e(trans('AdminSb.Group_Services')); ?>">
                                                <?php $__currentLoopData = $GroupedServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="group_<?php echo e($Group->id); ?>"
                                                        data-price="<?php echo e($Group->Total_with_tax ?? 0); ?>"
                                                        data-name="<?php echo e($Group->name); ?>"
                                                        <?php echo e(old('item_type_id') == 'group_' . $Group->id ? 'selected' : ''); ?>>
                                                        <?php echo e(trans('AdminSb.package_colon')); ?> <?php echo e($Group->name); ?>

                                                        (<?php echo e(number_format($Group->Total_with_tax ?? 0, 2)); ?>

                                                        <?php echo e(config('app.currency_symbol', 'NIS')); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['item_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-2"><label for="Debit"><?php echo e(trans('AdminSb.amount')); ?> <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-10 mg-t-5 mg-md-t-0">
                                    <input class="form-control <?php $__errorArgs = ['Debit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="Debit"
                                        id="Debit" value="<?php echo e(old('Debit')); ?>" type="number" step="0.01" required>
                                    <?php $__errorArgs = ['Debit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-2"><label for="description"><?php echo e(trans('AdminSb.description')); ?></label>
                                </div>
                                <div class="col-md-10 mg-t-5 mg-md-t-0">
                                    <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="description" id="description"
                                        rows="3"><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <button type="submit"
                                class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5"><?php echo e(trans('AdminSb.submit')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/js/form-elements.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2').select2({
                    placeholder: "<?php echo e(trans('forms.select_option')); ?>",
                    allowClear: true
                });
            }

            $('#item_type_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var price = selectedOption.data('price');
                var itemName = selectedOption.data('name'); // اسم الخدمة أو الباقة
                var currentDescription = $('#description').val();
                var itemValue = $(this).val(); //  "service_X" or "group_Y"

                if (price !== undefined && itemValue !== "") {
                    $('#Debit').val(parseFloat(price).toFixed(2));
                } else if (itemValue === "") { // إذا تم اختيار "اختر من القائمة"
                    $('#Debit').val(''); // مسح المبلغ
                }


                if (itemName !== undefined && itemValue !== "" && (currentDescription === "" ||
                        currentDescription.startsWith("رسوم خدمة:") || currentDescription.startsWith(
                            "رسوم باقة:"))) {
                    var prefix = itemValue.startsWith("service_") ? "رسوم خدمة: " : (itemValue.startsWith(
                        "group_") ? "رسوم باقة: " : "");
                    $('#description').val(prefix + itemName);
                } else if (itemValue === "" && (currentDescription.startsWith("رسوم خدمة:") ||
                        currentDescription.startsWith("رسوم باقة:"))) {
                    // $('#description').val(''); // أو اتركه إذا كان المستخدم قد كتب شيئاً آخر
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Receipt/add.blade.php ENDPATH**/ ?>