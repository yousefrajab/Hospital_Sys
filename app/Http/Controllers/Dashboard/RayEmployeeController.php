<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RayEmployee\StoreRayEmployeeRequest;
use App\Http\Requests\RayEmployee\UpdateRayEmployeeRequest;
use App\Interfaces\RayEmployee\RayEmployeeRepositoryInterface;

class RayEmployeeController extends Controller
{
    private $employee;

    public function __construct(RayEmployeeRepositoryInterface $employee)
    {
        $this->employee = $employee;
    }


    public function index()
    {
        return $this->employee->index();
    }


    public function edit($id)
    {
        return $this->employee->edit($id);
    }



    public function store(StoreRayEmployeeRequest $request)
    {
        return $this->employee->store($request);
    }



    public function update(UpdateRayEmployeeRequest $request, $id)
    {
        return $this->employee->update($request,$id);
    }


    public function destroy($id)
    {
        return $this->employee->destroy($id);
    }
}
