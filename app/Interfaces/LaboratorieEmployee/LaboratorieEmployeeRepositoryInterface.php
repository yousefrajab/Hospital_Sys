<?php


 namespace App\Interfaces\LaboratorieEmployee;

 use App\Http\Requests\StoreLaboratorieEmployeeRequest;

 use App\Http\Requests\UpdateLaboratorieEmployeeRequest;

 interface LaboratorieEmployeeRepositoryInterface
 {
     public function index();

     public function edit($id);
     public function store(StoreLaboratorieEmployeeRequest $request);

     public function update(UpdateLaboratorieEmployeeRequest $request,$id);

     public function destroy($id);
 }
