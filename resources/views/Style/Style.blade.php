<link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('Dashboard/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('Dashboard/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('Dashboard/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />



{{-- ------------------------------------------Add Doctor------------------------------------------- --}}

<style>
    /* Index /Single Service */
    body {
        background: #f2f6fc;
    }

    .service-card {
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        transition: 0.3s ease-in-out;
    }

    .card-header {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        color: #fff;
        padding: 1.2rem 1.5rem;
        border-radius: 20px 20px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .btn-modern {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 8px 16px;
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-modern:hover {
        background-color: rgba(255, 255, 255, 0.35);
        transform: translateY(-2px);
    }

    .table thead {
        background-color: #f1f3fa;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .action-btns a {
        margin: 0 3px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .btn-info {
        background-color: #5a67d8;
    }

    .btn-danger {
        background-color: #e53e3e;
    }
</style>
{{-- --------------index Sections----------------- --}}
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4895ef;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --danger-color: #dc3545;
        --success-color: #28a745;
    }

    .section-table-card {
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .section-table-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 15px 15px 0 0 !important;
    }

    .btn-primary,
    .btn-info,
    .btn-danger {
        transition: 0.3s ease-in-out;
    }

    .btn-primary:hover,
    .btn-info:hover,
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }

    .table thead th {
        background-color: var(--light-color);
        color: var(--dark-color);
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: rgba(72, 149, 239, 0.05);
    }

    .section-link {
        color: var(--primary-color);
        font-weight: 500;
    }

    .section-link:hover {
        text-decoration: none;
        color: var(--secondary-color);
    }
</style>

{{-- ---------------- edit Doctor--------------- --}}
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366F1, #8B5CF6);
        --secondary-gradient: linear-gradient(135deg, #06B6D4, #0EA5E9);
        --glass-effect: rgba(255, 255, 255, 0.25);
    }

    body {
        background: #F8FAFC;
        font-family: 'Inter', sans-serif;
    }

    .card-3d {
        background: white;
        border-radius: 24px;
        box-shadow:
            0 10px 25px -5px rgba(0, 0, 0, 0.1),
            0 10px 10px -5px rgba(0, 0, 0, 0.04);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-3d:hover {
        transform: translateY(-4px);
        box-shadow:
            0 20px 25px -5px rgba(0, 0, 0, 0.1),
            0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        padding-bottom: 12px;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: 2px;
    }

    .form-control-modern {
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        padding: 12px 16px;
        transition: all 0.3s;
        background: rgba(255, 255, 255, 0.7);
    }

    .form-control-modern:focus {
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        background: white;
    }


    .avatar-upload {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }

    .avatar-upload img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload label {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 40px;
        height: 40px;
        background: var(--secondary-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload label i {
        color: white;
        font-size: 18px;
    }

    .avatar-upload input[type="file"] {
        display: none;
    }

    .breadcrumb-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(67, 97, 238, 0.15);
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
    }

    /* .breadcrumb-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: white;
        padding: 15px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(8px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    } */



    .breadcrumb-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(to bottom right,
                rgba(255, 255, 255, 0.1) 0%,
                rgba(255, 255, 255, 0) 60%);
        transform: rotate(30deg);
        /* pointer-events: none; */
    }

    .breadcrumb-header:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(67, 97, 238, 0.25);
    }

    .breadcrumb-header .content-title {
        font-weight: 700;
        letter-spacing: 0.5px;
        position: relative;
        display: inline-block;
    }

    .breadcrumb-header .content-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 50px;
        height: 3px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 3px;
    }

    .breadcrumb-header .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 400;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .breadcrumb-header {
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
        }

        .breadcrumb-header .content-title {
            font-size: 1.25rem;
        }
    }


    .select2-container--bootstrap4 .select2-selection {
        border-radius: 12px !important;
        border: 1px solid #E2E8F0 !important;
        padding: 8px 12px !important;
    }

    .floating-label {
        position: relative;
        margin-bottom: 24px;
    }

    .floating-label label {
        position: absolute;
        top: -10px;
        left: 16px;
        background: white;
        padding: 0 8px;
        font-size: 13px;
        color: #6366F1;
        font-weight: 600;
    }
</style>
<style>
    /* التنسيقات الأساسية */
    .btn-custom {
        position: relative;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 150px;
        text-align: center;
    }

    /* زر الإلغاء */
    .btn-cancel {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        color: #6c757d;
        box-shadow: 0 4px 6px rgba(108, 117, 125, 0.1);
    }

    /* زر الحفظ */
    .btn-save {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        box-shadow: 0 4px 6px rgba(0, 178, 255, 0.2);
    }

    /* تأثيرات التحويم */
    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(108, 117, 125, 0.15);
        color: #5a6268;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 178, 255, 0.3);
        background: linear-gradient(135deg, #3a9ffd 0%, #00d9e9 100%);
    }

    /* تأثير النقر */
    .btn-custom:active {
        transform: translateY(1px);
    }

    /* موجة الحركة */
    .btn-wave {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%, -50%);
        transform-origin: 50% 50%;
        transition: all 0.5s ease-out;
    }

    .btn-custom:hover .btn-wave {
        opacity: 1;
        transform: scale(50, 50) translate(-50%, -50%);
    }

    /* التأخير في موجة الحركة */
    .btn-save .btn-wave {
        transition-delay: 0.1s;
    }

    /* محتوى الزر */
    .btn-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
    }

    /* المسافة بين الأزرار */
    .gap-4 {
        gap: 5rem;
    }

    /* زر العين داخل حقل كلمة المرور */
    .toggle-password-eye {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        z-index: 10;
    }

    .toggle-password-eye i {
        font-size: 18px;
        color: #8B5CF6;
    }
