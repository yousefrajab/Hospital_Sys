@extends('Dashboard.layouts.master')

@section('css')

    @include('Style.Style')
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">{{ trans('Dashboard/main-sidebar_trans.sections') }}</h4>
                <span class="text mt-1 tx-13 mr-2 mb-0">/ {{ trans('main-sidebar_trans.view_all') }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card section-table-card">
                <div class="card-header card-header-gradient">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title mb-0 text-white me-auto">
                            <i class="fas fa-list-alt mr-2"></i>{{ trans('Dashboard/main-sidebar_trans.sections') }}
                        </h3>
                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#add">
                            <i class="fas fa-plus-circle mr-1"></i>{{ trans('Dashboard/sections_trans.add_sections') }}
                        </button>
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive" style="min-height: 400px;">
                        <table class="table table-hover table-advanced" id="sections-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('sections_trans.name_sections') }}</th>
                                    <th>{{ trans('sections_trans.description') }}</th>
                                    <th>{{ trans('sections_trans.created_at') }}</th>
                                    <th>{{ trans('sections_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sections as $section)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('admin.Sections.show', $section->id) }}" class="section-link">
                                                {{ $section->name }}
                                            </a>
                                        </td>
                                        <td>{{ \Str::limit($section->description, 50) }}</td>
                                        <td class="creation-time-cell">
                                            <div class="time-display"
                                                title="{{ $section->created_at->format('Y/m/d h:i A') }}">
                                                <small class="text-muted">
                                                    {{ $section->created_at->format('Y/m/d') }}
                                                    <span
                                                        class="time-period {{ $section->created_at->format('A') == 'AM' ? 'morning' : 'evening' }}">
                                                        {{ $section->created_at->format('A') == 'AM' ? trans('doctors.AM') : trans('doctors.PM') }}
                                                    </span>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a class="btn btn-sm btn-info mr-2" data-toggle="modal"
                                                    href="#edit{{ $section->id }}">
                                                    <i class="las la-pen"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger" data-toggle="modal"
                                                    href="#delete{{ $section->id }}">
                                                    <i class="las la-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    @include('Dashboard.Sections.edit')
                                    @include('Dashboard.Sections.delete')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('Dashboard.Sections.add')
    </div>
@endsection

@section('js')
    @include('Script.Script')
@endsection
