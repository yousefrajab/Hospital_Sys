@extends('Dashboard.layouts.master')
@section('title', trans('ui.roles.patient'))

@php
    use App\Services\Dashboard\Trend;
    use Illuminate\Support\Carbon;
    $greetingKey = now()->hour < 12 ? 'ui.dash.greeting_morning' : 'ui.dash.greeting_evening';
@endphp

@section('content')
    <x-hms.page-header :title="trans($greetingKey, ['name' => $patient->name])" :subtitle="trans('ui.dash.patient_subtitle')">
        <a href="{{ route('patient.appointments.create.form') }}" class="btn btn-primary"><i class="far fa-calendar-plus me-1"></i> {{ trans('ui.dash.book_now') }}</a>
    </x-hms.page-header>

    @if ($refillDue)
        <div class="alert alert-warning d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px">
            <span><i class="fas fa-pills me-2"></i>{{ trans('ui.dash.refill_due', ['date' => Carbon::parse($refillDue->next_refill_due_date)->translatedFormat('l j F')]) }}</span>
            <a href="{{ route('patient.pharmacy.index') }}" class="btn btn-sm btn-warning">{{ trans('ui.dash.request_refill') }}</a>
        </div>
    @endif

    <div class="hms-grid hms-grid--2">
        <section class="hms-next">
            <span class="hms-next__label"><i class="far fa-calendar-check"></i> {{ trans('ui.dash.next_appointment') }}</span>
            @if ($next)
                @php($when = Carbon::parse($next->appointment))
                <div class="hms-next__body">
                    <div class="hms-next__date">
                        <strong>{{ $when->translatedFormat('j') }}</strong>
                        <span>{{ $when->translatedFormat('F') }}</span>
                    </div>
                    <div class="hms-next__info">
                        <h2>{{ trans('ui.dash.by_doctor', ['name' => optional($next->doctor)->name ?? '—']) }}</h2>
                        <p>{{ optional($next->section)->name }}</p>
                        <p><i class="far fa-clock"></i> {{ $when->translatedFormat('l · H:i') }} · {{ trans('ui.dash.in_time', ['time' => $when->diffForHumans(['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE])]) }}</p>
                        <x-hms.status :value="$next->type" />
                    </div>
                </div>
                <a href="{{ route('website.my.appointments') }}" class="hms-next__link">{{ trans('ui.dash.my_appointments') }} <i class="fas fa-arrow-left hms-flip-ltr"></i></a>
            @else
                <div class="hms-next__empty">
                    <p>{{ trans('ui.dash.no_next_appointment') }}</p>
                    <a href="{{ route('patient.appointments.create.form') }}" class="btn btn-light">{{ trans('ui.dash.book_now') }}</a>
                </div>
            @endif
        </section>

        <div class="hms-grid hms-grid--halves" style="margin: 0">
            <x-hms.stat :label="trans('ui.dash.upcoming_count')" :value="$kpis['upcoming']" icon="fa-calendar-alt" :href="route('website.my.appointments')" />
            <x-hms.stat :label="trans('ui.dash.ready_prescriptions')" :value="$kpis['ready_prescriptions']" icon="fa-prescription-bottle-alt" tone="success" :href="route('patient.pharmacy.index')" />
            <x-hms.stat :label="trans('ui.dash.balance_due')" :value="Trend::money($kpis['balance'])" icon="fa-wallet" tone="warning" :href="route('website.my.account')" />
            <x-hms.stat :label="trans('ui.dash.results_ready')" :value="$kpis['results']" icon="fa-file-medical-alt" tone="info" :href="route('rays.patient')" />
        </div>
    </div>

    <div class="hms-grid hms-grid--3">
        <x-hms.card :title="trans('ui.dash.more_upcoming')" icon="fa-calendar-week" :href="route('website.my.appointments')" flush>
            @if ($upcoming->isEmpty())
                <x-hms.empty icon="fa-calendar" :text="trans('ui.dash.no_next')" />
            @else
                <ul class="hms-list">
                    @foreach ($upcoming as $appointment)
                        <li class="hms-list__item">
                            <div class="hms-list__main">
                                <span class="hms-list__title">{{ trans('ui.dash.by_doctor', ['name' => optional($appointment->doctor)->name ?? '—']) }}</span>
                                <span class="hms-list__meta">{{ Carbon::parse($appointment->appointment)->translatedFormat('l j M · H:i') }}</span>
                            </div>
                            <x-hms.status :value="$appointment->type" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <x-hms.card :title="trans('ui.dash.my_prescriptions')" icon="fa-file-prescription" :href="route('patient.pharmacy.index')" flush>
            @if ($prescriptions->isEmpty())
                <x-hms.empty icon="fa-prescription" :text="trans('ui.dash.no_my_prescriptions')" />
            @else
                <ul class="hms-list">
                    @foreach ($prescriptions as $prescription)
                        <li class="hms-list__item">
                            <div class="hms-list__main">
                                <span class="hms-list__title">#{{ $prescription->prescription_number }}</span>
                                <span class="hms-list__meta">{{ trans('ui.dash.by_doctor', ['name' => optional($prescription->doctor)->name ?? '—']) }} · {{ Carbon::parse($prescription->prescription_date)->translatedFormat('j M Y') }}</span>
                            </div>
                            <span class="hms-pill hms-tone-primary">{{ $prescription->status_display }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <x-hms.card :title="trans('ui.dash.my_invoices')" icon="fa-file-invoice" :href="route('website.my.invoices')" flush>
            @if ($invoices->isEmpty())
                <x-hms.empty icon="fa-receipt" :text="trans('ui.dash.no_invoices')" />
            @else
                <ul class="hms-list">
                    @foreach ($invoices as $invoice)
                        <li class="hms-list__item">
                            <div class="hms-list__main">
                                <span class="hms-list__title">
                                    {{ $invoice->invoice_type == 2 ? (optional($invoice->Group)->name ?? trans('ui.dash.invoice_package')) : (optional($invoice->Service)->name ?? '—') }}
                                </span>
                                <span class="hms-list__meta">{{ Carbon::parse($invoice->invoice_date)->translatedFormat('j M Y') }} · {{ $invoice->type == 1 ? trans('ui.dash.cash') : trans('ui.dash.credit') }}</span>
                            </div>
                            <strong class="hms-list__side">{{ Trend::money($invoice->total_with_tax) }}</strong>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>
    </div>

    <x-hms.card :title="trans('ui.dash.quick_actions')" icon="fa-bolt">
        <div class="hms-quick">
            <a href="{{ route('patient.appointments.create.form') }}"><i class="far fa-calendar-plus"></i>{{ trans('ui.dash.book_now') }}</a>
            <a href="{{ route('patient.pharmacy.index') }}"><i class="fas fa-pills"></i>{{ trans('ui.dash.pharmacy') }}</a>
            <a href="{{ route('rays.patient') }}"><i class="fas fa-x-ray"></i>{{ trans('ui.dash.results') }}</a>
            <a href="{{ route('website.my.account') }}"><i class="fas fa-wallet"></i>{{ trans('ui.dash.account_statement') }}</a>
            <a href="{{ route('list.doctors') }}"><i class="far fa-comments"></i>{{ trans('ui.dash.contact_doctor') }}</a>
            <a href="{{ route('profile.show') }}"><i class="far fa-user"></i>{{ trans('ui.profile') }}</a>
        </div>
    </x-hms.card>
@endsection
