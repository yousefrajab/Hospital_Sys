{{--
    ApexCharts chart drawn by public/hms/js/hms.js, coloured from the theme and redrawn on light/dark switch.
    type: area | bar | donut | line ...   series: Apex series array   labels: x categories or donut labels
    tones: theme colour names in series order (primary, success, warning, danger, info, purple, muted)
--}}
@props(['type' => 'area', 'series' => [], 'labels' => [], 'height' => 280, 'tones' => ['primary'], 'stacked' => false, 'empty' => null])

@php($hasData = collect($series)->flatten()->filter(fn ($v) => is_numeric($v) && $v != 0)->isNotEmpty())
@if ($hasData)
    <div class="hms-chart" style="min-height: {{ $height }}px"
        data-hms-chart="{{ json_encode(['type' => $type, 'series' => $series, 'labels' => $labels, 'height' => (int) $height, 'tones' => $tones, 'stacked' => (bool) $stacked], JSON_UNESCAPED_UNICODE) }}"></div>
@else
    <x-hms.empty icon="fa-chart-area" :text="$empty ?? trans('ui.dash.no_data')" />
@endif

@once
    @push('hms-scripts')
        <script src="{{ asset('vendor/hms/apexcharts/apexcharts.min.js') }}"></script>
    @endpush
@endonce
