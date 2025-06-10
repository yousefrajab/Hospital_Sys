<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_admission_id')->constrained('patient_admissions')->cascadeOnDelete();
            // $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete(); // يمكن إضافته لتسهيل الاستعلامات المباشرة على المريض، لكن patient_admission_id كافٍ
            $table->dateTime('recorded_at')->default(now());
            $table->decimal('temperature', 4, 1)->nullable()->comment('درجة الحرارة (مئوية)');
            $table->integer('systolic_bp')->nullable()->comment('ضغط الدم الانقباضي (mmHg)');
            $table->integer('diastolic_bp')->nullable()->comment('ضغط الدم الانبساطي (mmHg)');
            $table->integer('heart_rate')->nullable()->comment('معدل نبضات القلب (bpm)');
            $table->integer('respiratory_rate')->nullable()->comment('معدل التنفس (rpm)');
            $table->decimal('oxygen_saturation', 4, 1)->nullable()->comment('تشبع الأكسجين (%)');
            $table->unsignedTinyInteger('pain_level')->nullable()->comment('مستوى الألم (0-10)');
            // يمكنك إضافة حقول أخرى مثل: الطول، الوزن، محيط الرأس (للأطفال) إذا كانت تسجل بشكل دوري
            // $table->decimal('height_cm', 5, 1)->nullable();
            // $table->decimal('weight_kg', 5, 2)->nullable();
            // $table->decimal('bmi', 4, 2)->nullable(); // يمكن حسابه
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete()->comment('المستخدم الذي سجل القراءة (طبيب/ممرض)'); // افترض أن لديك جدول users للموظفين
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
    }
};
