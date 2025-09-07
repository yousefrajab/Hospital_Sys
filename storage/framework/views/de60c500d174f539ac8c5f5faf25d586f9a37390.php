<?php $__env->startSection('css'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" />


    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #e6e9ff;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #2b2d42;
            --light-color: #f8f9fa;
            --gray-color: #6c757d;
        }

        body {
            background-color: #f4f7fc;
        }

        .patient-profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
            transition: all 0.3s ease;
            overflow: hidden;
            /* To contain the avatar shadow if it's too large */
        }

        .patient-profile-header:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        }

        .patient-profile-avatar {
            /* More standard avatar */
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            margin-right: 1.5rem;
            /* Space between avatar and text */
        }

        .patient-stats {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .stat-card {
            border-left: 3px solid var(--primary-color);
            transition: all 0.3s;
            background-color: #fff;
            border-radius: 8px;
            padding: 1rem;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .modern-tabs .nav-link {
            color: var(--gray-color);
            font-weight: 600;
            padding: 12px 20px;
            border: none;
            margin-right: 5px;
            position: relative;
            transition: all 0.3s;
            background: transparent;
            border-radius: 8px 8px 0 0;
        }

        .modern-tabs .nav-link.active {
            color: var(--primary-color);
            background: white;
            /* Tab content area is white, so active tab bg should match */
        }

        .modern-tabs .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: -1px;
            /* Align with card border */
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary-color);
        }

        .modern-tabs .nav-link:hover:not(.active) {
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .tab-content-card {
            background: white;
            border-radius: 0 0 15px 15px;
            /* Match header card */
            border: none;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        }

        .data-table {
            border-radius: 10px;
            overflow: hidden;
            width: 100% !important;
            /* Ensure DataTable takes full width */
        }

        .data-table th {
            background-color: var(--primary-light);
            color: var(--dark-color);
            font-weight: 600;
            border: none;
        }

        .data-table td {
            vertical-align: middle;
            border-top: 1px solid #f1f1f1;
        }

        .badge-pill {
            padding: 0.4em 0.75em;
            /* Slightly larger badges */
            font-weight: 500;
            border-radius: 50px;
        }

        .invoice-badge {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .receipt-badge {
            background-color: rgba(23, 162, 184, 0.1);
            color: var(--info-color);
        }

        .payment-badge {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .empty-state {
            background: #f9fafc;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            border: 1px dashed #e0e0e0;
        }

        .empty-state-icon {
            font-size: 48px;
            color: var(--gray-color);
            margin-bottom: 20px;
        }

        .balance-card {
            background: linear-gradient(135deg, #f5f7fa, #e4e8f0);
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .floating-action-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
            z-index: 100;
            transition: all 0.3s;
        }

        .floating-action-btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
            margin-bottom: 1.5rem;
        }

        .card-custom .card-header {
            background-color: white;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 15px 15px 0 0;
            font-weight: 600;
        }

        .list-group-item-action-custom {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .list-group-item-action-custom:last-child {
            border-bottom: none;
        }

        .list-group-item-action-custom strong {
            color: var(--dark-color);
        }

        @media (max-width: 768px) {
            .patient-profile-header {
                text-align: center;
            }

            .patient-profile-avatar {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .modern-tabs .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }

            .d-flex.align-items-center {
                /* For avatar + name responsive */
                flex-direction: column;
                align-items: center !important;
            }
        }


        .qr-code-section {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--admin-border-color);
        }

        .qr-code-image svg {
            display: block;
            margin: 0 auto 1rem auto;
            max-width: 300px;
            height: auto;
            border: 1px solid var(--admin-border-color);
            padding: 8px;
            background-color: white;
            border-radius: var(--admin-radius-lg);
        }

        .qr-code-instructions {
            font-size: 0.9rem;
            color: var(--admin-text-secondary);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    <i class="fas fa-user-injured"></i> ملف المريض - <?php echo e($Patient->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-users"></i>
                    <?php echo e(trans('AdminSb.Patients Management')); ?></h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('AdminSb.patient_profile')); ?></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="<?php echo e(route('admin.Patients.index')); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> <?php echo e(trans('AdminSb.patients list')); ?>

                </a>
            </div>
            <div class="pr-1 mb-3 mb-xl-0 mr-2">
                <a href="<?php echo e(route('admin.Patients.edit', $Patient->id)); ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> <?php echo e(trans('AdminSb.edit_patient_data')); ?>

                </a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row" data-aos="fade-in">

        
        <div class="col-lg-4 col-md-12">
            <div class="patient-profile-header p-4 mb-4" id="patientProfileHeaderSection">
                <div class="d-flex align-items-center">
                    <?php if($Patient->image): ?>
                        <img src="<?php echo e(Url::asset('Dashboard/img/patients/' . $Patient->image->filename)); ?>"
                            class="patient-profile-avatar" alt="<?php echo e($Patient->name); ?>">
                    <?php else: ?>
                        <img src="<?php echo e(Url::asset('Dashboard/img/doctor_default.png')); ?>" class="patient-profile-avatar"
                            alt="<?php echo e(trans('AdminSb.default_image')); ?>">
                    <?php endif; ?>
                    <div class="profile-text-info">
                        <h3 class="mb-1 font-weight-bold"><?php echo e($Patient->name); ?></h3>
                        <p class="mb-1 text-light op-8">
                            <i class="fas fa-id-card mr-1"></i>
                            <?php echo e(trans('AdminSb.patient_id')); ?>: <?php echo e($Patient->id); ?>

                        </p>
                        <span
                            class="badge badge-pill <?php echo e($Patient->Gender == 1 ? 'bg-primary-transparent text-primary' : 'bg-pink-transparent text-pink'); ?>"
                            style="background-color: rgba(255,255,255,0.2); color:white;">
                            <i class="fas <?php echo e($Patient->Gender == 1 ? 'fa-mars' : 'fa-venus'); ?> mr-1"></i>
                            <?php echo e($Patient->Gender == 1 ? trans('AdminSb.male') : trans('AdminSb.female')); ?>

                        </span>
                    </div>
                </div>
                <div class="qr-code-section">
                    <?php if($Patient && $Patient->id): ?>
                        <div class="qr-code-image">
                            <?php echo $Patient->generateQrCodeSvg(200); ?>

                        </div>
                        <p class="qr-code-instructions"><?php echo e(trans('AdminSb.scan_qr_for_profile')); ?></p>
                        <div class="qr-download-buttons mt-3 no-print">
                            <a href="#" class="btn btn-sm btn-outline-primary" style="color: white" id="downloadPNG">
                                <i class="fas fa-download"></i> <?php echo e(trans('AdminSb.download_png')); ?>

                            </a>
                            <a href="#" class="btn btn-sm btn-outline-success" style="color: white" id="downloadSVG">
                                <i class="fas fa-download"></i> <?php echo e(trans('AdminSb.download_svg')); ?>

                            </a>
                        </div>
                    <?php else: ?>
                        <p class="text-danger my-4"><?php echo e(trans('AdminSb.qr_code_error')); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="patient-stats p-3 mb-4">
                <h5 class="mb-3 text-center font-weight-bold text-primary"><?php echo e(trans('AdminSb.financial_summary')); ?></h5>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-card">
                            <h5 class="mb-1 text-primary font-weight-bold">
                                <?php echo e(number_format($invoices->sum('total_with_tax'), 2)); ?></h5>
                            <small class="text-muted"><?php echo e(trans('AdminSb.total_invoices')); ?></small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-card">
                            <h5 class="mb-1 text-success font-weight-bold">
                                <?php echo e(number_format($receipt_accounts->sum('amount'), 2)); ?></h5>
                            <small class="text-muted"><?php echo e(trans('AdminSb.total_payments')); ?></small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <h5 class="mb-1 text-info font-weight-bold"><?php echo e($invoices->count()); ?></h5>
                            <small class="text-muted"><?php echo e(trans('AdminSb.invoices_count')); ?></small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <?php $balance = $invoices->sum('total_with_tax') - $receipt_accounts->sum('amount'); ?>
                            <h5 class="mb-1 font-weight-bold <?php echo e($balance >= 0 ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e(number_format(abs($balance), 2)); ?>

                            </h5>
                            <small
                                class="text-muted"><?php echo e($balance >= 0 ? trans('AdminSb.debit') : trans('AdminSb.credit')); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2 text-primary"></i>
                        <?php echo e(trans('AdminSb.basic information')); ?></h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-id-card-alt mr-2 text-primary op-7"></i>
                                <?php echo e(trans('AdminSb.national_id')); ?></span>
                            <strong class="text-dark"><?php echo e($Patient->national_id); ?></strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-phone-alt mr-2 text-primary op-7"></i>
                                <?php echo e(trans('AdminSb.phone')); ?></span>
                            <strong class="text-dark"><?php echo e($Patient->Phone); ?></strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="far fa-envelope mr-2 text-primary op-7"></i>
                                <?php echo e(trans('AdminSb.email')); ?></span>
                            <strong class="text-dark"><?php echo e($Patient->email); ?></strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-birthday-cake mr-2 text-primary op-7"></i>
                                <?php echo e(trans('AdminSb.date_of_birth')); ?></span>
                            <strong
                                class="text-dark"><?php echo e(\Carbon\Carbon::parse($Patient->Date_Birth)->format('d M, Y')); ?></strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-tint mr-2 text-danger op-7"></i>
                                <?php echo e(trans('AdminSb.blood_type')); ?></span>
                            <span class="badge badge-danger-light"><?php echo e($Patient->Blood_Group); ?></span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="fas fa-map-marker-alt mr-2 text-primary op-7"></i>
                                <?php echo e(trans('AdminSb.detailed_address')); ?></span>
                            <strong class="text-dark"><?php echo e($Patient->Address); ?></strong>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center list-group-item-action-custom">
                            <span><i class="far fa-calendar-alt mr-2 text-primary op-7"></i>
                                <?php echo e(trans('AdminSb.date_added')); ?></span>
                            <strong class="text-dark"><?php echo e($Patient->created_at->format('d M, Y')); ?></strong>
                        </li>
                    </ul>
                </div>
            </div>

            
            <div class="card card-custom">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-notes-medical mr-2 text-danger"></i>
                        <?php echo e(trans('AdminSb.chronic_diseases')); ?></h5>
                </div>
                <div class="card-body">
                    <?php if($Patient->diagnosedChronicDiseases && $Patient->diagnosedChronicDiseases->count() > 0): ?>
                        <?php $__currentLoopData = $Patient->diagnosedChronicDiseases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diagnosedDisease): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3 pb-2 border-bottom">
                                <h6><i class="fas fa-disease text-danger mr-1"></i> <?php echo e($diagnosedDisease->name); ?></h6>
                                <small class="d-block text-muted">
                                    <i class="fas fa-calendar-check mr-1"></i> <?php echo e(trans('AdminSb.diagnosis_date_colon')); ?>

                                    <strong><?php echo e($diagnosedDisease->pivot->diagnosed_at ? \Carbon\Carbon::parse($diagnosedDisease->pivot->diagnosed_at)->format('d M, Y') : trans('AdminSb.undefined')); ?></strong>
                                </small>
                                
                                <small class="d-block text-muted">
                                    <i class="fas fa-thermometer-half mr-1"></i>
                                    <?php echo e(trans('AdminSb.current_status_colon')); ?>

                                    <strong><?php echo e(\App\Models\PatientChronicDisease::getStatuses()[$diagnosedDisease->pivot->current_status] ?? trans('AdminSb.undefined')); ?></strong>
                                </small>
                                <?php if($diagnosedDisease->pivot->treatment_plan): ?>
                                    <small class="d-block text-muted">
                                        <i class="fas fa-pills mr-1"></i> <?php echo e(trans('AdminSb.treatment_plan_colon')); ?>

                                        <strong><?php echo e(Str::limit($diagnosedDisease->pivot->treatment_plan, 50)); ?></strong>
                                    </small>
                                <?php endif; ?>
                                <?php if($diagnosedDisease->pivot->notes): ?>
                                    <small class="d-block text-muted">
                                        <i class="far fa-comment-dots mr-1"></i> <?php echo e(trans('AdminSb.notes_colon')); ?>

                                        <strong><?php echo e(Str::limit($diagnosedDisease->pivot->notes, 50)); ?></strong>
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="empty-state p-2 text-center">
                            <div class="empty-state-icon"><i class="fas fa-file-medical-alt"></i></div>
                            <p class="text-muted"><?php echo e(trans('AdminSb.no_chronic_diseases_registered')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-8 col-md-12">
            <div class="card card-custom">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav modern-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#invoices-tab" role="tab"
                                aria-controls="invoices-tab" aria-selected="true">
                                <i class="fas fa-file-invoice-dollar mr-1"></i> <?php echo e(trans('AdminSb.invoices')); ?>

                                <span class="badge badge-pill badge-light ms-1"><?php echo e($invoices->count()); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#account-tab" role="tab"
                                aria-controls="account-tab" aria-selected="false">
                                <i class="fas fa-calculator mr-1"></i> <?php echo e(trans('AdminSb.account_statement')); ?>

                                <span class="badge badge-pill badge-light ms-1"><?php echo e($receipt_accounts->count()); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#admissions-tab" role="tab"
                                aria-controls="admissions-tab" aria-selected="false">
                                <i class="fas fa-procedures mr-1"></i> <?php echo e(trans('AdminSb.admissions_log')); ?>

                                <span class="badge badge-pill badge-light ms-1"><?php echo e($patient->admissions->count()); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#radiology-tab" role="tab"
                                aria-controls="radiology-tab" aria-selected="false">
                                <i class="fas fa-x-ray mr-1"></i> <?php echo e(trans('AdminSb.radiology')); ?>

                                <?php if($patient_rays): ?>
                                    <span class="badge badge-pill badge-light ms-1"><?php echo e($patient_rays->count()); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#lab-tab" role="tab"
                                aria-controls="lab-tab" aria-selected="false">
                                <i class="fas fa-flask mr-1"></i> <?php echo e(trans('AdminSb.laboratory')); ?>

                                <?php if($patient_Laboratories): ?>
                                    <span
                                        class="badge badge-pill badge-light ms-1"><?php echo e($patient_Laboratories->count()); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body tab-content p-0 tab-content-card">
                    <!-- Invoices Tab -->
                    <div class="tab-pane fade show active p-3" id="invoices-tab" role="tabpanel">
                        <?php if($invoices->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table data-table table-hover text-md-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo e(trans('AdminSb.service_group')); ?></th>
                                            <th><?php echo e(trans('AdminSb.invoice_date')); ?></th>
                                            <th><?php echo e(trans('AdminSb.total_amount')); ?></th>
                                            <th><?php echo e(trans('AdminSb.invoice_type')); ?></th>
                                            <th><?php echo e(trans('AdminSb.options')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e($invoice->Service->name ?? ($invoice->Group->name ?? 'N/A')); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d')); ?>

                                                </td>
                                                <td><?php echo e(number_format($invoice->total_with_tax, 2)); ?></td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill <?php echo e($invoice->type == 1 ? 'invoice-badge' : 'payment-badge'); ?>">
                                                        <?php echo e($invoice->type == 1 ? trans('AdminSb.cash') : trans('AdminSb.deferred')); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary"
                                                        data-toggle="tooltip"
                                                        title="<?php echo e(trans('AdminSb.view_invoice')); ?>"><i
                                                            class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="3" class="text-right font-weight-bold">
                                                <?php echo e(trans('AdminSb.total')); ?></th>
                                            <th colspan="3" class="font-weight-bold">
                                                <?php echo e(number_format($invoices->sum('total_with_tax'), 2)); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                <h4><?php echo e(trans('AdminSb.no_invoices_registered')); ?></h4>
                                <p class="text-muted"><?php echo e(trans('AdminSb.no_invoices_yet_for_patient')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Account Statement Tab -->
                    <div class="tab-pane fade p-3" id="account-tab" role="tabpanel">
                        <?php if($Patient_accounts->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table data-table table-hover text-md-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo e(trans('AdminSb.date')); ?></th>
                                            <th><?php echo e(trans('AdminSb.description')); ?></th>
                                            <th><?php echo e(trans('AdminSb.debit_col')); ?></th>
                                            <th><?php echo e(trans('AdminSb.credit_col')); ?></th>
                                            <th><?php echo e(trans('AdminSb.balance')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $runningBalance = 0; ?>
                                        <?php $__currentLoopData = $Patient_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Patient_account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $currentDebit = $Patient_account->Debit ?? 0;
                                                $currentCredit = $Patient_account->credit ?? 0;
                                                $runningBalance += $currentDebit - $currentCredit;
                                            ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($Patient_account->date)->format('Y-m-d')); ?>

                                                </td>
                                                <td>
                                                    <?php if($Patient_account->invoice_id && $Patient_account->invoice): ?>
                                                        <?php echo e(trans('AdminSb.service_invoice_colon')); ?>

                                                        <?php echo e($Patient_account->invoice->Service->name ?? ($Patient_account->invoice->Group->name ?? 'N/A')); ?>

                                                    <?php elseif($Patient_account->receipt_id && $Patient_account->ReceiptAccount): ?>
                                                        <?php echo e(trans('AdminSb.receipt_colon')); ?>

                                                        <?php echo e($Patient_account->ReceiptAccount->description); ?>

                                                    <?php elseif($Patient_account->Payment_id && $Patient_account->PaymentAccount): ?>
                                                        <?php echo e(trans('AdminSb.payment_voucher_colon')); ?>

                                                        <?php echo e($Patient_account->PaymentAccount->description); ?>

                                                    <?php else: ?>
                                                        <?php echo e(trans('AdminSb.financial_transaction')); ?>

                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e(number_format($currentDebit, 2)); ?></td>
                                                <td><?php echo e(number_format($currentCredit, 2)); ?></td>
                                                <td
                                                    class="<?php echo e($runningBalance >= 0 ? 'text-danger' : 'text-success'); ?> font-weight-bold">
                                                    <?php echo e(number_format(abs($runningBalance), 2)); ?>

                                                    <small>(<?php echo e($runningBalance >= 0 ? trans('AdminSb.debit') : trans('AdminSb.credit')); ?>)</small>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="balance-card p-3 mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bold"><?php echo e(trans('AdminSb.final_balance_colon')); ?></h5>
                                    <?php $finalBalance = $Patient_accounts->sum('Debit') - $Patient_accounts->sum('credit'); ?>
                                    <h3
                                        class="mb-0 font-weight-bold <?php echo e($finalBalance >= 0 ? 'text-danger' : 'text-success'); ?>">
                                        <?php echo e(number_format(abs($finalBalance), 2)); ?>

                                        <small
                                            class="text-muted">(<?php echo e($finalBalance >= 0 ? trans('AdminSb.debit') : trans('AdminSb.credit')); ?>)</small>
                                    </h3>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-calculator"></i></div>
                                <h4><?php echo e(trans('AdminSb.no_financial_transactions')); ?></h4>
                                <p class="text-muted"><?php echo e(trans('AdminSb.no_financial_transactions_yet_for_patient')); ?>

                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Admissions History Tab -->
                    <div class="tab-pane fade p-3" id="admissions-tab" role="tabpanel">
                        <?php if($Patient->admissions && $Patient->admissions->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table data-table table-hover text-md-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo e(trans('AdminSb.admission_date')); ?></th>
                                            <th><?php echo e(trans('AdminSb.discharge_date')); ?></th>
                                            <th><?php echo e(trans('AdminSb.attending_physician')); ?></th>
                                            <th><?php echo e(trans('AdminSb.department')); ?></th>
                                            <th><?php echo e(trans('AdminSb.room_bed')); ?></th>
                                            <th><?php echo e(trans('AdminSb.reason_for_admission')); ?></th>
                                            <th><?php echo e(trans('AdminSb.status')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $Patient->admissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($admission->admission_date)->format('Y-m-d H:i')); ?>

                                                </td>
                                                <td><?php echo e($admission->discharge_date ? \Carbon\Carbon::parse($admission->discharge_date)->format('Y-m-d H:i') : trans('AdminSb.still_admitted')); ?>

                                                </td>
                                                <td><?php echo e($admission->doctor->name ?? trans('AdminSb.undefined')); ?></td>
                                                <td><?php echo e($admission->bed->room->section->name ?? trans('AdminSb.undefined')); ?>

                                                </td>
                                                <td><?php echo e($admission->bed->room->room_number ?? 'N/A'); ?> -
                                                    <?php echo e($admission->bed->bed_number ?? 'N/A'); ?></td>
                                                <td><?php echo e(Str::limit($admission->reason_for_admission, 40) ?? trans('AdminSb.undefined')); ?>

                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill <?php echo e($admission->status == \App\Models\PatientAdmission::STATUS_ADMITTED && !$admission->discharge_date ? 'badge-success-light' : 'badge-warning-light'); ?>">
                                                        <?php echo e(\App\Models\PatientAdmission::getAdmissionStatusText($admission->status)); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-procedures"></i></div>
                                <h4><?php echo e(trans('AdminSb.no_admission_history')); ?></h4>
                                <p class="text-muted"><?php echo e(trans('AdminSb.no_admission_discharge_yet')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Radiology Tab -->
                    <div class="tab-pane fade p-3" id="radiology-tab" role="tabpanel">
                        <div class="info-section-card mb-0">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-x-ray me-2"></i><?php echo e(trans('AdminSb.radiology_log')); ?></span>
                                <button class="btn btn-sm btn-outline-secondary no-print"
                                    onclick="printPatientSection(['#patientProfileHeaderSection', '#radiology-tab'])"><i
                                        class="fas fa-print fa-sm"></i>
                                    <?php echo e(trans('AdminSb.print_this_section')); ?></button>
                            </div>
                            <div class="card-body p-0">
                                <?php if(isset($patient_rays) && $patient_rays->count() > 0): ?>
                                    <div class="table-responsive px-3 pt-3">
                                        <table id="raysTable"
                                            class="table table-records table-sm table-hover table-striped w-100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo e(trans('AdminSb.description_request')); ?></th>
                                                    <th><?php echo e(trans('AdminSb.requesting_physician')); ?></th>
                                                    <th><?php echo e(trans('AdminSb.request_date')); ?></th>
                                                    <th class="text-center"><?php echo e(trans('AdminSb.status')); ?></th>
                                                    <th class="text-center no-print"><?php echo e(trans('AdminSb.result')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $patient_rays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ray): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($loop->iteration); ?></td>
                                                        <td><?php echo e(Str::limit($ray->description, 35)); ?></td>
                                                        <td><?php echo e($ray->doctor->name ?? '-'); ?></td>
                                                        <td><?php echo e($ray->created_at ? $ray->created_at->translatedFormat('d M Y, H:i A') : '-'); ?>

                                                        </td>
                                                        <td class="text-center">
                                                            <?php if($ray->case == 1): ?>
                                                                <span
                                                                    class="status-badge-sm status-completed"><?php echo e(trans('AdminSb.completed')); ?></span>
                                                            <?php else: ?>
                                                                <span
                                                                    class="status-badge-sm status-pending"><?php echo e(trans('AdminSb.in_progress')); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center no-print">
                                                            <?php if($ray->case == 1 && isset($ray->employee_id) && $ray->image && $ray->image->filename): ?>
                                                                <a href="<?php echo e(asset('ads/rays/' . $ray->image->filename)); ?>"
                                                                    data-lightbox="ray-images-<?php echo e($patient->id); ?>"
                                                                    data-title="<?php echo e(trans('AdminSb.radiology')); ?>: <?php echo e($ray->description); ?> - <?php echo e(trans('AdminSb.date')); ?>: <?php echo e($ray->created_at ? $ray->created_at->translatedFormat('d M Y') : ''); ?>"
                                                                    class="btn btn-xs btn-outline-info px-2 py-1"
                                                                    data-toggle="tooltip"
                                                                    title="<?php echo e(trans('AdminSb.view_radiology_result')); ?>">
                                                                    <i class="fas fa-file-image fa-fw"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state-compact p-3 m-3"><i class="fas fa-radiation"></i>
                                        <p><?php echo e(trans('AdminSb.no_previous_radiology_requests')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Lab Tab -->
                    <div class="tab-pane fade p-3" id="lab-tab" role="tabpanel">
                        <div class="info-section-card mb-0">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-flask-vial me-2"></i><?php echo e(trans('AdminSb.lab_log')); ?></span>
                                <button class="btn btn-sm btn-outline-secondary no-print"
                                    onclick="printPatientSection(['#patientProfileHeaderSection', '#lab-tab'])"><i
                                        class="fas fa-print fa-sm"></i>
                                    <?php echo e(trans('AdminSb.print_this_section')); ?></button>
                            </div>
                            <div class="card-body p-0">
                                <?php if(isset($patient_Laboratories) && $patient_Laboratories->count() > 0): ?>
                                    <div class="table-responsive px-3 pt-3">
                                        <table id="labsTable"
                                            class="table table-records table-sm table-hover table-striped w-100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo e(trans('AdminSb.description_request')); ?></th>
                                                    <th><?php echo e(trans('AdminSb.requesting_physician')); ?></th>
                                                    <th><?php echo e(trans('AdminSb.request_date')); ?></th>
                                                    <th class="text-center"><?php echo e(trans('AdminSb.status')); ?></th>
                                                    <th class="text-center no-print"><?php echo e(trans('AdminSb.result')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $patient_Laboratories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($loop->iteration); ?></td>
                                                        <td><?php echo e(Str::limit($lab->description, 35)); ?></td>
                                                        <td><?php echo e($lab->doctor->name ?? '-'); ?></td>
                                                        <td><?php echo e($lab->created_at ? $lab->created_at->translatedFormat('d M Y, H:i A') : '-'); ?>

                                                        </td>
                                                        <td class="text-center">
                                                            <?php if($lab->case == 1): ?>
                                                                <span
                                                                    class="status-badge-sm status-completed"><?php echo e(trans('AdminSb.completed')); ?></span>
                                                            <?php else: ?>
                                                                <span
                                                                    class="status-badge-sm status-pending"><?php echo e(trans('AdminSb.in_progress')); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center no-print">
                                                            <?php if($lab->case == 1 && isset($lab->employee_id) && $lab->image && $lab->image->filename): ?>
                                                                <a href="<?php echo e(asset('Dashboard/img/laboratories/' . $lab->image->filename)); ?>"
                                                                    data-lightbox="lab-images-<?php echo e($patient->id); ?>"
                                                                    data-title="<?php echo e(trans('AdminSb.lab_test')); ?>: <?php echo e($lab->description); ?> - <?php echo e(trans('AdminSb.date')); ?>: <?php echo e($lab->created_at ? $lab->created_at->translatedFormat('d M Y') : ''); ?>"
                                                                    class="btn btn-xs btn-outline-info px-2 py-1"
                                                                    data-toggle="tooltip"
                                                                    title="<?php echo e(trans('AdminSb.view_lab_result')); ?>">
                                                                    <i class="fas fa-file-alt fa-fw"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state-compact p-3 m-3"><i class="fas fa-vial-virus"></i>
                                        <p><?php echo e(trans('AdminSb.no_previous_lab_requests')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <a href="#" class="floating-action-btn" data-toggle="tooltip" title="<?php echo e(trans('AdminSb.quick_action')); ?>">
        <i class="fas fa-plus"></i>
    </a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        $(document).ready(function() {
            // Initialize DataTables for all tables with class 'data-table'
            $('.data-table').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json",
                    searchPlaceholder: "ابحث هنا..."
                },
                // dom: '<"top"f>rt<"bottom"lip><"clear">', // Default is fine
                pageLength: 10, // Or any other number
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],

            });

            // Tooltip initialization
            $('[data-toggle="tooltip"]').tooltip();

            // Smooth scroll for tabs and activate tab
            $('.modern-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                // If you want to scroll to the tab content when it's shown (optional)
                // let target = $(e.target).attr("href"); // activated tab
                // $(target).get(0).scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            // Handle deep linking for tabs
            let hash = window.location.hash;
            if (hash) {
                $('.modern-tabs a[href="' + hash + '"]').tab('show');
                // Scroll to tab content after a short delay to ensure it's visible
                setTimeout(function() {
                    $(hash).get(0).scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }

            // Update hash on tab change
            $('.modern-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                window.location.hash = e.target.hash;
                // Optional: Scroll to top of tab content when tab changes
                // $(e.target.hash).scrollTop(0);
            });


        });

        document.addEventListener('DOMContentLoaded', function() {
            // تحميل QR كصورة PNG
            document.getElementById('downloadPNG').addEventListener('click', function(e) {
                e.preventDefault();
                const svg = document.querySelector('.qr-code-image svg');
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const data = new XMLSerializer().serializeToString(svg);
                const DOMURL = window.URL || window.webkitURL || window;

                const img = new Image();
                const svgBlob = new Blob([data], {
                    type: 'image/svg+xml;charset=utf-8'
                });
                const url = DOMURL.createObjectURL(svgBlob);

                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);
                    DOMURL.revokeObjectURL(url);

                    const png = canvas.toDataURL('image/png');
                    const downloadLink = document.createElement('a');
                    downloadLink.href = png;
                    downloadLink.download = 'patient_qr_<?php echo e($Patient->id); ?>.png';
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                };

                img.src = url;
            });

            // تحميل QR كـ SVG
            document.getElementById('downloadSVG').addEventListener('click', function(e) {
                e.preventDefault();
                const svg = document.querySelector('.qr-code-image svg').outerHTML;
                const blob = new Blob([svg], {
                    type: 'image/svg+xml'
                });
                const url = URL.createObjectURL(blob);
                const downloadLink = document.createElement('a');
                downloadLink.href = url;
                downloadLink.download = 'patient_qr_<?php echo e($Patient->id); ?>.svg';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                URL.revokeObjectURL(url);
            });

            <?php if(session('success')): ?>
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> <?php echo e(session('success')); ?>",
                    type: "success",
                    position: "bottom",
                    autohide: true,
                    timeout: 5000
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> <?php echo e(session('error')); ?>",
                    type: "error",
                    position: "bottom",
                    autohide: true,
                    timeout: 7000
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Patients/show.blade.php ENDPATH**/ ?>