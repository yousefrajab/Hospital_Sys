<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // في ملف migration الجديد
    public function up()
    {
        Schema::create('doctor_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_working_day_id')->constrained()->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_breaks');
    }
}