</style>

{{-- -------------index doctors---------------- --}}
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4895ef;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --danger-color: #dc3545;
        --success-color: #28a745;
    }

    .doctors-card {
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .doctors-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 20px;
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        color: white;
        transition: all 0.3s;
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-danger-gradient {
        background: linear-gradient(135deg, var(--danger-color), #c82333);
        border: none;
        color: white;
    }

    #doctors-table thead th {
        background-color: var(--light-color);
        color: var(--dark-color);
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }

    #doctors-table tbody tr:hover {
        background-color: rgba(72, 149, 239, 0.05);
    }

    .doctor-avatar {
        width: 45px;
        height: 65px;
        border-radius: 30%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 8px;
    }

    .bg-primary-light {
        background-color: rgba(67, 97, 238, 0.1);
    }

    .bg-secondary-light {
        background-color: rgba(108, 117, 125, 0.1);
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-secondary {
        color: #6c757d !important;
    }

    .dot-label {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    .dropdown-action {
        min-width: 180px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .dropdown-action .dropdown-item {
        padding: 8px 15px;
        font-size: 14px;
    }

    .dropdown-action .dropdown-item i {
        width: 20px;
        text-align: center;
        margin-right: 5px;
    }
</style>

<style>
    .statements-count {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .count-circle {
        position: relative;
        width: 40px;
        height: 40px;
        margin-bottom: 5px;
    }

    .count-number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
        font-weight: 600;
        color: #4361ee;
    }

    .count-circle-bg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .count-circle-fill {
        transition: stroke-dasharray 1s ease-in-out;
    }

    .count-label {
        font-size: 11px;
        color: #6c757d;
        font-weight: 500;
    }

    /* تأثير عند المرور */
    .statements-count:hover .count-number {
        color: #3a0ca3;
        transform: translate(-50%, -50%) scale(1.1);
        transition: all 0.3s ease;
    }

    .statements-count:hover .count-circle-fill {
        stroke: #3a0ca3;
    }

    /* رسم دائرة متحركة عند التحميل */
    @keyframes circle-fill {
        from {
            stroke-dasharray: 0, 100;
        }

        to {
            stroke-dasharray: var(--percentage), 100;
        }
    }
</style>
<style>
    .innovative-checkbox {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .innovative-checkbox input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .check-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 12px;
    }

    .check-icon {
        width: 24px;
        height: 24px;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        transition: all 0.3s ease;
    }

    .check-bg-circle {
        stroke: #e0e0e0;
        fill: transparent;
        transition: all 0.3s ease;
    }

    .check-mark {
        stroke: transparent;
        stroke-dasharray: 20;
        stroke-dashoffset: 20;
        transition: all 0.3s ease;
    }

    .pulse-effect {
        stroke: transparent;
        stroke-width: 0;
        fill: rgba(67, 97, 238, 0);
        transform-origin: center;
        transition: all 0.5s ease;
    }

    .check-text {
        font-size: 10px;
        margin-top: 4px;
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(5px);
    }

    /* تأثيرات عند التركيز */
    .innovative-checkbox input:focus-visible~.check-label {
        outline: 2px solid #4361ee;
        outline-offset: 2px;
    }

    /* تأثيرات عند التحديد */
    .innovative-checkbox input:checked~.check-label .check-bg-circle {
        stroke: #4361ee;
        fill: rgba(67, 97, 238, 0.1);
    }

    .innovative-checkbox input:checked~.check-label .check-mark {
        stroke: #4361ee;
        stroke-dashoffset: 0;
    }

    .innovative-checkbox input:checked~.check-label .pulse-effect {
        animation: pulse 0.6s ease-out;
    }

    /* تأثيرات عند المرور */
    .innovative-checkbox:hover .check-bg-circle {
        stroke: #b3c1f7;
    }

    .innovative-checkbox:hover .check-text {
        opacity: 1;
        transform: translateY(0);
    }

    .innovative-checkbox input:checked:hover~.check-label .check-bg-circle {
        stroke: #3a0ca3;
        fill: rgba(58, 12, 163, 0.1);
    }

    .innovative-checkbox input:checked:hover~.check-label .check-mark {
        stroke: #3a0ca3;
    }

    /* تأثير النبض */
    @keyframes pulse {
        0% {
            transform: scale(0.8);
            opacity: 1;
            stroke-width: 0;
            fill: rgba(67, 97, 238, 0.1);
        }

        50% {
            transform: scale(1.2);
            fill: rgba(67, 97, 238, 0.05);
        }

        100% {
            transform: scale(1.4);
            opacity: 0;
            fill: rgba(67, 97, 238, 0);
        }
    }

    /* تأثيرات للوضع المظلم (إذا كان المدعوم) */
    @media (prefers-color-scheme: dark) {
        .check-bg-circle {
            stroke: #4a4a4a;
        }

        .innovative-checkbox input:checked~.check-label .check-bg-circle {
            stroke: #5a7aff;
            fill: rgba(90, 122, 255, 0.1);
        }

        .innovative-checkbox input:checked~.check-label .check-mark {
            stroke: #5a7aff;
        }

        .check-text {
            color: #a0a0a0;
        }
    }
</style>

<style>
    .creation-time-cell {
        padding: 12px 16px;
    }

    .time-display {
        display: inline-block;
    }

    .time-period {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-right: 3px;
    }

    .time-period.morning {
        background-color: rgba(234, 179, 8, 0.1);
        color: #ca8a04;
    }

    .time-period.evening {
        background-color: rgba(59, 130, 246, 0.1);
        color: #1d4ed8;
    }

    /* تأثيرات للوضع المظلم */
    @media (prefers-color-scheme: dark) {
        .time-period.morning {
            background-color: rgba(234, 179, 8, 0.2);
            color: #facc15;
        }

        .time-period.evening {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }
    }
</style>
{{--  --------------Delete Sections-------------------------- --}}
<style>
    .delete-icon-container {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background-color: rgba(220, 53, 69, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .delete-icon-container i {
        font-size: 2.5rem;
        color: #dc3545;
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }

    .bg-gradient-light {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    .custom-checkbox .custom-control-label::before {
        border-radius: 0.25rem;
    }

    .custom-control-input:checked~.custom-control-label::before {
        border-color: #dc3545;
        background-color: #dc3545;
    }
</style>


{{-- ------------------------------------Add Sections------------------------------------------- --}}
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0069d9);
    }

    .bg-gradient-light {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .modal-content {
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

{{-- ------------------------------------------Show Doctors------------------------------------------------ --}}
<style>
    .dropdown-menu {
        min-width: 200px !important;
        position: absolute !important;
        inset: auto auto 0px 0px !important;
        transform: translate(0px, 34px) !important;
        z-index: 1000 !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    .table-hover tbody tr {
        transition: all 0.3s ease;
    }

    /* .table-hover tbody tr:hover {
        transform: translateX(5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    } */

    /* .dot-label {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    } */

    /* .status-badge {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    } */

    .status-active {
        background-color: #e6f7ee;
        color: #28a745;
    }

    .status-inactive {
        background-color: #fee;
        color: #dc3545;
    }

    .dropdown-item {
        white-space: nowrap !important;
        padding: 8px 15px !important;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        padding-left: 20px;
    }

    .action-icon {
        margin-left: 5px;
        font-size: 14px;
    }

    /* .doctor-avatar-container {
        width: 60px;
        height: 60px;
        margin: 0 auto;
    } */

    /* .doctor-avatar {

        height: 5%;
        object-fit: cover;
        border-radius: 20%;
        border: 2px solid #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    } */

    .doctor-avatar:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }
</style>
<style>
    .statements-count {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .count-circle {
        position: relative;
        width: 40px;
        height: 40px;
        margin-bottom: 5px;
    }

    .count-number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
        font-weight: 600;
        color: #4361ee;
    }

    .count-circle-bg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .count-circle-fill {
        transition: stroke-dasharray 1s ease-in-out;
    }

    .count-label {
        font-size: 11px;
        color: #6c757d;
        font-weight: 500;
    }

    /* تأثير عند المرور */
    .statements-count:hover .count-number {
        color: #3a0ca3;
        transform: translate(-50%, -50%) scale(1.1);
        transition: all 0.3s ease;
    }

    .statements-count:hover .count-circle-fill {
        stroke: #3a0ca3;
    }

    /* رسم دائرة متحركة عند التحميل */
    @keyframes circle-fill {
        from {
            stroke-dasharray: 0, 100;
        }

        to {
            stroke-dasharray: var(--percentage), 100;
        }
    }
</style>


{{-- -----------------------------------------Update_Status------------------- --}}

<style>
    /* Modern Styling */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
    }

    .wave-bg {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="rgba(255,255,255,0.1)"/></svg>');
        background-size: cover;
        opacity: 0.5;
    }



    .status-indicator {
        position: absolute;
        bottom: 0;
        right: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .status-toggle-container {
        position: relative;
        display: flex;
        background: #f8f9fa;
        border-radius: 50px;
        padding: 5px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .status-radio {
        display: none;
    }

    .status-label {
        flex: 1;
        text-align: center;
        padding: 10px 15px;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1;
        font-weight: 500;
    }

    .status-enabled {
        color: #28a745;
    }

    .status-disabled {
        color: #dc3545;
    }

    .status-slider {
        position: absolute;
        top: 5px;
        bottom: 5px;
        width: calc(50% - 10px);
        background: white;
        border-radius: 50px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        z-index: 0;
    }



    .submit-btn {
        position: relative;
        overflow: hidden;
    }

    .submit-btn .submit-text {
        position: relative;
        z-index: 1;
        transition: all 0.3s;
    }

    .submit-btn:hover .submit-text {
        transform: translateY(-2px);
    }
</style>

{{-- -------------------------------------------Add----------------------------------------------------- --}}


<style>
    /* Glass Morphism Effect */
    .glass-header {
        background: rgba(102, 126, 234, 0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Gradient Background Animation */
    .glass-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
        z-index: -1;
        animation: gradientAnimation 8s ease infinite;
        background-size: 200% 200%;
    }

    @keyframes gradientAnimation {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* Wave Effect */
    .wave-effect {
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 20px;
        background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="%23f8f9fa"/></svg>');
        background-size: cover;
        animation: waveAnimation 10s linear infinite;
    }

    @keyframes waveAnimation {
        0% {
            background-position-x: 0;
        }

        100% {
            background-position-x: 1200px;
        }
    }

    /* Floating Label Effect */
    .floating-label-group {
        position: relative;
        margin-bottom: 30px;
    }

    .floating-input {
        border: none;
        border-bottom: 2px solid #e0e0e0;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
        background-color: transparent;
        transition: all 0.3s;
    }

    .floating-input:focus {
        box-shadow: none;
        border-bottom-color: #667eea;
    }

    .floating-label {
        position: absolute;
        top: 10px;
        left: 0;
        /* pointer-events: none; */
        transition: all 0.3s;
        color: #999;
    }

    .floating-input:focus~.floating-label,
    .floating-input:not(:placeholder-shown)~.floating-label {
        top: -20px;
        left: 0;
        font-size: 12px;
        color: #667eea;
    }

    /* Icon Styling */
    .icon-wrapper {
        position: relative;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s;
    }

    /* Button Styling */
    .btn-rounded {
        border-radius: 50px;
        transition: all 0.4s;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-hover-transform:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    /* Currency Input Styling */
    .currency-prepend {
        background: transparent;
        border: none;
        border-bottom: 2px solid #e0e0e0;
        border-radius: 0;
        color: #667eea;
    }

    /* Light Background for Footer */
    .bg-light-5 {
        background-color: rgba(248, 249, 250, 0.5);
    }

    /* Responsive Adjustments */
    @media (max-width: 576px) {
        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            flex-direction: column;
        }

        .modal-footer .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>





<style>
    .badge-status {
        display: inline-flex;
        align-items: center;
        /* font-weight: 100; */
        padding: 12px 10px;
        border-radius: 50px;
        font-size: 15px;
        box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.05), inset -2px -2px 5px rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(4px);
    }

    .active-status {
        color: #28a745;
        border: 1px solid #28a74533;
        background: linear-gradient(135deg, #e6f4ea, #ffffff);
    }

    .inactive-status {
        color: #dc3545;
        border: 1px solid #dc354533;
        background: linear-gradient(135deg, #fce8e6, #ffffff);
    }

    .status-icon {
        font-size: 10px;
    }

    .pulse {
        animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.9;
        }

        50% {
            transform: scale(1.3);
            opacity: 1;
        }
    }
</style>
