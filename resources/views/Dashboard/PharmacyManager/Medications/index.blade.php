@extends('Dashboard.layouts.master') {{-- أو layout الصيدلي إذا كان مختلفًا --}}

@section('title', 'إدارة قائمة الأدوية')

@section('css')
    @parent {{-- لاستيراد CSS الأساسي من الـ layout --}}
    {{-- NotifIt للإشعارات --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- Select2 للـ dropdowns --}}
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --admin-primary: #4f46e5;
            /* Indigo-600 */
            --admin-primary-dark: #4338ca;
            /* Indigo-700 */
            --admin-primary-light: #c7d2fe;
            --admin-secondary: #10b981;
            /* Emerald-600 */
            --admin-success: #22c55e;
            /* Green-500 */
            --admin-danger: #ef4444;
            /* Red-500 */
            --admin-warning: #f59e0b;
            /* Amber-500 */
            --admin-info: #3b82f6;
            /* Blue-500 */
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text: #1f2937;
            /* Gray-800 */
            --admin-text-secondary: #6b7280;
            /* Gray-500 */
            --admin-border-color: #e5e7eb;
            /* Gray-200 */
            --admin-input-border: #d1d5db;
            /* Gray-300 */
            --admin-radius-md: 0.375rem;
            /* 6px */
            --admin-radius-lg: 0.5rem;
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px -1px rgba(0, 0, 0, 0.07);
            --admin-shadow-md: 0 4px 8px -1px rgba(0, 0, 0, 0.07), 0 2px 6px -2px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --bs-primary-rgb: 79, 70, 229;
            /* لـ Bootstrap 5 shadow/focus colors */
            --admin-success-rgb: 34, 197, 94;
            --admin-danger-rgb: 239, 68, 68;
            --admin-warning-rgb: 245, 158, 11;
            --admin-info-rgb: 59, 130, 246;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827;
                --admin-card-bg: #1f2937;
                --admin-text: #e5e7eb;
                --admin-text-secondary: #9ca3af;
                --admin-border-color: #374151;
                --admin-input-border: #4b5563;
                --admin-primary: #6366f1;
                --admin-primary-dark: #4f46e5;
                --admin-secondary: #34d399;
            }

            .table thead th {
                background-color: #2d3748;
                border-color: var(--admin-border-color);
                color: var(--admin-text-secondary);
            }

            .table tbody tr:hover {
                background-color: rgba(99, 102, 241, 0.08);
            }

            .card {
                border-color: var(--admin-border-color);
                background-color: var(--admin-card-bg);
            }

            .form-control,
            .form-select,
            .select2-container--bootstrap-5 .select2-selection {
                background-color: #2d3748 !important;
                border-color: var(--admin-input-border) !important;
                color: var(--admin-text) !important;
            }

            .select2-container--bootstrap-5 .select2-dropdown {
                background-color: #2d3748;
                border-color: var(--admin-input-border);
            }

            .select2-container--bootstrap-5 .select2-results__option {
                color: var(--admin-text);
            }

            .select2-container--bootstrap-5 .select2-results__option--highlighted {
                background-color: var(--admin-primary) !important;
            }

            .page-item .page-link {
                background-color: var(--admin-card-bg);
                border-color: var(--admin-border-color);
                color: var(--admin-text-secondary);
            }

            .page-item.active .page-link {
                background-color: var(--admin-primary);
                border-color: var(--admin-primary);
            }

            .page-item.disabled .page-link {
                background-color: #4b5563;
                border-color: #6b7280;
                color: #9ca3af;
            }
        }

        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', sans-serif;
            color: var(--admin-text);
        }

        .card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border-color);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--admin-border-color);
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header .card-title {
            font-weight: 600;
            color: var(--admin-text);
            margin-bottom: 0;
            font-size: 1.1rem;
        }

        .card-header .card-title i {
            margin-left: 0.5rem;
            color: var(--admin-primary);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            color: var(--admin-text);
        }

        .table thead th {
            background-color: var(--admin-bg);
            color: var(--admin-text-secondary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--admin-border-color);
            white-space: nowrap;
            padding: 0.85rem 1rem;
            text-align: right;
        }

        .table td,
        .table th {
            vertical-align: middle;
            padding: 0.85rem 1rem;
            border-top: 1px solid var(--admin-border-color);
            text-align: right;
            font-size: 0.9rem;
        }

        .table tbody tr:hover {
            background-color: rgba(var(--admin-primary-rgb), 0.04);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.75em;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-light-success {
            background-color: rgba(var(--admin-success-rgb), 0.1);
            color: var(--admin-success) !important;
            border: 1px solid rgba(var(--admin-success-rgb), 0.2);
        }

        .badge-light-danger {
            background-color: rgba(var(--admin-danger-rgb), 0.1);
            color: var(--admin-danger) !important;
            border: 1px solid rgba(var(--admin-danger-rgb), 0.2);
        }

        .badge-light-warning {
            background-color: rgba(var(--admin-warning-rgb), 0.15);
            color: #92400e !important;
        }

        .badge-light-info {
            background-color: rgba(var(--admin-info-rgb), 0.1);
            color: var(--admin-info) !important;
        }

        .action-buttons .btn {
            margin: 0 2px;
            padding: 0.35rem 0.6rem;
            font-size: 0.8rem;
        }

        .action-buttons .btn i {
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-input-border);
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--admin-primary-rgb), 0.15);
        }

        .select2-container--bootstrap-5 .select2-selection {
            border-radius: var(--admin-radius-md) !important;
            border: 1px solid var(--admin-input-border) !important;
            padding: 0.47rem 0.75rem !important;
            min-height: calc(1.5em + 1rem + 2px) !important;
            font-size: 0.9rem !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1rem) !important;
        }

        .pagination-container .pagination {
            margin-bottom: 0;
        }

        .page-item .page-link {
            border-radius: var(--admin-radius-md) !important;
            margin: 0 2px;
            border-color: var(--admin-border-color);
            color: var(--admin-text-secondary);
            background-color: var(--admin-card-bg);
            transition: var(--admin-transition);
            font-size: 0.9rem;
        }

        .page-item .page-link:hover {
            background-color: var(--admin-bg);
            border-color: #cbd5e1;
            color: var(--admin-text);
        }

        .page-item.active .page-link {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            color: white;
            box-shadow: 0 2px 5px rgba(var(--admin-primary-rgb), 0.3);
        }

        .page-item.disabled .page-link {
            background-color: var(--admin-bg);
            border-color: var(--admin-border-color);
            color: #cbd5e1;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-pills fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">{{ trans('phrM.pharmacy') }}</h4>
                    <span class="text-muted mt-0 tx-13">/ {{ trans('phrM.manage_medications_list') }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('pharmacy_manager.medications.create') }}" class="btn btn-primary ripple">
                <i class="fas fa-plus-circle me-1"></i> {{ trans('phrM.add_new_medication') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>{{ trans('phrM.filter_medications_search') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pharmacy_manager.medications.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search_medication" class="form-label">{{ trans('phrM.search_by_name_generic_barcode') }}</label>
                        <input type="text" name="search_medication" id="search_medication" class="form-control"
                            placeholder="{{ trans('phrM.search_placeholder') }}" value="{{ $request->search_medication }}">
                    </div>
                    <div class="col-md-3">
                        <label for="category_filter" class="form-label">{{ trans('phrM.category') }}</label>
                        <select name="category_filter" id="category_filter" class="form-select select2"
                            data-placeholder="{{ trans('phrM.all_placeholder') }}">
                            <option value="">{{ trans('phrM.all_placeholder') }}</option>
                            @if (isset($categories))
                                @foreach ($categories as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $request->category_filter == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status_filter" class="form-label">{{ trans('phrM.status') }}</label>
                        <select name="status_filter" id="status_filter" class="form-select select2" data-placeholder="{{ trans('phrM.all_placeholder') }}">
                            <option value="">{{ trans('phrM.all_placeholder') }}</option>
                            @if (isset($statuses))
                                @foreach ($statuses as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $request->status_filter == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i>
                            {{ trans('phrM.apply') }}</button>
                        @if (request()->hasAny(['search_medication', 'category_filter', 'status_filter']))
                            <a href="{{ route('pharmacy_manager.medications.index') }}"
                                class="btn btn-outline-secondary"><i class="fas fa-eraser me-1"></i> {{ trans('phrM.clear') }}</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-tablets me-2"></i>{{ trans('phrM.registered_medications_list', ['count' => $medications->total()]) }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('phrM.medication_name') }}</th>
                            <th>{{ trans('phrM.generic_name') }}</th>
                            <th>{{ trans('phrM.category') }}</th>
                            <th>{{ trans('phrM.dosage_form') }}</th>
                            <th>{{ trans('phrM.strength') }}</th>
                            <th>{{ trans('phrM.unit_of_measure') }}</th>
                            <th>{{ trans('phrM.reorder_level') }}</th>
                            <th>{{ trans('phrM.status') }}</th>
                            <th class="text-center">{{ trans('phrM.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medications as $index => $medication)
                            <tr>
                                <td>{{ $medications->firstItem() + $index }}</td>
                                <td class="fw-bold">
                                    <a href="{{ route('pharmacy_manager.medications.show', $medication->id) }}">
                                        {{ $medication->name }}
                                    </a>
                                </td>
                                <td>{{ $medication->generic_name ?: '-' }}</td>
                                <td>{{ isset($categories[$medication->category]) ? $categories[$medication->category] : ($medication->category ?: '-') }}
                                </td>
                                <td>{{ isset($dosageForms[$medication->dosage_form]) ? $dosageForms[$medication->dosage_form] : ($medication->dosage_form ?: '-') }}
                                </td>
                                <td>{{ $medication->strength ?: '-' }}</td>
                                <td>{{ isset($unitsOfMeasure[$medication->unit_of_measure]) ? $unitsOfMeasure[$medication->unit_of_measure] : ($medication->unit_of_measure ?: '-') }}
                                </td>
                                <td>{{ $medication->minimum_stock_level }}</td>
                                <td>
                                    <span
                                        class="badge {{ $medication->status ? 'badge-light-success' : 'badge-light-danger' }}">
                                        <i
                                            class="fas {{ $medication->status ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                        {{ $medication->status ? trans('phrM.active') : trans('phrM.inactive') }}
                                    </span>
                                </td>
                                <td class="text-center action-buttons">
                                    <a href="{{ route('pharmacy_manager.medications.stocks.index', $medication->id) }}"
                                        class="btn btn-sm btn-outline-info" title="{{ trans('phrM.manage_stock_batches') }}">
                                        <i class="fas fa-boxes-stacked"></i>
                                    </a>
                                    <a href="{{ route('pharmacy_manager.medications.show', $medication->id) }}"
                                        class="btn btn-sm btn-outline-success" title="{{ trans('phrM.view_details') }}"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{ route('pharmacy_manager.medications.edit', $medication->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="{{ trans('phrM.edit_medication') }}"><i
                                            class="fas fa-edit"></i></a>
                                    <form action="{{ route('pharmacy_manager.medications.destroy', $medication->id) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('{{ trans('phrM.confirm_delete_message') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ trans('phrM.delete_medication') }}"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-capsules fa-2x text-muted mb-2"></i><br>
                                    {{ trans('phrM.no_medications_registered') }}
                                    @if (!request()->hasAny(['search_medication', 'category_filter', 'status_filter']))
                                        <br> <a href="{{ route('pharmacy_manager.medications.create') }}"
                                            class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i> {{ trans('phrM.add_new_medication') }}</a>
                                    @else
                                        <br> <span class="text-muted">{{ trans('phrM.try_adjusting_filters') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($medications->hasPages())
                <div class="mt-3 d-flex justify-content-center pagination-container">
                    {{ $medications->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('#category_filter, #status_filter, #dosage_form_filter').select2({ // أضف ID الفلاتر الجديدة هنا
                placeholder: $(this).data('placeholder') || "{{ trans('AdminSb.View All') }}",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true
            });

            // إظهار رسائل NotifIt
            @if (session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "bottom",
                    autohide: true,
                    timeout: 5000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                    type: "error",
                    position: "bottom",
                    autohide: true,
                    timeout: 7000
                });
            @endif
        });
    </script>
@endsection
