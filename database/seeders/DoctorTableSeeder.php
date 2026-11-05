<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Doctor::factory()->create([
            'email' => 'doctor@hospital.com',
            'password' => Hash::make('12345678'),
        ]);

    //     $Appointments = Appointment::all();

    //     foreach ($doctors as $doctor){
    //         $Appointments = Appointment::all()->random()->id;
    //         $doctor->doctorappointments()->attach($Appointments);
    //     }
    //     Doctor::all()->each(function ($doctor) use ($Appointments) {
    //        $doctor->doctorappointments()->attach(
    //           $Appointments->random(rand(1,7))->pluck('id')->toArray()
    //        );
    //    });


    }
}
