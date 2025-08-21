<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(LaravelLocalization::getCurrentLocaleDirection()); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo e(trans('patients.my_account_page_title', ['name' => $patient->name])); ?> - <?php echo e(config('app.name', trans('patients.medical_platform'))); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />

    <style>
        :root {
            --accountpage-primary-color: <?php echo e($settings['accountpage_primary_color'] ?? '#0288D1'); ?>; /* Light Blue */
            --accountpage-primary-color-rgb: <?php echo e($settings['accountpage_primary_color_rgb'] ?? '2, 136, 209'); ?>;
            --accountpage-secondary-color: <?php echo e($settings['accountpage_secondary_color'] ?? '#FFA000'); ?>; /* Amber */
            --accountpage-text-color: #333;
            --accountpage-heading-color: #111;
            --accountpage-light-text: #555;
            --accountpage-bg-color: #f4f7f9;
            --accountpage-card-bg: #ffffff;
            --accountpage-border-color: #e0e7ff; /* Lighter border */
            --accountpage-radius: 8px;
            --accountpage-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            --accountpage-font-primary: 'Cairo', sans-serif;
            --accountpage-font-secondary: 'Tajawal', sans-serif;
        }

        html, body { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--accountpage-font-primary); background-color: var(--accountpage-bg-color); color: var(--accountpage-text-color); line-height: 1.7; font-size: 16px; }
        *, *::before, *::after { box-sizing: inherit; }

        .my-account-container { max-width: 1140px; margin: 0 auto; padding: 2.5rem 1rem; }

        .standalone-account-header { background-color: var(--accountpage-card-bg); padding: 1rem 1.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 2.5rem; }
        .standalone-account-header .container-fluid { max-width: 1140px; margin:0 auto; display: flex; justify-content: space-between; align-items: center; }
        .standalone-account-header .site-branding .site-title { font-family: var(--accountpage-font-secondary); font-size: 1.6rem; color: var(--accountpage-primary-color); margin: 0; font-weight: 700; text-decoration:none; }
        .standalone-account-header .user-actions { display:flex; align-items:center; gap:1rem;}
        .standalone-account-header .user-actions .welcome-text { color: var(--accountpage-light-text); font-size: 0.9rem;}
        .standalone-account-header .user-actions a { color: var(--accountpage-primary-color); text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: color 0.2s ease; }
        .standalone-account-header .user-actions a i { margin-inline-end: 0.4rem; }
        .standalone-account-header .user-actions a:hover { color: var(--accountpage-secondary-color); }

        .my-account-title-section { padding: 1rem 0 2rem; text-align: center; margin-bottom: 2rem; }
        .my-account-title-section h1 { font-family: var(--accountpage-font-secondary); font-size: 2.25rem; color: var(--accountpage-heading-color); font-weight: 700; margin-bottom: 0.5rem; }
        .my-account-title-section .title-subtext { font-size: 1.05rem; color: var(--accountpage-light-text); }

        .receipt-list-wrapper {
            background-color: var(--accountpage-card-bg);
            border-radius: var(--accountpage-radius);
            box-shadow: var(--accountpage-shadow);
            border: 1px solid var(--accountpage-border-color);
            overflow: hidden; /* For rounded corners on table */
        }

        .table-receipts {
            margin-bottom: 0; /* Remove default bootstrap margin */
            font-size: 0.95rem;
        }
        .table-receipts thead th {
            background-color: color-mix(in srgb, var(--accountpage-primary-color) 10%, white);
            color: var(--accountpage-primary-color);
            font-family: var(--accountpage-font-secondary);
            font-weight: 600;
            border-bottom-width: 2px;
            border-color: var(--accountpage-primary-color) !important;
            text-align: center;
            vertical-align: middle;
        }
        .table-receipts tbody tr:hover {
            background-color: color-mix(in srgb, var(--accountpage-secondary-color) 5%, transparent);
        }
        .table-receipts td {
            vertical-align: middle;
            padding: 0.85rem 1rem;
        }
        .table-receipts .receipt-id { font-weight: 600; color: var(--accountpage-primary-color); }
        .table-receipts .receipt-amount { font-weight: 700; color: var(--accountpage-secondary-color); font-size:1.05em;}
        .table-receipts .receipt-description { color: var(--accountpage-light-text); font-size:0.9em; }
        .table-receipts .btn-print-receipt {
            color: var(--accountpage-primary-color);
            border-color: var(--accountpage-primary-color);
            padding: 0.3rem 0.75rem;
            font-size: 0.8rem;
        }
        .table-receipts .btn-print-receipt:hover {
            background-color: var(--accountpage-primary-color);
            color: white;
        }

        .empty-account-state { text-align: center; padding: 3rem 1.5rem; background-color: var(--accountpage-card-bg); border-radius: var(--accountpage-radius); box-shadow: var(--accountpage-shadow); border: 1px dashed var(--accountpage-border-color); }
        .empty-account-state .empty-icon { font-size: 4rem; color: var(--accountpage-primary-color); opacity: 0.6; margin-bottom: 1.25rem; }
        .empty-account-state h4 { font-family: var(--accountpage-font-secondary); font-size: 1.5rem; color: var(--accountpage-heading-color); margin-bottom: 0.75rem; }
        .empty-account-state p { font-size: 1rem; color: var(--accountpage-light-text); }

        .pagination-standalone .page-item .page-link { color: var(--accountpage-primary-color); border-radius: var(--accountpage-radius); }
        .pagination-standalone .page-item.active .page-link { background-color: var(--accountpage-primary-color); border-color: var(--accountpage-primary-color); color: var(--accountpage-card-bg); }

        .standalone-account-footer { text-align: center; padding: 1.75rem 0; margin-top: 3rem; background-color: var(--accountpage-card-bg); border-top: 1px solid var(--accountpage-border-color); font-size: 0.9rem; color: var(--accountpage-light-text); }

        /* NotifIt custom styling */
        .notifit_container { font-family: var(--accountpage-font-primary) !important; z-index: 99999 !important; }
        /* ... (باقي أنماط NotifIt إذا كنت تستخدم ألوانًا مختلفة هنا) ... */
    </style>
