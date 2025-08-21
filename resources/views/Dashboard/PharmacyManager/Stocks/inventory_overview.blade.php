@extends('Dashboard.layouts.master')
@section('title', 'نظرة عامة على المخزون')
{{-- CSS and JS similar to other index pages --}}
@section('page-header')
    <h4><i class="fas fa-boxes-stacked"></i> {{ trans('phrM.medication_stock_overview') }}</h4>
@endsection
@section('content')
    {{-- يمكنك إضافة فلاتر هنا --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ trans('phrM.medication_name') }}</th>
                        <th>{{ trans('phrM.total_available_quantity_col') }}</th>
                        <th>{{ trans('phrM.nearest_expiry_date_col') }}</th>
                        <th>{{ trans('phrM.reorder_level') }}</th>
                        <th>{{ trans('phrM.status') }}</th>
                        <th>{{ trans('phrM.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($medications as $medication)
                        <tr>
                            <td>{{ $medication->name }}</td>
                            <td class="fw-bold">{{ $medication->total_quantity ?? 0 }}</td>
                            <td>
                                @if($medication->stocks->isNotEmpty() && $medication->stocks->first()->expiry_date)
                                    {{ \Carbon\Carbon::parse($medication->stocks->first()->expiry_date)->format('Y-m-d') }}
                                    @if(\Carbon\Carbon::parse($medication->stocks->first()->expiry_date)->isPast())
                                        <span class="badge bg-danger">{{ trans('phrM.expired') }}</span>
                                    @elseif(\Carbon\Carbon::parse($medication->stocks->first()->expiry_date)->isBefore(now()->addMonths(3)))
                                        <span class="badge bg-warning text-dark">{{ trans('phrM.expiring_soon') }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $medication->minimum_stock_level }}</td>
                            <td>
                                @if(($medication->total_quantity ?? 0) <= $medication->minimum_stock_level)
                                    <span class="badge bg-danger-soft">{{ trans('phrM.low_stock') }}</span>
                                @else
                                    <span class="badge bg-success-soft">{{ trans('phrM.in_stock') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pharmacy_manager.stocks.index', $medication->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> {{ trans('phrM.view_batches') }}
                                </a>
                                 <a href="{{ route('pharmacy_manager.stocks.create', $medication->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i> {{ trans('phrM.add_batch') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">{{ trans('phrM.no_meds_to_display_stock') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $medications->links() }}
            </div>
        </div>
    </div>
@endsection
