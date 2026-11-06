@extends('Dashboard.layouts.master')
@section('title', trans('ui.roles.pharmacy_manager'))

@php
    use App\Services\Dashboard\Trend;
    use Illuminate\Support\Carbon;
    $greetingKey = now()->hour < 12 ? 'ui.dash.greeting_morning' : 'ui.dash.greeting_evening';
@endphp

@section('content')
    <x-hms.page-header :title="trans($greetingKey, ['name' => (auth('pharmacy_manager')->user()->name ?? '')])" :subtitle="trans('ui.dash.manager_subtitle') . ' · ' . now()->translatedFormat('l j F Y')">
        <a href="{{ route('pharmacy_manager.medications.index') }}" class="btn btn-outline-primary"><i class="fas fa-pills me-1"></i> {{ trans('ui.dash.medications') }}</a>
        <a href="{{ route('pharmacy_manager.medications.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> {{ trans('ui.dash.add_medication') }}</a>
    </x-hms.page-header>

    <div class="hms-grid hms-grid--stats">
        <x-hms.stat :label="trans('ui.dash.active_medications')" :value="$kpis['medications']" icon="fa-pills" :href="route('pharmacy_manager.medications.index')" />
        <x-hms.stat :label="trans('ui.dash.low_stock')" :value="$kpis['low']" icon="fa-exclamation-triangle" tone="warning"
            :hint="trans('ui.dash.out_of_stock') . ': ' . $kpis['out']" />
        <x-hms.stat :label="trans('ui.dash.expiring_soon', ['days' => $warningDays])" :value="$kpis['expiring']" icon="fa-hourglass-end" tone="danger" />
        <x-hms.stat :label="trans('ui.dash.stock_value')" :value="Trend::money($kpis['stock_value'])" icon="fa-coins" tone="success" />
    </div>

    <div class="hms-grid hms-grid--2">
        <x-hms.card :title="trans('ui.dash.dispensed_30')" icon="fa-chart-area">
            <x-hms.chart type="area" :labels="$dispensedTrend['labels']" :series="[['name' => trans('ui.dash.dispensed'), 'data' => $dispensedTrend['values']]]" />
        </x-hms.card>
        <x-hms.card :title="trans('ui.dash.stock_status')" icon="fa-chart-pie">
            <x-hms.chart type="donut" :labels="$stockChart['labels']" :series="$stockChart['values']" :tones="['success', 'warning', 'danger']" />
        </x-hms.card>
    </div>

    <div class="hms-grid hms-grid--3">
        <x-hms.card :title="trans('ui.dash.low_list')" icon="fa-arrow-down" flush>
            @if ($lowList->isEmpty())
                <x-hms.empty icon="fa-check-circle" :text="trans('ui.dash.no_low')" />
            @else
                <ul class="hms-list">
                    @foreach ($lowList as $medication)
                        @php($pct = $medication->minimum_stock_level > 0 ? min(100, round((int) $medication->on_hand / $medication->minimum_stock_level * 100)) : 0)
                        <li class="hms-list__item">
                            <div class="hms-list__main">
                                <a href="{{ route('pharmacy_manager.medications.show', $medication->id) }}" class="hms-list__title">{{ $medication->name }}</a>
                                <span class="hms-list__meta">{{ trans('ui.dash.on_hand_min', ['have' => (int) $medication->on_hand, 'min' => (int) $medication->minimum_stock_level]) }}</span>
                                <div class="hms-meter__bar mt-1 {{ (int) $medication->on_hand === 0 ? 'hms-tone-danger' : 'hms-tone-warning' }}"><span style="width: {{ $pct }}%"></span></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <x-hms.card :title="trans('ui.dash.expiring_list')" icon="fa-hourglass-end" flush>
            @if ($expiringList->isEmpty())
                <x-hms.empty icon="fa-check-circle" :text="trans('ui.dash.no_expiring')" />
            @else
                <ul class="hms-list">
                    @foreach ($expiringList as $stock)
                        <li class="hms-list__item">
                            <div class="hms-list__main">
                                <span class="hms-list__title">{{ optional($stock->medication)->name ?? '—' }}</span>
                                <span class="hms-list__meta">{{ trans('ui.dash.batch', ['no' => $stock->batch_number]) }} · {{ $stock->quantity_on_hand }}</span>
                            </div>
                            <span class="hms-pill hms-tone-danger">{{ trans('ui.dash.expires', ['date' => Carbon::parse($stock->expiry_date)->translatedFormat('j M Y')]) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <x-hms.card :title="trans('ui.dash.recent_stock')" icon="fa-truck-loading" flush>
            @if ($recentStock->isEmpty())
                <x-hms.empty icon="fa-box" :text="trans('ui.dash.no_stock')" />
            @else
                <ul class="hms-list">
                    @foreach ($recentStock as $stock)
                        <li class="hms-list__item">
                            <div class="hms-list__main">
                                <span class="hms-list__title">{{ optional($stock->medication)->name ?? '—' }}</span>
                                <span class="hms-list__meta">{{ trans('ui.dash.batch', ['no' => $stock->batch_number]) }} · {{ $stock->received_date ? Carbon::parse($stock->received_date)->translatedFormat('j M Y') : '' }}</span>
                            </div>
                            <strong class="hms-list__side">{{ $stock->quantity_on_hand }}</strong>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>
    </div>
@endsection
