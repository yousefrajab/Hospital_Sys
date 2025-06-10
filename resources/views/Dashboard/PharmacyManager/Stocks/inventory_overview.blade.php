@extends('Dashboard.layouts.master')
@section('title', 'نظرة عامة على المخزون')
{{-- CSS and JS similar to other index pages --}}
@section('page-header')
    <h4><i class="fas fa-boxes-stacked"></i> نظرة عامة على مخزون الأدوية</h4>
@endsection
@section('content')
    {{-- يمكنك إضافة فلاتر هنا --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الدواء</th>
                        <th>الكمية الإجمالية المتوفرة</th>
                        <th>أقرب تاريخ انتهاء صلاحية</th>
                        <th>حد الطلب</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
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
                                        <span class="badge bg-danger">منتهي</span>
                                    @elseif(\Carbon\Carbon::parse($medication->stocks->first()->expiry_date)->isBefore(now()->addMonths(3)))
                                        <span class="badge bg-warning text-dark">قارب على الانتهاء</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $medication->minimum_stock_level }}</td>
                            <td>
                                @if(($medication->total_quantity ?? 0) <= $medication->minimum_stock_level)
                                    <span class="badge bg-danger-soft">منخفض المخزون</span>
                                @else
                                    <span class="badge bg-success-soft">متوفر</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pharmacy_manager.stocks.index', $medication->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> عرض الدفعات
                                </a>
                                 <a href="{{ route('pharmacy_manager.stocks.create', $medication->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i> إضافة دفعة
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">لا توجد أدوية لعرض مخزونها.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $medications->links() }}
            </div>
        </div>
    </div>
@endsection
