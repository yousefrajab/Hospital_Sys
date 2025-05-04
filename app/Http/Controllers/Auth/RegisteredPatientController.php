<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\StorePatientRequest;
use App\Models\Patient; // استخدام نموذج المريض

class RegisteredPatientController extends Controller
{
    public function create()
    {
        return view('auth.register2');
    }
    public function store(StorePatientRequest $request)
    {


        // إنشاء مريض جديد في جدول المرضى

            $patient = Patient::create([
                'national_id' => $request->national_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'Date_Birth' => $request->Date_Birth,
                'Phone' => $request->Phone,
                'Gender' => $request->Gender,
                'Blood_Group' => $request->Blood_Group,
                'Address' => $request->Address,
            ]);



        // event(new Registered($patient));

        Auth::login($patient);

        return redirect(RouteServiceProvider::PATIENT);
    }
}
