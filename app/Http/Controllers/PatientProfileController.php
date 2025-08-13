<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientProfileController extends Controller
{
    public function showpat()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            abort(404);
        }
    }


    public function edit()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            abort(404);
        }
    }
}
