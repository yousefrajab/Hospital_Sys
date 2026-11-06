@props(['icon' => 'fa-inbox', 'text' => ''])

<div class="hms-empty">
    <i class="fas {{ $icon }}"></i>
    <p>{{ $text }}</p>
    {{ $slot }}
</div>
