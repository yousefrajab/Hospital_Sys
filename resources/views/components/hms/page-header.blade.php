{{-- Page title row. Usage: <x-hms.page-header :title="..." :subtitle="..."> action buttons </x-hms.page-header> --}}
@props(['title', 'subtitle' => null])

<div class="hms-page-header">
    <div>
        <h1 class="hms-page-header__title">{{ $title }}</h1>
        @if ($subtitle)
            <p class="hms-page-header__subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @if (trim($slot) !== '')
        <div class="hms-page-header__actions">{{ $slot }}</div>
    @endif
</div>
