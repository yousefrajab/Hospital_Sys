@extends('Dashboard.layouts.master')

@section('title', 'إدارة آراء المرضى')
@section('content')
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">{{ trans('AdminSb.patient_reviews_and_comments') }}</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('dashboard.admin') }}">{{ trans('AdminSb.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('AdminSb.comments_management') }}</li>
                            </ol>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ trans('AdminSb.comments_list') }}</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ trans('AdminSb.patient_name') }}</th>
                                        <th>{{ trans('AdminSb.comment') }}</th>
                                        <th>{{ trans('AdminSb.status') }}</th>
                                        <th>{{ trans('AdminSb.creation_date') }}</th>
                                        <th>{{ trans('AdminSb.approval_rejection_date') }}</th>
                                        <th>{{ trans('AdminSb.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($testimonials as $testimonial)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $testimonial->patient_name }}</td>
                                            <td>{{ Str::limit($testimonial->comment, 100) }}</td>
                                            <td>
                                                @if ($testimonial->status == 'approved')
                                                    <span class="badge bg-success">{{ trans('AdminSb.approved') }}</span>
                                                @elseif ($testimonial->status == 'pending')
                                                    <span
                                                        class="badge bg-warning text-dark">{{ trans('AdminSb.pending_review') }}</span>
                                                @elseif ($testimonial->status == 'rejected')
                                                    <span class="badge bg-danger">{{ trans('AdminSb.rejected') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $testimonial->created_at->translatedFormat('d M Y, h:i A') }}</td>
                                            <td>
                                                @if ($testimonial->status == 'approved' && $testimonial->approved_at)
                                                    {{ $testimonial->approved_at->translatedFormat('d M Y, h:i A') }}
                                                @elseif ($testimonial->status == 'rejected' && $testimonial->updated_at)
                                                    {{ $testimonial->updated_at->translatedFormat('d M Y, h:i A') }}
                                                    ({{ trans('AdminSb.rejected_short') }})
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($testimonial->status == 'pending')
                                                    <form
                                                        action="{{ route('admin.testimonials.approve', $testimonial->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-soft-success"
                                                            title="{{ trans('AdminSb.approve') }}"><i
                                                                class="fas fa-check"></i></button>
                                                    </form>
                                                    <form
                                                        action="{{ route('admin.testimonials.reject', $testimonial->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-soft-danger"
                                                            title="{{ trans('AdminSb.reject') }}"><i
                                                                class="fas fa-times"></i></button>
                                                    </form>
                                                @elseif ($testimonial->status == 'approved')
                                                    <form
                                                        action="{{ route('admin.testimonials.reject', $testimonial->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-soft-warning"
                                                            title="{{ trans('AdminSb.change_to_rejected') }}"><i
                                                                class="fas fa-undo"></i>
                                                            {{ trans('AdminSb.reject') }}</button>
                                                    </form>
                                                @elseif ($testimonial->status == 'rejected')
                                                    <form
                                                        action="{{ route('admin.testimonials.approve', $testimonial->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-soft-info"
                                                            title="{{ trans('AdminSb.change_to_approved') }}"><i
                                                                class="fas fa-undo"></i>
                                                            {{ trans('AdminSb.approve') }}</button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('{{ trans('AdminSb.confirm_delete_comment') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-soft-danger"
                                                        title="{{ trans('AdminSb.delete') }}"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                {{ trans('AdminSb.no_comments_to_display') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div><!--end /table-responsive-->
                        <div class="mt-3">
                            {{ $testimonials->links() }}
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
@endsection

@section('css')
    {{-- يمكنك إضافة أي ستايلات خاصة هنا إذا احتجت --}}
    <style>
        .btn-soft-success {
            background-color: rgba(40, 199, 111, 0.1);
            color: #28c76f !important;
            border-color: transparent;
        }

        .btn-soft-success:hover {
            background-color: #28c76f;
            color: #fff !important;
        }

        .btn-soft-danger {
            background-color: rgba(234, 84, 85, 0.1);
            color: #ea5455 !important;
            border-color: transparent;
        }

        .btn-soft-danger:hover {
            background-color: #ea5455;
            color: #fff !important;
        }

        .btn-soft-primary {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff !important;
            border-color: transparent;
        }

        .btn-soft-primary:hover {
            background-color: #007bff;
            color: #fff !important;
        }

        .btn-soft-warning {
            background-color: rgba(255, 159, 67, 0.1);
            color: #ff9f43 !important;
            border-color: transparent;
        }

        .btn-soft-warning:hover {
            background-color: #ff9f43;
            color: #fff !important;
        }

        .btn-soft-info {
            background-color: rgba(0, 207, 232, 0.1);
            color: #00cfe8 !important;
            border-color: transparent;
        }

        .btn-soft-info:hover {
            background-color: #00cfe8;
            color: #fff !important;
        }

        .badge.bg-success {
            background-color: #28c76f !important;
        }

        .badge.bg-warning {
            background-color: #ff9f43 !important;
        }

        .badge.bg-danger {
            background-color: #ea5455 !important;
        }
    </style>
@endsection
