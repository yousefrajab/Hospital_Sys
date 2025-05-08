<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaboratorieEmployeeRequest;
use App\Http\Requests\UpdateLaboratorieEmployeeRequest;
use App\Interfaces\LaboratorieEmployee\LaboratorieEmployeeRepositoryInterface;

class LaboratorieEmployeeController extends Controller
{

    private $laboratorie_employee;

    public function __construct(LaboratorieEmployeeRepositoryInterface $laboratorie_employee)
    {
        $this->laboratorie_employee = $laboratorie_employee;
    }

    public function index()
    {
        return $this->laboratorie_employee->index();
    }

    public function edit($id)
    {
        return $this->laboratorie_employee->edit($id);
    }
    public function store(StoreLaboratorieEmployeeRequest $request)
    {
        return $this->laboratorie_employee->store($request);
    }


    public function update(UpdateLaboratorieEmployeeRequest $request, $id)
    {
        return $this->laboratorie_employee->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->laboratorie_employee->destroy($id);
    }
}
