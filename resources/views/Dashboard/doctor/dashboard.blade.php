@extends('Dashboard.layouts.master')
@section('title', trans('ui.roles.doctor'))

@php
    $greetingKey = now()->hour < 12 ? 'ui.dash.greeting_morning' : 'ui.dash.greeting_evening';
@endphp

@section('content')
    <x-hms.page-header :title="trans($greetingKey, ['name' => trans('ui.dash.by_doctor', ['name' => $doctor->name])])" :subtitle="trans('ui.dash.doctor_subtitle') . ' · ' . now()->translatedFormat('l j F Y')">
        <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-primary"><i class="far fa-calendar-alt me-1"></i> {{ trans('ui.dash.my_appointments') }}</a>
        <a href="{{ route('doctor.patients.search_for_prescription') }}" class="btn btn-primary"><i class="fas fa-prescription me-1"></i> {{ trans('ui.dash.new_prescription') }}</a>
    </x-hms.page-header>

    <div class="hms-grid hms-grid--stats">
        <x-hms.stat :label="trans('ui.dash.today_appointments')" :value="$kpis['today']" icon="fa-calendar-day" :href="route('doctor.appointments')" />
        <x-hms.stat :label="trans('ui.dash.pending_requests')" :value="$kpis['pending']" icon="fa-hourglass-half" tone="warning"
            :hint="trans('ui.dash.needs_your_ok')" :href="route('doctor.appointments')" />
        <x-hms.stat :label="trans('ui.dash.visits_month')" :value="$kpis['visits_month']" icon="fa-stethoscope" tone="success" :href="route('doctor.invoices.index')" />
        <x-hms.stat :label="trans('ui.dash.refill_requests')" :value="$kpis['refills']" icon="fa-redo" tone="danger"
            :href="route('doctor.prescriptions.approvalRequests')" />
        <x-hms.stat :label="trans('ui.dash.unread_messages')" :value="$kpis['unread']" icon="fa-comment-dots" tone="info" :href="route('doctor.chat.patients')" />
        <x-hms.stat :label="trans('ui.dash.prescriptions_month')" :value="$kpis['prescriptions_month']" icon="fa-file-prescription" tone="purple" :href="route('prescriptions.index')" />
    </div>

    <div class="hms-grid hms-grid--2">
        <x-hms.card :title="trans('ui.dash.today_schedule')" icon="fa-clock" :href="route('doctor.appointments')" flush>
            @if ($todayList->isEmpty())
                <x-hms.empty icon="fa-mug-hot" :text="trans('ui.dash.no_today')" />
            @else
                <ul class="hms-list">
                    @foreach ($todayList as $appointment)
                        <li class="hms-list__item">
                            <span class="hms-time">{{ \Illuminate\Support\Carbon::parse($appointment->appointment)->format('H:i') }}</span>
                            <x-hms.avatar :name="$appointment->name" />
                            <div class="hms-list__main">
                                <span class="hms-list__title">{{ $appointment->name }}</span>
                                <span class="hms-list__meta">{{ $appointment->phone }}</span>
                            </div>
                            <x-hms.status :value="$appointment->type" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <x-hms.card :title="trans('ui.dash.next_appointments')" icon="fa-calendar-week" flush>
            @if ($nextList->isEmpty())
                <x-hms.empty icon="fa-calendar" :text="trans('ui.dash.no_next')" />
            @else
                <ul class="hms-list">
                    @foreach ($nextList as $appointment)
                        <li class="hms-list__item">
                            <div class="hms-list__main">
                                <span class="hms-list__title">{{ $appointment->name }}</span>
                                <span class="hms-list__meta">{{ \Illuminate\Support\Carbon::parse($appointment->appointment)->translatedFormat('l j M · H:i') }}</span>
                            </div>
                            <x-hms.status :value="$appointment->type" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>
    </div>

    <div class="hms-grid hms-grid--halves">
        <x-hms.card :title="trans('ui.dash.load_next_14')" icon="fa-chart-bar">
            <x-hms.chart type="bar" :labels="$upcoming['labels']" :series="[['name' => trans('ui.dash.appointments_count'), 'data' => $upcoming['values']]]" />
        </x-hms.card>
        <x-hms.card :title="trans('ui.dash.visits_30')" icon="fa-chart-area">
            <x-hms.chart type="area" :labels="$visits['labels']" :series="[['name' => trans('ui.dash.examinations'), 'data' => $visits['values']]]" :tones="['success']" />
        </x-hms.card>
    </div>

    <div class="hms-grid hms-grid--2">
        <x-hms.card :title="trans('ui.dash.recent_prescriptions')" icon="fa-file-prescription" :href="route('prescriptions.index')" flush>
            @if ($recentPrescriptions->isEmpty())
                <x-hms.empty icon="fa-prescription" :text="trans('ui.dash.no_prescriptions')" />
            @else
                <ul class="hms-list">
                    @foreach ($recentPrescriptions as $prescription)
                        <li class="hms-list__item">
                            <x-hms.avatar :name="optional($prescription->patient)->name" />
                            <div class="hms-list__main">
                                <a href="{{ route('prescriptions.show', $prescription->id) }}" class="hms-list__title">{{ optional($prescription->patient)->name ?? '—' }}</a>
                                <span class="hms-list__meta">#{{ $prescription->prescription_number }} · {{ $prescription->updated_at->diffForHumans() }}</span>
                            </div>
                            <span class="hms-pill hms-tone-primary">{{ $prescription->status_display }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <x-hms.card :title="trans('ui.dash.quick_actions')" icon="fa-bolt">
            <div class="hms-quick">
                <a href="{{ route('doctor.patients.search_for_prescription') }}"><i class="fas fa-prescription"></i>{{ trans('ui.dash.new_prescription') }}</a>
                <a href="{{ route('doctor.invoices.index') }}"><i class="fas fa-stethoscope"></i>{{ trans('ui.dash.examinations') }}</a>
                <a href="{{ route('doctor.reviewInvoices') }}"><i class="fas fa-redo-alt"></i>{{ trans('ui.dash.review_invoices') }} ({{ $kpis['review_invoices'] }})</a>
                <a href="{{ route('doctor.schedule.show') }}"><i class="far fa-calendar-alt"></i>{{ trans('ui.dash.my_schedule') }}</a>
                <a href="{{ route('doctor.chat.patients') }}"><i class="far fa-comments"></i>{{ trans('ui.dash.chat') }}</a>
                <a href="{{ route('doctor.prescriptions.approvalRequests') }}"><i class="fas fa-redo"></i>{{ trans('ui.dash.refill_requests') }}</a>
            </div>
        </x-hms.card>
    </div>
@endsection
