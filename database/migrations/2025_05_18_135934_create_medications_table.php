<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicationsTable extends Migration
{

    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();

            // الاسم الأساسي (تجاري أو الذي سيعرض للمستخدم) - قد يكون مترجمًا
            $table->string('name'); // إذا كان مترجمًا، سينتقل لجدول الترجمة
            $table->string('generic_name')->nullable()->comment('الاسم العلمي/العام للدواء');
            $table->text('description')->nullable()->comment('وصف مختصر للدواء واستخداماته');
            $table->string('category')->nullable()->comment('تصنيف الدواء، مثال: مضاد حيوي، مسكن');
            $table->string('manufacturer')->nullable()->comment('الشركة المصنعة');

            $table->string('dosage_form')->nullable()->comment('الشكل الصيدلاني: أقراص، شراب، حقن');
            $table->string('strength')->nullable()->comment('تركيز الدواء: 500mg, 10mg/5ml');
            $table->string('unit_of_measure')->nullable()->comment('وحدة القياس الأساسية: قرص، علبة، مل');
            $table->string('barcode')->nullable()->unique()->comment('الباركود العالمي للمنتج (UPC/EAN)');

            // معلومات المخزون والتسعير
            $table->unsignedInteger('minimum_stock_level')->default(10)->comment('حد الطلب الأدنى للمخزون');
            $table->unsignedInteger('maximum_stock_level')->nullable()->comment('الحد الأقصى للمخزون (اختياري)');
            $table->decimal('purchase_price', 8, 2)->nullable()->comment('سعر الشراء للوحدة');
            $table->decimal('selling_price', 8, 2)->nullable()->comment('سعر البيع للوحدة للمريض');

            // معلومات تنظيمية وإضافية
            $table->boolean('requires_prescription')->default(true)->comment('هل يتطلب وصفة طبية؟');
            $table->text('contraindications')->nullable()->comment('موانع الاستعمال'); // قد يكون مترجمًا
            $table->text('side_effects')->nullable()->comment('الآثار الجانبية الشائعة'); // قد يكون مترجمًا

            $table->boolean('status')->default(true)->comment('نشط/غير نشط في قائمة الأدوية');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
