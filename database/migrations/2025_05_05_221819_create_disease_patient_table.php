<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseasePatientTable extends Migration
{
    public function up()
    {
        Schema::create('disease_patient', function (Blueprint $table) {
            // المفاتيح الأجنبية
            $table->foreignId('disease_id')->constrained('diseases')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

            // جعل المفتاحين معًا مفتاحًا أساسيًا لمنع التكرار
            $table->primary(['disease_id', 'patient_id']);

            // (اختياري) يمكن إضافة حقول أخرى هنا مثل:
            // $table->text('notes')->nullable(); // ملاحظات خاصة بهذه الحالة للمريض
            // $table->date('diagnosed_at')->nullable(); // تاريخ التشخيص
            // $table->boolean('is_active')->default(true); // هل الحالة لا تزال نشطة؟
        });
    }

    public function down()
    {
        Schema::dropIfExists('disease_patient');
    }
}
