<?php

namespace App\Services\Dashboard;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Prescription;

/** Everything the doctor home page shows, for one doctor. */
class DoctorDashboard
{
    public static function build(Doctor $doctor): array
    {
        $appointments = fn () => Appointment::where('doctor_id', $doctor->id);
        $cancelled = [Appointment::STATUS_CANCELLED, Appointment::STATUS_CANCELLED_BY_PATIENT, Appointment::STATUS_CANCELLED_BY_DOCTOR];
        $monthStart = now()->startOfMonth();

        $kpis = [
            'today' => $appointments()->whereDate('appointment', today())->whereNotIn('type', $cancelled)->count(),
            'pending' => $appointments()->where('type', Appointment::STATUS_PENDING)->where('appointment', '>=', now())->count(),
            'visits_month' => Invoice::where('doctor_id', $doctor->id)->where('invoice_date', '>=', $monthStart)->count(),
            'prescriptions_month' => Prescription::where('doctor_id', $doctor->id)->where('created_at', '>=', $monthStart)->count(),
            'refills' => Prescription::where('doctor_id', $doctor->id)->where('status', Prescription::STATUS_REFILL_REQUESTED)->count(),
            'unread' => Message::where('receiver_email', $doctor->email)->where('read', false)->count(),
            'review_invoices' => Invoice::where('doctor_id', $doctor->id)->where('invoice_status', 2)->count(),
        ];

        // Upcoming load: appointments per day for the next 14 days.
        $from = today();
        $rows = $appointments()->whereBetween('appointment', [$from, $from->copy()->addDays(13)->endOfDay()])
            ->whereNotIn('type', $cancelled)
            ->selectRaw('DATE(appointment) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');
        $upcoming = ['labels' => [], 'values' => []];
        for ($i = 0; $i < 14; $i++) {
            $day = $from->copy()->addDays($i);
            $upcoming['labels'][] = $day->translatedFormat('D j');
            $upcoming['values'][] = (int) ($rows[$day->toDateString()] ?? 0);
        }

        $visits = Trend::daily(Invoice::where('doctor_id', $doctor->id), 'invoice_date', 30);

        return [
            'doctor' => $doctor,
            'kpis' => $kpis,
            'upcoming' => $upcoming,
            'visits' => $visits,
            'todayList' => $appointments()->whereDate('appointment', today())->whereNotIn('type', $cancelled)
                ->with('patient')->orderBy('appointment')->get(),
            'nextList' => $appointments()->where('appointment', '>', today()->endOfDay())
                ->whereIn('type', [Appointment::STATUS_CONFIRMED, Appointment::STATUS_PENDING])
                ->orderBy('appointment')->take(6)->get(),
            'recentPrescriptions' => Prescription::where('doctor_id', $doctor->id)->with('patient')
                ->latest('updated_at')->take(5)->get(),
        ];
    }
}
