<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_id')->constrained('medications')->cascadeOnDelete();
            $table->string('batch_number')->nullable()->comment('رقم الدفعة/التشغيلة');
            $table->date('expiry_date');
            $table->integer('quantity_on_hand')->default(0)->comment('الكمية الحالية في هذه الدفعة');
            $table->integer('initial_quantity')->default(0)->comment('الكمية الأولية عند استلام الدفعة'); // ** حقل جديد **

            $table->decimal('cost_price_per_unit', 8, 2)->nullable()->comment('سعر تكلفة الوحدة لهذه الدفعة'); // ** حقل جديد **
            $table->string('supplier_name')->nullable(); // أو اسم المورد كنص مبدئيًا
            $table->date('received_date')->nullable()->comment('تاريخ استلام الدفعة'); // ** حقل جديد **
            $table->text('stock_notes')->nullable()->comment('ملاحظات على هذه الدفعة'); // ** حقل جديد **

            $table->timestamps();

            $table->index(['medication_id', 'expiry_date']);
            $table->index('batch_number');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_stocks');
    }
};
