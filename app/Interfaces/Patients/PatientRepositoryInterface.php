<?php

namespace App\Interfaces\Patients;

use Illuminate\Http\Request;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;


interface PatientRepositoryInterface
{
    // Get All Patients
    public function index(Request $request);
    // Create New Patients
    public function create();
    // Store new Patients
    public function store(StorePatientRequest $request);
    // edit Patients
    public function edit($id);
    // show Patients
    public function show($id);
    public function showQR($id);
    // update Patients
    public function update(UpdatePatientRequest $request);
    // Deleted Patients
    public function destroy($request);
}
