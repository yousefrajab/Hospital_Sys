<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_patient_chronic_diseases_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_chronic_diseases', function (Blueprint $table) {
            $table->id(); // مفتاح أساسي للسجل نفسه
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('disease_id')->constrained('diseases')->cascadeOnDelete();

            $table->date('diagnosed_at')->nullable()->comment('تاريخ تشخيص هذا المرض لهذا المريض');
            $table->string('diagnosed_by')->nullable()->comment('الطبيب أو الجهة التي قامت بالتشخيص');
            $table->enum('current_status', ['active', 'controlled', 'in_remission', 'resolved'])->nullable()->comment('الحالة الحالية للمرض لدى المريض');
            // active: نشط ويتطلب متابعة / controlled: تحت السيطرة بالعلاج / in_remission: في حالة هدوء / resolved: تم الشفاء منه (قد لا ينطبق على كل الأمراض المزمنة)
            $table->text('treatment_plan')->nullable()->comment('وصف مختصر لخطة العلاج الحالية أو الأدوية الرئيسية');
            $table->text('notes')->nullable()->comment('ملاحظات خاصة بحالة هذا المرض لهذا المريض');
            $table->timestamps(); // لتتبع متى تم إضافة أو تحديث هذا السجل

            // ضمان عدم تكرار نفس المرض لنفس المريض
            $table->unique(['patient_id', 'disease_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_chronic_diseases');
    }
};
