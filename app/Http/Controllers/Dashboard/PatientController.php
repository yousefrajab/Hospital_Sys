<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Ray;
use App\Models\Invoice;
use App\Models\Section;
use App\Models\Appointment;
use App\Models\Laboratorie;
use Illuminate\Http\Request;
use App\Models\ReceiptAccount;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Mail\AppointmentCancelledByPatientToDoctor;
use App\Interfaces\Patients\PatientRepositoryInterface;

class PatientController extends Controller
{

    private $Patient;

    public function __construct(PatientRepositoryInterface $Patient)
    {
        $this->Patient = $Patient;
    }
    
    public function index(Request $request)
    {
        return $this->Patient->index($request);
    }


    public function create()
    {
        return $this->Patient->create();
    }


    public function store(StorePatientRequest $request)
    {
        return $this->Patient->store($request);
    }


    public function show($id)
    {
        return $this->Patient->show($id);
    }

    public function showQR($id)
    {
        return $this->Patient->showQR($id);
    }

    public function edit($id)
    {
        return $this->Patient->edit($id);
    }


    public function update(UpdatePatientRequest $request)
    {
        return $this->Patient->update($request);
    }


    public function destroy(Request $request)
    {
        return $this->Patient->destroy($request);
    }
}
