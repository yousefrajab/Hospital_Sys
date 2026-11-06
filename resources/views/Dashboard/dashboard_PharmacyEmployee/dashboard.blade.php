@extends('Dashboard.layouts.master')
@section('title', trans('ui.roles.pharmacy_employee'))

@php
    $greetingKey = now()->hour < 12 ? 'ui.dash.greeting_morning' : 'ui.dash.greeting_evening';
@endphp

@section('content')
    <x-hms.page-header :title="trans($greetingKey, ['name' => (auth('pharmacy_employee')->user()->name ?? '')])" :subtitle="trans('ui.dash.pharmacy_subtitle') . ' · ' . now()->translatedFormat('l j F Y')">
        <a href="{{ route('pharmacy_employee.medications.search') }}" class="btn btn-outline-primary"><i class="fas fa-search me-1"></i> {{ trans('ui.dash.search_medication') }}</a>
        <a href="{{ route('pharmacy_employee.prescriptions.index') }}" class="btn btn-primary"><i class="fas fa-prescription-bottle-alt me-1"></i> {{ trans('ui.dash.dispense_queue') }}</a>
    </x-hms.page-header>

    <div class="hms-grid hms-grid--stats">
        <x-hms.stat :label="trans('ui.dash.to_dispense')" :value="$kpis['to_dispense']" icon="fa-hourglass-half" tone="warning" :href="route('pharmacy_employee.prescriptions.index')" />
        <x-hms.stat :label="trans('ui.dash.ready_pickup')" :value="$kpis['ready']" icon="fa-box-open" :href="route('pharmacy_employee.prescriptions.index')" />
        <x-hms.stat :label="trans('ui.dash.dispensed_today')" :value="$kpis['dispensed_today']" icon="fa-check-circle" tone="success" :href="route('pharmacy_employee.prescriptions.dispensed')" />
        <x-hms.stat :label="trans('ui.dash.on_hold')" :value="$kpis['on_hold']" icon="fa-pause-circle" tone="danger" :href="route('pharmacy_employee.prescriptions.on_hold')" />
        <x-hms.stat :label="trans('ui.dash.low_stock')" :value="$kpis['low_stock']" icon="fa-exclamation-triangle" tone="purple" :href="route('pharmacy_employee.medications.search')" />
    </div>

    <div class="hms-grid hms-grid--2">
        <x-hms.card :title="trans('ui.dash.dispense_queue')" icon="fa-list-ol" :href="route('pharmacy_employee.prescriptions.index')" flush>
            @if ($queue->isEmpty())
                <x-hms.empty icon="fa-check-circle" :text="trans('ui.dash.no_dispense_queue')" />
            @else
                <ul class="hms-list">
                    @foreach ($queue as $prescription)
                        <li class="hms-list__item">
                            <x-hms.avatar :name="optional($prescription->patient)->name" />
                            <div class="hms-list__main">
                                <span class="hms-list__title">{{ optional($prescription->patient)->name ?? '—' }}</span>
                                <span class="hms-list__meta">
                                    #{{ $prescription->prescription_number }} · {{ trans('ui.dash.items_count', ['count' => $prescription->items_count]) }}
                                    · {{ trans('ui.dash.by_doctor', ['name' => optional($prescription->doctor)->name ?? '—']) }}
                                </span>
                            </div>
                            <span class="hms-pill hms-tone-warning hms-hide-sm">{{ $prescription->status_display }}</span>
                            <a href="{{ route('pharmacy_employee.prescriptions.dispense.form', $prescription->id) }}" class="btn btn-sm btn-primary">{{ trans('ui.dash.dispense') }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <x-hms.card :title="trans('ui.dash.dispensed_trend')" icon="fa-chart-bar">
            <x-hms.chart type="bar" :labels="$trend['labels']" :series="[['name' => trans('ui.dash.dispensed'), 'data' => $trend['values']]]" :tones="['success']" />
        </x-hms.card>
    </div>
@endsection
