<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // الإيميل الفريد
            $table->string('owner_type'); // النوع (مثل: Patient, Doctor, Employee)
            $table->unsignedBigInteger('owner_id'); // ID الخاص بالجدول
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
        Schema::dropIfExists('global_emails');
    }
}