</head>
<body>

  <header class="standalone-account-header">
        <div class="container-fluid">
            <div class="site-branding">
                <a href="<?php echo e(route('home')); ?>" class="site-title" title="<?php echo e(trans('patients.back_to_home_title', ['appName' => config('app.name')])); ?>">
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

    <main class="my-account-container">
        <section class="my-account-title-section">
            <h1 class="animate__animated animate__fadeInDown"><?php echo e(trans('patients.my_account_and_payments_header')); ?></h1>
            <p class="title-subtext animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                <?php echo e(trans('patients.my_account_subheader')); ?>

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

        <div class="receipt-list-wrapper animate__animated animate__fadeInUp" data-wow-delay="0.2s">
            <?php if($receipts->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-receipts">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo e(trans('patients.receipt_number_col')); ?></th>
                                <th><?php echo e(trans('patients.receipt_date_col')); ?></th>
                                <th><?php echo e(trans('patients.amount_paid_col')); ?></th>
                                <th><?php echo e(trans('patients.description_statement_col')); ?></th>
                                <th><?php echo e(trans('patients.actions_col')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration + ($receipts->currentPage() - 1) * $receipts->perPage()); ?></td>
                                    <td class="receipt-id">#<?php echo e($receipt->id); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($receipt->date)->translatedFormat('d F Y')); ?></td>
                                    <td class="receipt-amount">
                                        <?php echo e(number_format($receipt->amount, 2)); ?>

                                        <small><?php echo e(config('app.currency_symbol', trans('patients.currency_sar'))); ?></small>
                                    </td>
                                    <td class="receipt-description"><?php echo e(Str::limit($receipt->description, 70)); ?></td>
                                    <td>
                                        <?php if(Route::has('website.receipt.print')): ?>
                                        <a href="<?php echo e(route('website.receipt.print', $receipt->id)); ?>" class="btn btn-sm btn-outline-primary btn-print-receipt" target="_blank" title="<?php echo e(trans('patients.print_receipt_tooltip')); ?>">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($receipts->hasPages()): ?>
                    <div class="card-footer bg-transparent py-3">
                        <nav class="pagination-standalone d-flex justify-content-center">
                            <?php echo e($receipts->links('vendor.pagination.bootstrap-5')); ?>

                        </nav>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-account-state p-5">
                    <div class="empty-icon"><i class="fas fa-file-invoice"></i></div>
                    <h4><?php echo e(trans('patients.no_payments_or_receipts_title')); ?></h4>
                    <p><?php echo e(trans('patients.payments_will_appear_here_message')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script>
        // Script for NotifIt messages for session flash data
        <?php if(session('success_notify')): ?>
            notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-check-circle fa-lg me-2'></i><div><?php echo addslashes(session('success_notify')); ?></div></div>", type: "success", position: "center", autohide: true, timeout: 5500, zindex: 999999 });
        <?php endif; ?>
        // ... (باقي إشعارات NotifIt إذا احتجتها)
    </script>
</body>
</html>
<?php /**PATH H:\Hospital_Sys\resources\views/WebSite/account/my_account_statement_standalone.blade.php ENDPATH**/ ?>