<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();

            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();

            $table->text('reason_for_admission')->nullable();
            $table->text('discharge_reason')->nullable();
            $table->string('admitting_diagnosis')->nullable();
            $table->string('discharge_diagnosis')->nullable();

            $table->enum('status', ['admitted', 'discharged', 'transferred_out', 'transferred_in', 'cancelled'])
                  ->default('admitted');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'admission_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_admissions');
    }
}
