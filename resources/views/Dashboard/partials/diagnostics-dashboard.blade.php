{{-- Home page for radiology ($kind = 'ray') and laboratory ($kind = 'lab') staff. Data: DiagnosticsDashboard::build(). --}}
@php
    $greetingKey = now()->hour < 12 ? 'ui.dash.greeting_morning' : 'ui.dash.greeting_evening';
    $user = $kind === 'ray' ? auth('ray_employee')->user() : auth('laboratorie_employee')->user();
    $icon = $kind === 'ray' ? 'fa-x-ray' : 'fa-vials';
@endphp

<x-hms.page-header :title="trans($greetingKey, ['name' => ($user->name ?? '')])" :subtitle="trans('ui.dash.' . $kind . '_subtitle') . ' · ' . now()->translatedFormat('l j F Y')">
    <a href="{{ route($routes['done']) }}" class="btn btn-outline-primary"><i class="fas fa-check-double me-1"></i> {{ trans('ui.dash.completed_list') }}</a>
    <a href="{{ route($routes['queue']) }}" class="btn btn-primary"><i class="fas {{ $icon }} me-1"></i> {{ trans('ui.dash.queue') }}</a>
</x-hms.page-header>

<div class="hms-grid hms-grid--stats">
    <x-hms.stat :label="trans('ui.dash.pending_tests')" :value="$kpis['pending']" icon="fa-hourglass-half" tone="warning" :href="route($routes['queue'])" />
    <x-hms.stat :label="trans('ui.dash.done_today')" :value="$kpis['done_today']" icon="fa-check-circle" tone="success" :href="route($routes['done'])" />
    <x-hms.stat :label="trans('ui.dash.done_month')" :value="$kpis['done_month']" icon="fa-calendar-check" :href="route($routes['done'])" />
    <x-hms.stat :label="trans('ui.dash.done_total')" :value="$kpis['done_total']" :icon="$icon" tone="info" />
</div>

<div class="hms-grid hms-grid--2">
    <x-hms.card :title="trans('ui.dash.queue')" icon="fa-list-ol" :href="route($routes['queue'])" flush>
        @if ($queue->isEmpty())
            <x-hms.empty icon="fa-check-circle" :text="trans('ui.dash.no_queue')" />
        @else
            <ul class="hms-list">
                @foreach ($queue as $item)
                    <li class="hms-list__item">
                        <x-hms.avatar :name="optional($item->patient)->name" />
                        <div class="hms-list__main">
                            <span class="hms-list__title">{{ optional($item->patient)->name ?? '—' }}</span>
                            <span class="hms-list__meta">
                                {{ trans('ui.dash.requested_by', ['name' => optional($item->doctor)->name ?? '—']) }} · {{ $item->created_at->diffForHumans() }}
                                @if ($item->description)
                                    · {{ \Illuminate\Support\Str::limit($item->description, 40) }}
                                @endif
                            </span>
                        </div>
                        <a href="{{ route($routes['process'], $item->id) }}" class="btn btn-sm btn-primary">{{ trans('ui.dash.process') }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-hms.card>

    <x-hms.card :title="trans('ui.dash.my_recent')" icon="fa-history" :href="route($routes['done'])" flush>
        @if ($recent->isEmpty())
            <x-hms.empty :icon="$icon" :text="trans('ui.dash.no_recent')" />
        @else
            <ul class="hms-list">
                @foreach ($recent as $item)
                    <li class="hms-list__item">
                        <div class="hms-list__main">
                            <span class="hms-list__title">{{ optional($item->patient)->name ?? '—' }}</span>
                            <span class="hms-list__meta">{{ $item->updated_at->diffForHumans() }}</span>
                        </div>
                        <span class="hms-pill hms-tone-success">{{ trans('ui.dash.completed') }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-hms.card>
</div>

<x-hms.card :title="trans('ui.dash.tests_trend')" icon="fa-chart-area">
    <x-hms.chart type="area" :labels="$trend['labels']" :series="$trend['series']" :tones="['warning', 'success']" />
</x-hms.card>
