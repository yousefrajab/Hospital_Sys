{{-- Appointment status pill. $value is the Arabic status string stored in appointments.type. --}}
@props(['value'])

@php
    $map = [
        \App\Models\Appointment::STATUS_PENDING => ['pending', 'warning'],
        \App\Models\Appointment::STATUS_CONFIRMED => ['confirmed', 'primary'],
        \App\Models\Appointment::STATUS_COMPLETED => ['completed', 'success'],
        \App\Models\Appointment::STATUS_LAPSED => ['lapsed', 'muted'],
        \App\Models\Appointment::STATUS_CANCELLED => ['cancelled', 'danger'],
        \App\Models\Appointment::STATUS_CANCELLED_BY_PATIENT => ['cancelled', 'danger'],
        \App\Models\Appointment::STATUS_CANCELLED_BY_DOCTOR => ['cancelled', 'danger'],
    ];
    [$key, $tone] = $map[$value] ?? [null, 'muted'];
@endphp
<span class="hms-pill hms-tone-{{ $tone }}">{{ $key ? trans('ui.status.' . $key) : $value }}</span>
