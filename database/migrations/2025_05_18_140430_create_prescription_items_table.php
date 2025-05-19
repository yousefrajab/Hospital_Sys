<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->cascadeOnDelete();
            $table->foreignId('medication_id')->constrained('medications'); // لا تحذف الدواء إذا حذفت الوصفة

            $table->string('dosage')->comment('الجرعة: قرص واحد، 5 مل');
            $table->string('frequency')->comment('التكرار: مرتين يوميًا، كل 8 ساعات');
            $table->string('duration')->nullable()->comment('مدة العلاج: 7 أيام، شهر واحد');
            $table->string('route_of_administration')->nullable()->comment('طريقة الإعطاء: فموي، حقن');
            $table->unsignedInteger('quantity_prescribed')->nullable()->comment('الكمية الموصوفة (مثلاً عدد الأقراص)');
            $table->text('instructions_for_patient')->nullable()->comment('تعليمات للمريض');
            $table->unsignedTinyInteger('refills_allowed')->default(0)->comment('عدد مرات إعادة الصرف المسموح بها');
            $table->unsignedTinyInteger('refills_done')->default(0)->comment('عدد مرات إعادة الصرف التي تمت');
            $table->boolean('is_prn')->default(false)->comment('هل هو دواء عند اللزوم؟');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
