<?php

namespace Database\Seeders;

use App\Models\PharmacyEmployee;
use App\Models\PharmacyManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PharmacyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PharmacyManager::firstOrCreate(['email' => 'pm@gmail.com'], [
            'national_id' => 900000001,
            'name' => 'Pharmacy Manager',
            'phone' => '0590000001',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'status' => 1,
        ]);

        PharmacyEmployee::firstOrCreate(['email' => 'pe@gmail.com'], [
            'national_id' => 900000002,
            'name' => 'Pharmacy Employee',
            'phone' => '0590000002',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'status' => 1,
        ]);
    }
}
