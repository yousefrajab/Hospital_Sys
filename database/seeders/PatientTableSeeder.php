<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientTableSeeder extends Seeder
{

    public function run()
    {
        $Patients = new Patient();
        $Patients->national_id = '147258369';
        $Patients->email = 'Y@gmail.com';
        $Patients->password = Hash::make('12345678');
        $Patients->Date_Birth = '1988-12-01';
        $Patients->Phone = '0592611537';
        $Patients->Gender = 1;
        $Patients->Blood_Group = 'A+';
        $Patients->save();

        //insert trans
        $Patients->name = 'محمد السيد';
        $Patients->Address = 'القاهرة';
        $Patients->save();
    }
}
