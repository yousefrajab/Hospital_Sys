<?php

namespace App\Services\Dashboard;

use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\LaboratorieEmployee;
use App\Models\Patient;
use App\Models\PatientAdmission;
use App\Models\PharmacyEmployee;
use App\Models\PharmacyManager;
use App\Models\RayEmployee;
use App\Models\Section;

/** Everything the admin home page shows, computed in one place. */
class AdminDashboard
{
    public static function build(): array
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = $lastMonthStart->copy()->endOfMonth();

        $cancelled = [Appointment::STATUS_CANCELLED, Appointment::STATUS_CANCELLED_BY_PATIENT, Appointment::STATUS_CANCELLED_BY_DOCTOR];

        // KPIs
        $revenueThisMonth = (float) Invoice::where('invoice_date', '>=', $monthStart)->sum('total_with_tax');
        $revenueLastMonth = (float) Invoice::whereBetween('invoice_date', [$lastMonthStart, $lastMonthEnd])->sum('total_with_tax');
        $newPatients = Patient::where('created_at', '>=', $monthStart)->count();
        $totalBeds = Bed::count();
        $occupiedBeds = Bed::where('status', 'occupied')->count();

        $kpis = [
            'patients' => Patient::count(),
            'new_patients' => $newPatients,
            'today_appointments' => Appointment::whereDate('appointment', $today)->whereNotIn('type', $cancelled)->count(),
            'pending_appointments' => Appointment::where('type', Appointment::STATUS_PENDING)->count(),
            'revenue' => $revenueThisMonth,
            'revenue_change' => Trend::change($revenueThisMonth, $revenueLastMonth),
            'occupancy' => $totalBeds ? (int) round($occupiedBeds / $totalBeds * 100) : 0,
            'occupied_beds' => $occupiedBeds,
            'total_beds' => $totalBeds,
            'admitted' => PatientAdmission::whereNull('discharge_date')->count(),
        ];

        // Charts
        $bookings = Trend::daily(Appointment::query(), 'created_at', 14);
        $revenue = Trend::monthlySum(Invoice::query(), 'invoice_date', 'total_with_tax', 'type', [
            1 => trans('ui.dash.cash'),
            2 => trans('ui.dash.credit'),
        ]);

        $statusCounts = Appointment::selectRaw('type, COUNT(*) as c')->groupBy('type')->pluck('c', 'type');
        $statusGroups = [
            'pending' => [Appointment::STATUS_PENDING],
            'confirmed' => [Appointment::STATUS_CONFIRMED],
            'completed' => [Appointment::STATUS_COMPLETED],
            'cancelled' => $cancelled,
            'lapsed' => [Appointment::STATUS_LAPSED],
        ];
        $statusChart = ['labels' => [], 'values' => []];
        foreach ($statusGroups as $key => $values) {
            $statusChart['labels'][] = trans('ui.status.' . $key);
            $statusChart['values'][] = (int) collect($values)->sum(fn ($v) => $statusCounts[$v] ?? 0);
        }

        $sectionRows = Invoice::selectRaw('section_id, SUM(total_with_tax) as total')
            ->whereNotNull('section_id')
            ->groupBy('section_id')
            ->orderByDesc('total')
            ->take(6)
            ->get();
        $sectionNames = Section::whereIn('id', $sectionRows->pluck('section_id'))->get()->keyBy('id');
        $bySection = [
            'labels' => $sectionRows->map(fn ($r) => optional($sectionNames->get($r->section_id))->name ?? '—')->all(),
            'values' => $sectionRows->map(fn ($r) => round((float) $r->total, 2))->all(),
        ];

        $staff = [
            ['label' => trans('ui.roles.doctor'), 'count' => Doctor::count(), 'tone' => 'primary', 'icon' => 'fa-user-md'],
            ['label' => trans('ui.roles.ray_employee'), 'count' => RayEmployee::count(), 'tone' => 'info', 'icon' => 'fa-x-ray'],
            ['label' => trans('ui.roles.laboratorie_employee'), 'count' => LaboratorieEmployee::count(), 'tone' => 'purple', 'icon' => 'fa-vials'],
            ['label' => trans('ui.roles.pharmacy_employee'), 'count' => PharmacyEmployee::count(), 'tone' => 'success', 'icon' => 'fa-prescription-bottle-alt'],
            ['label' => trans('ui.roles.pharmacy_manager'), 'count' => PharmacyManager::count(), 'tone' => 'warning', 'icon' => 'fa-clinic-medical'],
        ];

        return [
            'kpis' => $kpis,
            'bookings' => $bookings,
            'revenue' => $revenue,
            'statusChart' => $statusChart,
            'bySection' => $bySection,
            'staff' => $staff,
            'sectionsCount' => Section::count(),
            'pendingAppointments' => Appointment::where('type', Appointment::STATUS_PENDING)
                ->with(['doctor', 'section'])
                ->orderBy('appointment')
                ->take(6)
                ->get(),
            'recentPatients' => Patient::with('image')->latest()->take(6)->get(),
        ];
    }
}
