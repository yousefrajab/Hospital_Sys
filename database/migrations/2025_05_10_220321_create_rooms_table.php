<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnDelete();

            $table->string('room_number')->comment('رقم أو كود الغرفة، يجب أن يكون فريدًا داخل القسم');

            $table->enum('type', [
                'patient_room',
                'private_room',
                'semi_private_room',
                'icu_room',
                'examination_room',
                'consultation_room',
                'treatment_room',
                'operating_room',
                'radiology_room',
                'laboratory_room',
                'office',
                'other'
            ])->default('patient_room');

            $table->enum('gender_type', ['male', 'female', 'mixed', 'any'])->default('any')
                ->comment('تحديد جنس المرضى المسموح بهم في الغرفة (لغرف المرضى)');

            $table->string('floor')->nullable()->comment('الطابق الذي توجد به الغرفة');

            $table->enum('status', [
                'available',
                'partially_occupied',
                'fully_occupied',
                'out_of_service',
                // القيم التي تم تعليقها يمكن إضافتها لاحقًا إذا لزم الأمر:
                // 'maintenance', 'cleaning', 'reserved'
            ])->default('available');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['section_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
}
