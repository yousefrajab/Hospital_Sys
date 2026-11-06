@extends('Dashboard.layouts.master')
@section('title', trans('ui.roles.admin'))

@php
    use App\Services\Dashboard\Trend;
    $hour = now()->hour;
    $greetingKey = $hour < 12 ? 'ui.dash.greeting_morning' : 'ui.dash.greeting_evening';
    $change = $kpis['revenue_change'];
@endphp

@section('content')
    <x-hms.page-header :title="trans($greetingKey, ['name' => (auth('admin')->user()->name ?? '')])" :subtitle="trans('ui.dash.admin_subtitle') . ' · ' . now()->translatedFormat('l j F Y')">
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-primary"><i class="far fa-calendar-check me-1"></i> {{ trans('ui.dash.appointments') }}</a>
        <a href="{{ route('admin.single_invoices') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> {{ trans('ui.dash.single_invoice') }}</a>
    </x-hms.page-header>

    <div class="hms-grid hms-grid--stats">
        <x-hms.stat :label="trans('ui.dash.patients')" :value="number_format($kpis['patients'])" icon="fa-user-injured"
            :hint="trans('ui.dash.new_this_month', ['count' => $kpis['new_patients']])" :href="route('admin.Patients.index')" />
        <x-hms.stat :label="trans('ui.dash.today_appointments')" :value="number_format($kpis['today_appointments'])" icon="fa-calendar-day" tone="info"
            :hint="trans('ui.dash.pending_hint', ['count' => $kpis['pending_appointments']])" :href="route('admin.appointments.index')" />
        <x-hms.stat :label="trans('ui.dash.revenue_month')" :value="Trend::money($kpis['revenue'])" icon="fa-coins" tone="success"
            :hint="$change === null ? null : trans('ui.dash.vs_last_month', ['value' => ($change > 0 ? '+' : '') . $change])" />
        <x-hms.stat :label="trans('ui.dash.occupancy')" :value="$kpis['occupancy'] . '%'" icon="fa-procedures" tone="warning"
            :hint="trans('ui.dash.beds_hint', ['occupied' => $kpis['occupied_beds'], 'total' => $kpis['total_beds'], 'admitted' => $kpis['admitted']])"
            :href="route('admin.beds.index')" />
    </div>

    <div class="hms-grid hms-grid--2">
        <x-hms.card :title="trans('ui.dash.bookings_trend')" icon="fa-chart-area">
            <x-hms.chart type="area" :labels="$bookings['labels']" :series="[['name' => trans('ui.dash.bookings'), 'data' => $bookings['values']]]" />
        </x-hms.card>
        <x-hms.card :title="trans('ui.dash.status_split')" icon="fa-chart-pie">
            <x-hms.chart type="donut" :labels="$statusChart['labels']" :series="$statusChart['values']"
                :tones="['warning', 'primary', 'success', 'danger', 'muted']" />
        </x-hms.card>
    </div>

    <div class="hms-grid hms-grid--halves">
        <x-hms.card :title="trans('ui.dash.revenue_trend')" icon="fa-chart-bar">
            <x-hms.chart type="bar" stacked :labels="$revenue['labels']" :series="$revenue['series']" :tones="['primary', 'warning']" />
        </x-hms.card>
        <x-hms.card :title="trans('ui.dash.revenue_by_section')" icon="fa-hospital">
            <x-hms.chart type="bar" :labels="$bySection['labels']" :series="[['name' => trans('ui.dash.revenue'), 'data' => $bySection['values']]]" :tones="['info']" />
        </x-hms.card>
    </div>

    <div class="hms-grid hms-grid--3">
        <x-hms.card :title="trans('ui.dash.pending_list')" icon="fa-hourglass-half" :href="route('admin.appointments.index')" flush>
            @if ($pendingAppointments->isEmpty())
                <x-hms.empty icon="fa-check-circle" :text="trans('ui.dash.no_pending')" />
            @else
                <ul class="hms-list">
                    @foreach ($pendingAppointments as $appointment)
                        <li class="hms-list__item">
                            <x-hms.avatar :name="$appointment->name" />
                            <div class="hms-list__main">
                                <span class="hms-list__title">{{ $appointment->name }}</span>
                                <span class="hms-list__meta">
                                    {{ trans('ui.dash.with_doctor', ['name' => optional($appointment->doctor)->name ?? '—']) }}
                                    · {{ optional($appointment->section)->name }}
                                </span>
                            </div>
                            <div class="hms-list__side">
                                {{ \Illuminate\Support\Carbon::parse($appointment->appointment)->translatedFormat('j M · H:i') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <x-hms.card :title="trans('ui.dash.recent_patients')" icon="fa-user-plus" :href="route('admin.Patients.index')" flush>
            @if ($recentPatients->isEmpty())
                <x-hms.empty icon="fa-users" :text="trans('ui.dash.no_patients')" />
            @else
                <ul class="hms-list">
                    @foreach ($recentPatients as $patient)
                        <li class="hms-list__item">
                            <x-hms.avatar :name="$patient->name" :src="$patient->image ? asset('Dashboard/img/patients/' . $patient->image->filename) : null" />
                            <div class="hms-list__main">
                                <span class="hms-list__title">{{ $patient->name }}</span>
                                <span class="hms-list__meta">{{ trans('ui.dash.joined', ['time' => $patient->created_at->diffForHumans()]) }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-hms.card>

        <div class="d-flex flex-column" style="gap: 20px">
            <x-hms.card :title="trans('ui.dash.staff')" icon="fa-users-cog">
                @php($maxStaff = max(1, collect($staff)->max('count')))
                <div class="hms-meter">
                    @foreach ($staff as $row)
                        <div class="hms-tone-{{ $row['tone'] }}">
                            <div class="hms-meter__row"><span><i class="fas {{ $row['icon'] }} me-1" style="color: var(--tone)"></i> {{ $row['label'] }}</span><strong>{{ $row['count'] }}</strong></div>
                            <div class="hms-meter__bar"><span style="width: {{ round($row['count'] / $maxStaff * 100) }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            </x-hms.card>

            <x-hms.card :title="trans('ui.dash.quick_actions')" icon="fa-bolt">
                <div class="hms-quick">
                    <a href="{{ route('admin.Doctors.create') }}"><i class="fas fa-user-md"></i>{{ trans('ui.dash.add_doctor') }}</a>
                    <a href="{{ route('admin.Patients.create') }}"><i class="fas fa-user-plus"></i>{{ trans('ui.dash.add_patient') }}</a>
                    <a href="{{ route('admin.group_invoices') }}"><i class="fas fa-layer-group"></i>{{ trans('ui.dash.group_invoice') }}</a>
                    <a href="{{ route('admin.Receipt.create') }}"><i class="fas fa-receipt"></i>{{ trans('ui.dash.receipt') }}</a>
                    <a href="{{ route('admin.patient_admissions.index') }}"><i class="fas fa-procedures"></i>{{ trans('ui.dash.admissions') }}</a>
                    <a href="{{ route('admin.Sections.index') }}"><i class="fas fa-hospital"></i>{{ trans('ui.dash.sections') }} ({{ $sectionsCount }})</a>
                </div>
            </x-hms.card>
        </div>
    </div>
@endsection
