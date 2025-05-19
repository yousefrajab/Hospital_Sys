<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{

    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number')->unique()->nullable()->comment('رقم وصفي فريد للوصفة'); // ** جديد **

            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->comment('الطبيب الذي كتب الوصفة'); // لا تحذف الطبيب إذا حذفت الوصفة
            $table->foreignId('patient_admission_id')->nullable()->constrained('patient_admissions')->nullOnDelete()->comment('سجل الدخول المرتبط (إن وجد)'); // ** جديد **

            $table->date('prescription_date');
            $table->enum('status', [
                'new',
                'pending_review',
                'approved',
                'dispensed',
                'partially_dispensed',
                'on_hold',
                'cancelled_by_doctor',
                'cancelled_by_pharmacist',
                'cancelled_by_patient',
                'expired'
            ])->default('new');

            $table->text('doctor_notes')->nullable()->comment('ملاحظات الطبيب للصيدلي');
            $table->text('pharmacy_notes')->nullable()->comment('ملاحظات الصيدلي على الوصفة'); // ** جديد **

            $table->foreignId('dispensed_by_pharmacy_employee_id')->nullable()->constrained('pharmacy_employees')->nullOnDelete()->comment('الصيدلي الذي قام بالصرف'); // ** جديد **
            $table->timestamp('dispensed_at')->nullable()->comment('تاريخ ووقت الصرف الكامل'); // ** جديد **

            $table->decimal('total_amount', 10, 2)->nullable()->comment('التكلفة الإجمالية للوصفة (إذا تم حسابها)'); // ** جديد **
            $table->boolean('is_chronic_prescription')->default(false)->comment('هل هي وصفة لمرض مزمن؟'); // ** جديد **
            $table->date('next_refill_due_date')->nullable()->comment('تاريخ استحقاق إعادة الصرف التالية'); // ** جديد **

            $table->timestamps();

            $table->index('status');
            $table->index('prescription_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
