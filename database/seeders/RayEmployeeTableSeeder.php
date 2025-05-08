<?php

namespace Database\Seeders;

use App\Models\RayEmployee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RayEmployeeTableSeeder extends Seeder
{

    public function run()
    {
        $ray_employee = new RayEmployee();

        $ray_employee->name = 'محمد احمد';
        $ray_employee->national_id = '123456789';

        $ray_employee->email = 'm@yahoo.com';
        $ray_employee->phone = '0592612643';
        $ray_employee->status = 1;
        $ray_employee->password = Hash::make('12345678');
        $ray_employee->save();
    }
}
