<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(LaravelLocalization::getCurrentLocaleDirection()); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo e(trans('patients.my_invoices_page_title', ['name' => $patient->name])); ?> - <?php echo e(config('app.name', trans('patients.medical_platform'))); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" /> 


    <style>
        :root {
            --invoicepage-primary-color: <?php echo e($settings['invoicepage_primary_color'] ?? '#009688'); ?>; /* Teal */
            --invoicepage-primary-color-rgb: <?php echo e($settings['invoicepage_primary_color_rgb'] ?? '0, 150, 136'); ?>;
            --invoicepage-secondary-color: <?php echo e($settings['invoicepage_secondary_color'] ?? '#FFC107'); ?>; /* Amber */
            --invoicepage-text-color: #374151;
            --invoicepage-heading-color: #1a202c;
            --invoicepage-light-text: #718096;
            --invoicepage-bg-color: #f7fafc;
            --invoicepage-card-bg: #ffffff;
            --invoicepage-border-color: #e2e8f0;
            --invoicepage-radius: 0.5rem; /* 8px */
            --invoicepage-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            --invoicepage-font-primary: 'Cairo', sans-serif;
            --invoicepage-font-secondary: 'Tajawal', sans-serif;

            --status-paid-bg: #d1e7dd; --status-paid-text: #0f5132;
            --status-unpaid-bg: #fff3cd; --status-unpaid-text: #664d03;
            --status-cancelled-bg: #f8d7da; --status-cancelled-text: #58151c;
        }

        html, body { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--invoicepage-font-primary); background-color: var(--invoicepage-bg-color); color: var(--invoicepage-text-color); line-height: 1.7; font-size: 16px; }
        *, *::before, *::after { box-sizing: inherit; }

        .my-invoices-container { max-width: 1200px; margin: 0 auto; padding: 2.5rem 1.25rem; }

        .standalone-invoice-header { background-color: var(--invoicepage-card-bg); padding: 1rem 1.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 2.5rem; }
        .standalone-invoice-header .container-fluid { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .standalone-invoice-header .site-branding .site-title { font-family: var(--invoicepage-font-secondary); font-size: 1.6rem; color: var(--invoicepage-primary-color); margin: 0; font-weight: 700; text-decoration:none; }
        .standalone-invoice-header .user-actions { display:flex; align-items:center; gap:1rem;}
        .standalone-invoice-header .user-actions .welcome-text { color: var(--invoicepage-light-text); font-size: 0.9rem;}
        .standalone-invoice-header .user-actions a { color: var(--invoicepage-primary-color); text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: color 0.2s ease; }
        .standalone-invoice-header .user-actions a i { margin-inline-end: 0.4rem; }
        .standalone-invoice-header .user-actions a:hover { color: var(--invoicepage-secondary-color); }

        .my-invoices-title-section { padding: 1rem 0 2rem; text-align: center; margin-bottom: 2rem; }
        .my-invoices-title-section h1 { font-family: var(--invoicepage-font-secondary); font-size: 2.25rem; color: var(--invoicepage-heading-color); font-weight: 700; margin-bottom: 0.5rem; }
        .my-invoices-title-section .title-subtext { font-size: 1.05rem; color: var(--invoicepage-light-text); }

        .invoice-card {
            background-color: var(--invoicepage-card-bg);
            border-radius: var(--invoicepage-radius);
            box-shadow: var(--invoicepage-shadow);
            border: 1px solid var(--invoicepage-border-color);
            margin-bottom: 1.75rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .invoice-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }

        .invoice-card-header {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--invoicepage-border-color);
            background-color: color-mix(in srgb, var(--invoicepage-primary-color) 5%, transparent);
            border-top-left-radius: var(--invoicepage-radius);
            border-top-right-radius: var(--invoicepage-radius);
        }
        .invoice-card-header .invoice-id { font-family:var(--invoicepage-font-secondary); font-size: 1.1rem; font-weight: 700; color: var(--invoicepage-primary-color); }
        .invoice-card-header .invoice-date { font-size: 0.9rem; color: var(--invoicepage-light-text); }

        .invoice-status-badge {
            font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.9rem;
            border-radius: 50px; text-transform: capitalize;
        }
        .status-badge.paid { background-color: var(--status-paid-bg); color: var(--status-paid-text); border: 1px solid color-mix(in srgb, var(--status-paid-text) 30%, transparent); }
        .status-badge.unpaid { background-color: var(--status-unpaid-bg); color: var(--status-unpaid-text); border: 1px solid color-mix(in srgb, var(--status-unpaid-text) 30%, transparent); }
        .status-badge.cancelled { background-color: var(--status-cancelled-bg); color: var(--status-cancelled-text); border: 1px solid color-mix(in srgb, var(--status-cancelled-text) 30%, transparent); }
        .status-badge.default { background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;}


        .invoice-card-body { padding: 1.5rem; }
        .invoice-card-body .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .invoice-card-body .detail-item { font-size: 0.95rem; }
        .invoice-card-body .detail-item .label { font-weight: 600; color: var(--invoicepage-light-text); display:block; margin-bottom:0.25rem; font-size:0.85rem;}
        .invoice-card-body .detail-item .value { font-weight: 500; color: var(--invoicepage-text-color); }
        .invoice-card-body .invoice-total {
            margin-top: 1.5rem; padding-top: 1.5rem;
            border-top: 1px dashed var(--invoicepage-border-color);
            text-align: end; /* RTL: text-align: left; */
        }
        .invoice-card-body .invoice-total .total-label { font-size:1rem; color:var(--invoicepage-light-text); }
        .invoice-card-body .invoice-total .total-amount { font-family: var(--invoicepage-font-secondary); font-size: 1.75rem; font-weight: 700; color: var(--invoicepage-primary-color); margin-inline-start: 0.5rem;}
        .invoice-card-body .invoice-total .total-amount small { font-size:0.6em; font-weight:500;}

        .invoice-card-footer {
            padding: 1rem 1.5rem;
            background-color: color-mix(in srgb, var(--invoicepage-bg-color) 50%, white);
            border-top: 1px solid var(--invoicepage-border-color);
            text-align: end; /* RTL: text-align: left; */
            border-bottom-left-radius: var(--invoicepage-radius);
            border-bottom-right-radius: var(--invoicepage-radius);
        }
        .invoice-card-footer .btn { font-size:0.9rem; padding: 0.5rem 1.25rem; }
        .invoice-card-footer .btn-outline-primary { color:var(--invoicepage-primary-color); border-color:var(--invoicepage-primary-color); }
        .invoice-card-footer .btn-outline-primary:hover { background-color:var(--invoicepage-primary-color); color:white; }


        .empty-invoices-state { text-align: center; padding: 3rem 1.5rem; background-color: var(--invoicepage-card-bg); border-radius: var(--invoicepage-radius); box-shadow: var(--invoicepage-shadow); border: 1px dashed var(--invoicepage-border-color); }
        .empty-invoices-state .empty-icon { font-size: 4rem; color: var(--invoicepage-primary-color); opacity: 0.6; margin-bottom: 1.25rem; }
        .empty-invoices-state h4 { font-family: var(--invoicepage-font-secondary); font-size: 1.5rem; color: var(--invoicepage-heading-color); margin-bottom: 0.75rem; }
        .empty-invoices-state p { font-size: 1.05rem; color: var(--invoicepage-light-text); }

        .pagination-standalone .page-item .page-link { color: var(--invoicepage-primary-color); border-radius: var(--invoicepage-radius); }
        .pagination-standalone .page-item.active .page-link { background-color: var(--invoicepage-primary-color); border-color: var(--invoicepage-primary-color); color: var(--invoicepage-card-bg); }

        .standalone-invoice-footer { text-align: center; padding: 1.75rem 0; margin-top: 3rem; background-color: var(--invoicepage-card-bg); border-top: 1px solid var(--invoicepage-border-color); font-size: 0.9rem; color: var(--invoicepage-light-text); }

        /* NotifIt custom styling */
        .notifit_container { font-family: var(--invoicepage-font-primary) !important; z-index: 99999 !important; }
        /* ... (باقي أنماط NotifIt إذا كنت تستخدم ألوانًا مختلفة هنا) ... */
    </style>
