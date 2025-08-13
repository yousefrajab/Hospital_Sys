<?php

namespace App\Http\Controllers\Auth;

use App\Models\Disease;
use App\Models\PatientChronicDisease;
use App\Models\Patient;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\StoreRegPatientRequest;


class RegisteredPatientController extends Controller
{
    use UploadTrait;

    public function create()
    {
        $diseases_list = Disease::where('is_chronic', true)->orderBy('name')->pluck('name', 'id');
        $chronic_disease_statuses = PatientChronicDisease::getStatuses();
        return view('auth.register2', compact('diseases_list', 'chronic_disease_statuses'));
    }

    public function store(StoreRegPatientRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $patientData = [
                'national_id' => $validatedData['national_id'],
                'email' => strtolower($validatedData['email']),
                'password' => Hash::make($validatedData['password']),
                'Date_Birth' => $validatedData['Date_Birth'],
                'Phone' => $validatedData['Phone'],
                'Gender' => $validatedData['Gender'],
                'Blood_Group' => $validatedData['Blood_Group'],
                'initial_allergies_text' => $validatedData['Allergies'] ?? null,
            ];

            // if (isset($validatedData['chronic_diseases']) && is_string($validatedData['chronic_diseases'])) {
            //     $patientData['initial_chronic_diseases_text'] = $validatedData['chronic_diseases'];
            // }


            $patient = Patient::create($patientData);

            $patient->name = $validatedData['name'];
            $patient->Address = $validatedData['Address'] ?? null;
            $patient->save();

            if ($request->hasFile('photo')) {
                $this->verifyAndStoreImage(
                    $request, 'photo', 'patients', 'upload_image',
                    $patient->id, Patient::class
                );
            }

            if (isset($validatedData['chronic_diseases']) && is_array($validatedData['chronic_diseases'])) {
                $syncData = [];
                foreach ($validatedData['chronic_diseases'] as $diseaseInput) {
                    if (!empty($diseaseInput['disease_id'])) {
                        $syncData[$diseaseInput['disease_id']] = [
                            'diagnosed_at' => $diseaseInput['diagnosed_at'] ?? null,
                            'current_status' => $diseaseInput['current_status'] ?? null,
                            'notes' => $diseaseInput['notes'] ?? null,
                        ];
                    }
                }
                if (!empty($syncData)) {
                    $patient->diagnosedChronicDiseases()->sync($syncData);
                    Log::info("Synced chronic diseases for new patient ID: {$patient->id}", $syncData);
                }
            }
            DB::commit();
            Log::info("New patient registered via public form. ID: {$patient->id}, Email: {$patient->email}");

            event(new Registered($patient));
            Auth::guard('patient')->login($patient);

            return redirect()->intended(RouteServiceProvider::PATIENT)
                             ->with('status_success', 'تم تسجيل حسابك بنجاح! مرحبًا بك.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Patient (public) registration validation error: ", $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Patient (public) registration general error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error_message', 'حدث خطأ أثناء عملية التسجيل: ' . $e->getMessage());
        }
    }
}
