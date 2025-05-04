@extends('Dashboard.layouts.master')

@section('css')

    @include('Style.Style')
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between bg-gradient-primary">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto" style="color: white">{{ $section->name }}</h4>
                <span class="mt-1 tx-13 mr-2 mb-0" style="color: white"> / {{ trans('sections_trans.section_doctors') }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.Sections.index') }}" class="btn btn-outline-light btn-with-icon" style="color: white">
                <i class="fas fa-arrow-left"></i> {{ trans('sections_trans.Back to Sections') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header border-bottom-0">
                    <h3 class="card-title" style="color: white">{{ trans('doctors.List of Doctors') }} / {{ $section->name }}</h3>
                    <div class="card-toolbar">
                        <span class="badge bg-primary fs-4 p-3 d-flex align-items-center"
                            style="
                            box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.1);
                            border-radius: 20px;
                            line-height: 1.5;
                        ">
                            <i class="fas fa-user-md me-2"></i>
                            <span>
                               {{ trans('sections_trans.total number') }} :
                                <strong class="fs-3">{{ $doctors->count() }}</strong>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="overflow: visible;">
                        <table class="table text-center border-0 rounded-3 shadow table-hover"
                            style="transition: all 0.3s ease-in-out;">

                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>{{ trans('doctors.name') }}</th>
                                    <th>{{ trans('doctors.national_id') }}</th>
                                    <th>{{ trans('doctors.img') }}</th>
                                    <th>{{ trans('doctors.email') }}</th>
                                    <th>{{ trans('doctors.section') }}</th>
                                    <th>{{ trans('doctors.phone') }}</th>
                                    <th>{{ trans('doctors.appointments') }}</th>
                                    <th>{{ trans('doctors.number_of_statements') }}</th>
                                    <th width="10%">{{ trans('doctors.Status') }}</th>
                                    <th width="15%">{{ trans('doctors.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doctors as $doctor)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40 symbol-light-primary mr-3">
                                                    <span class="symbol-label">
                                                        <span class="svg-icon svg-icon-md svg-icon-primary">
                                                            <i class="fas fa-user-md"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark-75 font-weight-bold">{{ $doctor->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- العمود الجديد: رقم الهوية -->
                                        <td class="font-weight-bold text-primary">
                                            {{ $doctor->national_id ?? 'غير محدد' }}
                                        </td>
                                        <td>
                                            <div class="doctor-avatar-container">
                                                @if ($doctor->image)
                                                    <img src="{{ asset('Dashboard/img/doctors/' . $doctor->image->filename) }}"
                                                        class="doctor-avatar" alt="{{ trans('doctors.img') }}"
                                                        onerror="this.src='{{ asset('Dashboard/img/doctor_default.png') }}'">
                                                @else
                                                    <img src="{{ asset('Dashboard/img/doctor_default.png') }}"
                                                        class="doctor-avatar" alt="صورة افتراضية">
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $doctor->email }}</td>
                                        <td>
                                            <span
                                                class="inline-block text-2xl font-bold text-primary bg-primary/10 rounded-xl shadow-sm">
                                                {{ $doctor->section->name }}
                                            </span>
                                        </td>

                                        <td dir="ltr">{{ $doctor->phone }}</td>
                                        <td>
                                            @foreach ($doctor->doctorappointments as $appointment)
                                                <span class="badge badge-pill badge-light-info mb-1">
                                                    {{ $appointment->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="statements-count" data-count="{{ $doctor->number_of_statements }}">
                                                <div class="count-circle">
                                                    <span class="count-number">{{ $doctor->number_of_statements }}</span>
                                                    <svg class="count-circle-bg" viewBox="0 0 36 36">
                                                        <path d="M18 2.0845
                                                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                                            fill="none" stroke="#e0e0e0" stroke-width="3" />
                                                        <path class="count-circle-fill" d="M18 2.0845
                                                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                                            fill="none" stroke="#4361ee" stroke-width="3"
                                                            stroke-dasharray="0, 100" />
                                                    </svg>
                                                </div>
                                                <span class="count-label">{{ trans('doctors.Reports') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge-status {{ $doctor->status == 1 ? 'active-status' : 'inactive-status' }}">
                                                <i class="fas fa-circle status-icon pulse me-1"
                                                    style="color: {{ $doctor->status == 1 ? '#28a745' : '#dc3545' }}"></i>
                                                {{ $doctor->status == 1 ? trans('doctors.Enabled') : trans('doctors.Not_enabled') }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                    type="button" id="dropdownMenu{{ $doctor->id }}"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-down"
                                                    aria-labelledby="dropdownMenu{{ $doctor->id }}">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.Doctors.edit', $doctor->id) }}">
                                                        <i class="fas fa-edit text-success"></i>{{ trans('doctors.modify data') }}
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#update_password{{ $doctor->id }}">
                                                        <i class="fas fa-key text-primary"></i> {{ trans('doctors.update_password') }}
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#update_status{{ $doctor->id }}">
                                                        <i class="fas fa-power-off text-warning"></i> {{ trans('doctors.Status_change') }}
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#" data-toggle="modal"
                                                        data-target="#delete{{ $doctor->id }}">
                                                        <i class="fas fa-trash-alt"></i> {{ trans('doctors.delete_doctor') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @include('Dashboard.Doctors.delete')
                                    @include('Dashboard.Doctors.delete_select')
                                    @include('Dashboard.Doctors.update_password')
                                    @include('Dashboard.Doctors.update_status')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('Script.Script')
@endsection
