{{-- KPI tile. tone: primary | success | warning | danger | info | purple --}}
@props(['label', 'value', 'icon' => 'fa-chart-line', 'tone' => 'primary', 'hint' => null, 'href' => null])

@php($tag = $href ? 'a' : 'div')
<{{ $tag }} @if ($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => 'hms-stat hms-tone-' . $tone]) }}>
    <span class="hms-stat__icon"><i class="fas {{ $icon }}"></i></span>
    <span class="hms-stat__body">
        <span class="hms-stat__label">{{ $label }}</span>
        <span class="hms-stat__value">{{ $value }}</span>
        @if ($hint)
            <span class="hms-stat__hint">{{ $hint }}</span>
        @endif
    </span>
</{{ $tag }}>
