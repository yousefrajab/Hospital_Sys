{{-- Round avatar: the photo when it exists, otherwise the person's initials. --}}
@props(['name' => '', 'src' => null, 'size' => 'md'])

@php
    $parts = array_slice(array_filter(preg_split('/\s+/u', trim((string) $name)) ?: []), 0, 2);
    // Arabic letters join together, so Arabic names get one letter; Latin names get two.
    $arabic = $parts && preg_match('/\p{Arabic}/u', mb_substr($parts[0], 0, 1));
    $initials = $arabic ? mb_substr($parts[0], 0, 1)
        : (mb_strtoupper(implode('', array_map(fn ($p) => mb_substr($p, 0, 1), $parts))) ?: '?');
    $exists = $src && is_file(public_path(ltrim(parse_url($src, PHP_URL_PATH) ?? '', '/')));
@endphp
@if ($exists)
    <img src="{{ $src }}" alt="" {{ $attributes->merge(['class' => 'hms-avatar hms-avatar--' . $size]) }}>
@else
    <span {{ $attributes->merge(['class' => 'hms-avatar hms-avatar--initials hms-avatar--' . $size]) }}>{{ $initials }}</span>
@endif
