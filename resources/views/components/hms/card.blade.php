{{-- Content card with a title row. `flush` removes body padding (for tables and lists). --}}
@props(['title' => null, 'icon' => null, 'href' => null, 'linkLabel' => null, 'flush' => false])

<section {{ $attributes->merge(['class' => 'hms-card']) }}>
    @if ($title)
        <header class="hms-card__head">
            <h2 class="hms-card__title">
                @if ($icon)<i class="fas {{ $icon }}"></i>@endif
                {{ $title }}
            </h2>
            @if ($href)
                <a href="{{ $href }}" class="hms-card__link">{{ $linkLabel ?? trans('ui.view_all') }}</a>
            @endif
        </header>
    @endif
    <div class="{{ $flush ? 'hms-card__body--flush' : 'hms-card__body' }}">
        {{ $slot }}
    </div>
</section>
