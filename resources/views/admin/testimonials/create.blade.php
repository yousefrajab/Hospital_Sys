@extends('Dashboard.layouts.master')

@section('title', trans('AdminSb.add_testimonial'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ trans('AdminSb.add_testimonial') }}</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">{{ trans('AdminSb.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}">{{ trans('AdminSb.comments_list') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('AdminSb.add_testimonial') }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.testimonials.store') }}" method="POST">
                            @csrf
                            @include('admin.testimonials._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
