<?php

namespace App\Interfaces\RayEmployee;

use App\Http\Requests\RayEmployee\StoreRayEmployeeRequest;
use App\Http\Requests\RayEmployee\UpdateRayEmployeeRequest;

interface RayEmployeeRepositoryInterface
{
    public function index();
    public function edit($id);

    public function store(StoreRayEmployeeRequest $request);

    public function update(UpdateRayEmployeeRequest $request,$id);

    public function destroy($id);

}