</head>
<body>

    <header class="standalone-invoice-header">
        <div class="container-fluid">
            <div class="site-branding">
                <a href="<?php echo e(route('home')); ?>" class="site-title" title="<?php echo e(trans('patients.back_to_home')); ?>">
                    <?php echo e(config('app.name', trans('patients.medical_platform'))); ?>

                </a>
            </div>
            <div class="user-actions">
                <?php if(auth()->guard('patient')->check()): ?>
                    <span class="welcome-text"><?php echo e(trans('patients.welcome_message')); ?> <strong><?php echo e($patient->name); ?></strong></span>
                    <a href="<?php echo e(route('logout.patient')); ?>"
                        onclick="event.preventDefault(); document.getElementById('logout-form-header-standalone').submit();"
                        title="<?php echo e(trans('patients.logout')); ?>">
                        <i class="fas fa-sign-out-alt"></i> <?php echo e(trans('patients.logout')); ?>

                    </a>
                    <form id="logout-form-header-standalone" action="<?php echo e(route('logout.patient')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?></form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>"><i class="fas fa-sign-in-alt"></i> <?php echo e(trans('patients.login')); ?></a>
                <?php endif; ?>
                <a href="<?php echo e(route('home')); ?>" title="<?php echo e(trans('patients.back_to_home')); ?>"><i class="fas fa-home"></i> <?php echo e(trans('patients.nav_home')); ?></a>
            </div>
        </div>
    </header>

    <main class="my-invoices-container">
        <section class="my-invoices-title-section">
            <h1 class="animate__animated animate__fadeInDown"><?php echo e(trans('patients.my_invoices_header')); ?></h1>
            <p class="title-subtext animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                <?php echo e(trans('patients.my_invoices_subheader')); ?>

            </p>
        </section>

        <?php if(session('success_notify') || session('error_notify') || session('warning_notify') || session('info_notify')): ?>
            <div class="row justify-content-center mb-4">
                <div class="col-md-10 col-lg-8">
                    <?php if(session('success_notify')): ?>
                        <div class="alert alert-success d-flex align-items-center animate__animated animate__bounceIn" role="alert">
                            <i class="fas fa-check-circle fa-lg me-2"></i><div><?php echo session('success_notify'); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="animate__animated animate__fadeInUp" data-wow-delay="0.2s">
            <?php if($invoices->isNotEmpty()): ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12">
                            <div class="invoice-card">
                                <div class="invoice-card-header">
                                    <div>
                                        <span class="invoice-id"><?php echo e(trans('patients.invoice_id_prefix')); ?> #<?php echo e($invoice->id); ?></span>
                                        <span class="invoice-date d-block"><i class="far fa-calendar-alt me-1"></i> <?php echo e(\Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('l، j F Y')); ?></span>
                                    </div>
                                    
                                </div>
                                <div class="invoice-card-body">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="label"><i class="fas fa-notes-medical me-1"></i> <?php echo e(trans('patients.service_package')); ?></span>
                                            <span class="value">
                                                <?php if($invoice->invoice_type == 1 && $invoice->Service): ?>
                                                    <?php echo e($invoice->Service->name); ?>

                                                <?php elseif($invoice->invoice_type == 2 && $invoice->Group): ?>
                                                    <?php echo e($invoice->Group->name); ?>

                                                <?php else: ?>
                                                    <?php echo e(trans('patients.unspecified_service')); ?>

                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <?php if($invoice->Doctor): ?>
                                        <div class="detail-item">
                                            <span class="label"><i class="fas fa-user-md me-1"></i> <?php echo e(trans('patients.doctor')); ?></span>
                                            <span class="value"><?php echo e($invoice->Doctor->name); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($invoice->Section): ?>
                                        <div class="detail-item">
                                            <span class="label"><i class="fas fa-clinic-medical me-1"></i> <?php echo e(trans('patients.department')); ?></span>
                                            <span class="value"><?php echo e($invoice->Section->name); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="detail-item">
                                            <span class="label"><i class="fas fa-money-bill-wave me-1"></i> <?php echo e(trans('patients.base_price')); ?></span>
                                            <span class="value"><?php echo e(number_format($invoice->price, 2)); ?> <small><?php echo e(config('app.currency_symbol', trans('patients.currency_sar'))); ?></small></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label"><i class="fas fa-percent me-1"></i> <?php echo e(trans('patients.discount')); ?></span>
                                            <span class="value"><?php echo e(number_format($invoice->discount_value, 2)); ?> <small><?php echo e(config('app.currency_symbol', trans('patients.currency_sar'))); ?></small></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label"><i class="fas fa-calculator me-1"></i> <?php echo e(trans('patients.tax_rate')); ?></span>
                                            <span class="value"><?php echo e($invoice->tax_rate); ?>%</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label"><i class="fas fa-coins me-1"></i> <?php echo e(trans('patients.tax_value')); ?></span>
                                            <span class="value"><?php echo e(number_format($invoice->tax_value, 2)); ?> <small><?php echo e(config('app.currency_symbol', trans('patients.currency_sar'))); ?></small></span>
                                        </div>
                                         <div class="detail-item">
                                            <span class="label"><i class="fas fa-cash-register me-1"></i> <?php echo e(trans('patients.payment_type')); ?></span>
                                            <span class="value fw-bold"><?php echo e($invoice->type == 1 ? trans('patients.cash') : ($invoice->type == 2 ? trans('patients.credit') : trans('patients.unspecified'))); ?></span>
                                        </div>
                                    </div>
                                    <div class="invoice-total">
                                        <span class="total-label"><?php echo e(trans('patients.total_due')); ?></span>
                                        <span class="total-amount">
                                            <?php echo e(number_format($invoice->total_with_tax, 2)); ?>

                                            <small><?php echo e(config('app.currency_symbol', trans('patients.currency_sar'))); ?></small>
                                        </span>
                                    </div>
                                </div>
                                <div class="invoice-card-footer">
                                    <?php if(Route::has('admin.Print_single_invoices')): ?>
                                        <a href="<?php echo e(route('website.invoice.print', $invoice->id)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-print me-1"></i> <?php echo e(trans('patients.print_invoice')); ?>

                                        </a>
                                    <?php endif; ?>
                                    <?php if($invoice->invoice_status == 1 && $invoice->type == 1): ?>
                                        
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($invoices->hasPages()): ?>
                    <nav class="pagination-standalone mt-4 d-flex justify-content-center" aria-label="<?php echo e(trans('patients.invoices_record_pagination_aria')); ?>">
                        <?php echo e($invoices->links('vendor.pagination.bootstrap-5')); ?>

                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-invoices-state">
                    <div class="empty-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h4><?php echo e(trans('patients.no_invoices_title')); ?></h4>
                    <p><?php echo e(trans('patients.no_invoices_message')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="standalone-invoice-footer">
        <p>© <?php echo e(date('Y')); ?> جميع الحقوق محفوظة - <?php echo e(config('app.name', 'المنصة الطبية')); ?>.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script>
        // NotifIt messages for session flash data
        <?php if(session('success_notify')): ?>
            notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-check-circle fa-lg me-2'></i><div><?php echo addslashes(session('success_notify')); ?></div></div>", type: "success", position: "center", autohide: true, timeout: 5500, zindex: 999999 });
        <?php endif; ?>
        <?php if(session('error_notify')): ?>
             notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-times-circle fa-lg me-2'></i><div><?php echo addslashes(session('error_notify')); ?></div></div>", type: "error", position: "center", autohide: true, timeout: 7500, zindex: 999999 });
        <?php endif; ?>
        <?php if(session('warning_notify')): ?>
             notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-exclamation-triangle fa-lg me-2'></i><div><?php echo addslashes(session('warning_notify')); ?></div></div>", type: "warning", position: "center", autohide: true, timeout: 6500, bgcolor: "var(--invoicepage-secondary-color)", color: "#333", zindex: 999999 });
        <?php endif; ?>
        <?php if(session('info_notify')): ?>
             notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-info-circle fa-lg me-2'></i><div><?php echo addslashes(session('info_notify')); ?></div></div>", type: "info", position: "center", autohide: true, timeout: 5500, zindex: 999999 });
        <?php endif; ?>
    </script>
</body>
</html>
<?php /**PATH H:\Hospital_Sys\resources\views/WebSite/invoices/my_invoices_standalone.blade.php ENDPATH**/ ?>