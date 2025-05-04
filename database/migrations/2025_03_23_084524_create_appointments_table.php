<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');

            // عرف user_id هنا مباشرة بالترتيب الذي تريده
            $table->foreignId('patient_id')
                ->nullable() // السماح بقيم فارغة
                ->constrained('patients') // <-- الربط مع جدول patients
                ->onDelete('cascade'); // أو set null

            // باقي الأعمدة بالترتيب
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->enum('type', ['غير مؤكد', 'مؤكد', 'منتهي', 'ملغي'])->default('غير مؤكد');
            $table->dateTime('appointment')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps(); // created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
