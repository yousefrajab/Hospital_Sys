<?php

namespace App\Interfaces\Patients;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;


interface PatientRepositoryInterface
{
    // Get All Patients
    public function index();
    // Create New Patients
    public function create();
    // Store new Patients
    public function store(StorePatientRequest $request);
    // edit Patients
    public function edit($id);
    // show Patients
    public function show($id);
    // update Patients
    public function update(UpdatePatientRequest $request);
    // Deleted Patients
    public function destroy($request);
}
