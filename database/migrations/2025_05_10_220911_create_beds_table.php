<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->string('bed_number')->comment('رقم أو كود السرير داخل الغرفة');

            $table->enum('type', [
                'standard',
                'icu_bed',
                'pediatric_bed',
                'special_care_bed',
                'other'
            ])->default('standard');

            $table->enum('status', [
                'available',
                'occupied',
                // القيم التي تم تعليقها يمكن إضافتها لاحقًا:
                // 'reserved', 'maintenance', 'cleaning'
            ])->default('available');

            // الحقول التي تم تعليقها (يمكن إضافتها لاحقًا إذا لزم الأمر)
            // $table->boolean('is_window_side')->default(false);
            // $table->json('features')->nullable();

            $table->timestamps();

            $table->unique(['room_id', 'bed_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
}
