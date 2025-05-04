<?php

namespace App\Repository\Patients;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PatientAccount;
use App\Models\ReceiptAccount;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StorePatientRequest;



use App\Interfaces\Patients\PatientRepositoryInterface;

use App\Models\single_invoice;
use Illuminate\Database\Eloquent\Model;

class PatientRepository implements PatientRepositoryInterface
{
    public function index()
    {
        $Patients = Patient::all();
        return view('Dashboard.Patients.index', compact('Patients'));
    }

    public function Show($id)
    {
        $Patient = patient::findorfail($id);
        $invoices = single_invoice::where('patient_id', $id)->get();
        $invoices = Invoice::where('patient_id', $id)->get();
        $receipt_accounts = ReceiptAccount::where('patient_id', $id)->get();
        $Patient_accounts = PatientAccount::where('patient_id', $id)->get();
        //  $Patient_accounts = PatientAccount::where('patient_id', $id)->get();

        return view('Dashboard.Patients.show', compact('Patient', 'invoices', 'receipt_accounts', 'Patient_accounts'));
    }

    public function create()
    {
        return view('Dashboard.Patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        try {
            $Patients = new Patient();
            $Patients->national_id = $request->national_id;
            $Patients->email = $request->email;
            $Patients->password = Hash::make($request->password);
            $Patients->Date_Birth = $request->Date_Birth;
            $Patients->Phone = $request->Phone;
            $Patients->Gender = $request->Gender;
            $Patients->Blood_Group = $request->Blood_Group;

            // حفظ الحقول المترجمة
            $Patients->translateOrNew(app()->getLocale())->name = $request->name;
            $Patients->translateOrNew(app()->getLocale())->Address = $request->Address;

            $Patients->save();

            session()->flash('add');
            return back()->with('success', 'تم تسجيل المريض بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $Patient = Patient::findorfail($id);
        return view('Dashboard.Patients.edit', compact('Patient'));
    }
    public function update(StorePatientRequest $request)
    {
        $Patient = Patient::findOrFail($request->id);
        $Patient->national_id = $request->national_id;
        $Patient->email = $request->email;
        $Patient->password = Hash::make($request->password);
        $Patient->Date_Birth = $request->Date_Birth;
        $Patient->Pheone = $request->Phone;
        $Patient->Gender = $request->Gender;
        $Patient->Blood_Group = $request->Blood_Group;
        $Patient->save();
        // insert trans
        $Patient->name = $request->name;
        $Patient->Address = $request->Address;
        $Patient->save();
        session()->flash('edit');
        return redirect()->route('admin.Patients.index');
    }

    public function destroy($request)
    {
        Patient::destroy($request->id);
        session()->flash('delete');
        return redirect()->back();
    }
}
