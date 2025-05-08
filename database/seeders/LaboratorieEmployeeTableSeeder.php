<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Hash;

class LaboratorieEmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lab_employee = new LaboratorieEmployee();

        $lab_employee->name = 'سمير احمد ';
        $lab_employee->national_id = '401234567';

         $lab_employee->email = 'Lab@gmail.com';
         $lab_employee->phone = '0592612643';
         $lab_employee->status = 1;
         $lab_employee->password = Hash::make('12345678');
         $lab_employee->save();
    }
}
