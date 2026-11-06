<?php

namespace App\Services\Dashboard;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Laboratorie;
use App\Models\Message;
use App\Models\Patient;
use App\Models\PatientAccount;
use App\Models\Prescription;
use App\Models\Ray;

/** Everything the patient home page shows, for one patient. */
class PatientDashboard
{
    public static function build(Patient $patient): array
    {
        $open = [Appointment::STATUS_CONFIRMED, Appointment::STATUS_PENDING];
        $upcoming = Appointment::where('patient_id', $patient->id)
            ->where('appointment', '>=', now())
            ->whereIn('type', $open)
            ->with(['doctor', 'section'])
            ->orderBy('appointment')
            ->get();

        $debit = (float) PatientAccount::where('patient_id', $patient->id)->sum('Debit');
        $credit = (float) PatientAccount::where('patient_id', $patient->id)->sum('credit');

        $refillDue = Prescription::where('patient_id', $patient->id)
            ->where('is_chronic_prescription', true)
            ->whereBetween('next_refill_due_date', [today(), today()->addDays(7)])
            ->whereNotIn('status', [
                Prescription::STATUS_REFILL_REQUESTED, Prescription::STATUS_CANCELLED_BY_DOCTOR,
                Prescription::STATUS_CANCELLED_BY_PATIENT, Prescription::STATUS_EXPIRED,
            ])
            ->orderBy('next_refill_due_date')
            ->first();

        return [
            'patient' => $patient,
            'next' => $upcoming->first(),
            'upcoming' => $upcoming->slice(1)->take(4),
            'kpis' => [
                'upcoming' => $upcoming->count(),
                'ready_prescriptions' => Prescription::where('patient_id', $patient->id)->where('status', Prescription::STATUS_READY_FOR_PICKUP)->count(),
                'balance' => max(0, $debit - $credit),
                'unread' => Message::where('receiver_email', $patient->email)->where('read', false)->count(),
                'results' => Ray::where('patient_id', $patient->id)->where('case', 1)->count()
                    + Laboratorie::where('patient_id', $patient->id)->where('case', 1)->count(),
            ],
            'refillDue' => $refillDue,
            'prescriptions' => Prescription::where('patient_id', $patient->id)->with('doctor')->latest('prescription_date')->take(4)->get(),
            'invoices' => Invoice::where('patient_id', $patient->id)->with(['Service', 'Group', 'Doctor'])->latest('invoice_date')->take(4)->get(),
        ];
    }
}
