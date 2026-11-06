<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Realistic demo activity (patients, doctors, appointments, invoices) so dashboards and charts have
 * something to show. NOT part of DatabaseSeeder: run it on purpose, on a demo or test database only.
 *
 *   php artisan db:seed --class=DemoDataSeeder
 */
class DemoDataSeeder extends Seeder
{
    private const FIRST = ['أحمد', 'محمد', 'سارة', 'ليلى', 'يوسف', 'مريم', 'خالد', 'نور', 'عمر', 'هبة', 'رامي', 'دينا', 'سامي', 'رنا', 'علي', 'جنى', 'كريم', 'لين', 'طارق', 'سلمى'];
    private const LAST = ['الخطيب', 'النجار', 'حداد', 'العلي', 'منصور', 'سعادة', 'عودة', 'الشامي', 'يونس', 'قاسم'];

    public function run(): void
    {
        mt_srand(2026);
        $services = Service::all();
        if ($services->isEmpty()) {
            $this->command?->warn('No services found: run the main seeders first.');

            return;
        }

        // A few more doctors so charts show more than one department.
        while (Doctor::count() < 4) {
            Doctor::factory()->create();
        }
        $doctors = Doctor::all();

        // Patients joined over the last two months.
        $patients = collect();
        for ($i = 0; $i < 30; $i++) {
            $p = new Patient();
            $p->national_id = (string) (870000000 + $i);
            $p->email = "demo.patient{$i}@hospital.test";
            $p->password = Hash::make('12345678');
            $p->Date_Birth = Carbon::now()->subYears(mt_rand(5, 80))->subDays(mt_rand(0, 360))->toDateString();
            $p->Phone = '059' . str_pad((string) mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $p->Gender = mt_rand(1, 2);
            $p->Blood_Group = ['A+', 'A-', 'B+', 'O+', 'O-', 'AB+'][mt_rand(0, 5)];
            $p->save();
            $p->name = self::FIRST[mt_rand(0, 19)] . ' ' . self::LAST[mt_rand(0, 9)];
            $p->Address = 'غزة';
            $p->created_at = Carbon::now()->subDays(mt_rand(0, 60));
            $p->save();
            $patients->push($p);
        }

        // Appointments: 30 days back, 10 days ahead.
        for ($i = 0; $i < 90; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();
            $when = Carbon::now()->startOfDay()->addDays(mt_rand(-30, 10))->setTime(mt_rand(9, 16), [0, 30][mt_rand(0, 1)]);
            if ($when->isPast()) {
                $type = [Appointment::STATUS_COMPLETED, Appointment::STATUS_COMPLETED, Appointment::STATUS_COMPLETED, Appointment::STATUS_LAPSED, Appointment::STATUS_CANCELLED][mt_rand(0, 4)];
            } else {
                $type = [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED, Appointment::STATUS_CONFIRMED][mt_rand(0, 2)];
            }
            $booked = $when->copy()->subDays(mt_rand(1, 10));
            if ($booked->isFuture()) {
                $booked = Carbon::now()->subHours(mt_rand(1, 48));
            }
            DB::table('appointments')->insert([
                'doctor_id' => $doctor->id,
                'section_id' => $doctor->section_id,
                'patient_id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'phone' => $patient->Phone,
                'type' => $type,
                'appointment' => $when,
                'notes' => null,
                'created_at' => $booked,
                'updated_at' => $booked,
            ]);
        }

        // Invoices over the last six months, cash and credit, with their ledger rows.
        for ($i = 0; $i < 70; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();
            $service = $services->random();
            $date = Carbon::now()->subDays(mt_rand(0, 175))->toDateString();
            $price = (float) (mt_rand(8, 60) * 10);
            $taxRate = 10;
            $tax = round($price * $taxRate / 100, 2);
            $type = mt_rand(1, 3) === 1 ? 2 : 1;
            $invoiceId = DB::table('invoices')->insertGetId([
                'invoice_type' => 1,
                'invoice_date' => $date,
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'section_id' => $doctor->section_id,
                'Service_id' => $service->id,
                'price' => $price,
                'discount_value' => 0,
                'tax_rate' => $taxRate,
                'tax_value' => $tax,
                'total_with_tax' => $price + $tax,
                'type' => $type,
                'invoice_status' => 3,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $ledger = $type === 1 ? 'fund_accounts' : 'patient_accounts';
            DB::table($ledger)->insert(array_filter([
                'date' => $date,
                'invoice_id' => $invoiceId,
                'patient_id' => $type === 2 ? $patient->id : null,
                'Debit' => $price + $tax,
                'credit' => 0,
                'created_at' => $date,
                'updated_at' => $date,
            ], fn ($v) => $v !== null));
        }

        $this->seedDiagnostics($patients, $doctors);
        $this->seedPharmacy($patients, $doctors);

        $this->command?->info('Demo data added: patients, appointments, invoices, imaging/lab requests, medications and prescriptions.');
    }

    /** Imaging and lab requests: some waiting, some done by employee #1. */
    private function seedDiagnostics($patients, $doctors): void
    {
        $invoiceIds = DB::table('invoices')->pluck('id');
        foreach (['rays' => ['أشعة صدر', 'أشعة مقطعية للرأس', 'رنين مغناطيسي للركبة', 'أشعة بانوراما للأسنان'],
                  'laboratories' => ['تحليل دم شامل', 'سكر صائم', 'وظائف كبد', 'فيتامين د']] as $table => $tests) {
            for ($i = 0; $i < 22; $i++) {
                $done = mt_rand(0, 2) > 0;
                $created = Carbon::now()->subDays(mt_rand(0, 13))->subHours(mt_rand(0, 8));
                DB::table($table)->insert([
                    'description' => $tests[mt_rand(0, 3)],
                    'invoice_id' => $invoiceIds->random(),
                    'patient_id' => $patients->random()->id,
                    'doctor_id' => $doctors->random()->id,
                    'employee_id' => $done ? 1 : null,
                    'description_employee' => $done ? 'النتيجة ضمن الحدود الطبيعية.' : null,
                    'case' => $done ? 1 : 0,
                    'created_at' => $created,
                    'updated_at' => $done ? $created->copy()->addHours(mt_rand(2, 30))->min(Carbon::now()) : $created,
                ]);
            }
        }
    }

    /** Medications with stock batches (some low, expiring or out) and prescriptions in every state. */
    private function seedPharmacy($patients, $doctors): void
    {
        $meds = [
            ['Paracetamol 500mg', 'tablet', 200], ['Amoxicillin 500mg', 'capsule', 100], ['Ibuprofen 400mg', 'tablet', 150],
            ['Omeprazole 20mg', 'capsule', 80], ['Metformin 850mg', 'tablet', 120], ['Amlodipine 5mg', 'tablet', 90],
            ['Salbutamol Inhaler', 'inhaler', 20], ['Insulin Glargine', 'injection', 15], ['Atorvastatin 20mg', 'tablet', 100],
            ['Azithromycin 250mg', 'tablet', 60], ['Cetirizine 10mg', 'tablet', 70], ['Vitamin D3 1000IU', 'capsule', 50],
        ];
        $ids = [];
        foreach ($meds as $k => [$name, $form, $min]) {
            $id = DB::table('medications')->insertGetId([
                'name' => $name, 'dosage_form' => $form, 'minimum_stock_level' => $min, 'maximum_stock_level' => $min * 5,
                'purchase_price' => mt_rand(5, 60), 'selling_price' => mt_rand(10, 90), 'requires_prescription' => 1, 'status' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $ids[] = $id;
            // 0-1: out of stock, 2-4: low, rest healthy; one batch expiring soon on some.
            $qty = $k < 2 ? 0 : ($k < 5 ? (int) ($min * 0.6) : $min * mt_rand(2, 4));
            DB::table('pharmacy_stocks')->insert([
                'medication_id' => $id, 'batch_number' => 'B' . (1000 + $k), 'quantity_on_hand' => $qty, 'initial_quantity' => max($qty, $min),
                'expiry_date' => Carbon::now()->addDays($k % 4 === 0 ? mt_rand(10, 60) : mt_rand(200, 700))->toDateString(),
                'cost_price_per_unit' => mt_rand(2, 30), 'supplier_name' => 'Demo Supplier',
                'received_date' => Carbon::now()->subDays(mt_rand(0, 40))->toDateString(), 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $statuses = ['new', 'approved', 'processing', 'ready_for_pickup', 'dispensed', 'dispensed', 'dispensed', 'on_hold', 'partially_dispensed'];
        for ($i = 0; $i < 30; $i++) {
            $status = $statuses[mt_rand(0, count($statuses) - 1)];
            $date = Carbon::now()->subDays(mt_rand(0, 29));
            $pid = DB::table('prescriptions')->insertGetId([
                'prescription_number' => 'RX-' . str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                'patient_id' => $patients->random()->id, 'doctor_id' => $doctors->random()->id,
                'prescription_date' => $date->toDateString(), 'status' => $status,
                'dispensed_at' => $status === 'dispensed' ? $date->copy()->addHours(mt_rand(1, 20))->min(Carbon::now()) : null,
                'dispensed_by_pharmacy_employee_id' => $status === 'dispensed' ? 1 : null,
                'created_at' => $date, 'updated_at' => $date,
            ]);
            for ($j = 0, $n = mt_rand(1, 3); $j < $n; $j++) {
                DB::table('prescription_items')->insert([
                    'prescription_id' => $pid, 'medication_id' => $ids[array_rand($ids)], 'dosage' => '1 حبة', 'frequency' => 'مرتين يومياً',
                    'quantity_prescribed' => 14, 'quantity_dispensed' => $status === 'dispensed' ? 14 : 0,
                    'created_at' => $date, 'updated_at' => $date,
                ]);
            }
        }
    }
}
