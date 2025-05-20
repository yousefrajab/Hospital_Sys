<?php

namespace App\Http\Controllers\Dashboard_Doctor;

use App\Models\Ray;
use App\Models\Patient;
use App\Models\Diagnostic;
use App\Models\Laboratorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PatientDetailsController extends Controller
{
    public function index($id)
    {
        Log::info("PatientDetailsController@index: Attempting to load details for Patient ID: {$id}");

        $patient = Patient::with([
            'image',
            'diagnosedChronicDiseases',
            'prescription' => function ($query) {
                $query->with(['doctor'])
                      ->orderBy('prescription_date', 'desc')->take(10);
            },
            'admissions' => function ($query) {
                $query->with(['bed.room.section', 'doctor'])
                      ->orderBy('admission_date', 'desc')->take(5);
            }
        ])->find($id);

        if (!$patient) {
            Log::error("PatientDetailsController@index: Patient with ID {$id} not found.");
            abort(404, 'المريض المطلوب غير موجود.');
        }

        $patient_records = Diagnostic::where('patient_id', $id)
                                     ->with(['doctor'])
                                     ->orderBy('date', 'desc')->get();

        $patient_rays = Ray::where('patient_id', $id)
                             ->with(['doctor', 'employee'])
                             ->orderBy('created_at', 'desc')->get();

        $patient_Laboratories = Laboratorie::where('patient_id', $id)
                                         ->with(['doctor', 'employee'])
                                         ->orderBy('created_at', 'desc')->get();

        Log::info("PatientDetailsController@index: Successfully loaded details for Patient ID: {$id}, Name: {$patient->name}");

        return view('Dashboard.Doctors.Patients.details', compact(
            'patient',
            'patient_records',
            'patient_rays',
            'patient_Laboratories'
        ));
    }
}
